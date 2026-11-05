<?php
/**
 * OrderPrintex - Admin Quotation Management
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_permission('quotations.view');
$adminUser = auth_user();

// Handle Quotation Saving & Status Changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/quotations.php'));
        exit;
    }

    $formAction = $_POST['form_action'];

    if ($formAction === 'save_quotation') {
        $quoteId = intval($_POST['quote_id'] ?? 0);
        $enquiryId = !empty($_POST['enquiry_id']) ? intval($_POST['enquiry_id']) : null;
        $customerName = trim($_POST['customer_name'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');
        $validityDays = max(1, intval($_POST['validity_days'] ?? 15));
        $taxRate = floatval($_POST['tax_rate'] ?? 18.00);
        $deliveryCharge = floatval($_POST['delivery_charge'] ?? 0.00);
        $adminNotes = trim($_POST['admin_notes'] ?? '');

        if (empty($customerName) || empty($customerEmail)) {
            set_flash('error', 'Customer name and email are required.');
            header('Location: ' . url('admin/quotations.php' . ($quoteId ? '?quote_id=' . $quoteId : '?action=create')));
            exit;
        }

        // Process line items
        $items = $_POST['items'] ?? [];
        $subtotal = 0.00;
        $processedItems = [];

        foreach ($items as $item) {
            $desc = trim($item['description'] ?? '');
            if (empty($desc)) continue;
            $dims = trim($item['dimensions'] ?? '');
            $qty = max(1, intval($item['quantity'] ?? 1));
            $unitPrice = max(0, floatval($item['unit_price'] ?? 0));
            $rowTotal = $qty * $unitPrice;
            $subtotal += $rowTotal;

            $processedItems[] = [
                'item_description' => $desc,
                'dimensions'       => $dims,
                'quantity'         => $qty,
                'unit_price'       => $unitPrice,
                'total_price'      => $rowTotal
            ];
        }

        $taxAmount = ($subtotal * $taxRate) / 100.0;
        $grandTotal = $subtotal + $taxAmount + $deliveryCharge;
        $validUntil = date('Y-m-d', strtotime("+{$validityDays} days"));

        // Match user account if exists
        $user = db_fetch_one("SELECT id FROM users WHERE email = :email", ['email' => $customerEmail]);
        $userId = $user ? $user['id'] : null;

        if ($quoteId > 0) {
            // Update existing
            $quote = db_fetch_one("SELECT * FROM quotations WHERE id = :id", ['id' => $quoteId]);
            if ($quote) {
                db_update('quotations', [
                    'customer_name'   => $customerName,
                    'customer_email'  => $customerEmail,
                    'user_id'         => $userId,
                    'subtotal'        => $subtotal,
                    'tax_rate'        => $taxRate,
                    'tax_amount'      => $taxAmount,
                    'delivery_charge' => $deliveryCharge,
                    'grand_total'     => $grandTotal,
                    'validity_days'   => $validityDays,
                    'valid_until'     => $validUntil,
                    'admin_notes'     => $adminNotes,
                    'status'          => $status
                ], 'id = :id', ['id' => $quoteId]);

                // Replace items
                db_delete('quotation_items', 'quotation_id = :qid', ['qid' => $quoteId]);
                foreach ($processedItems as $pItem) {
                    $pItem['quotation_id'] = $quoteId;
                    db_insert('quotation_items', $pItem);
                }

                log_activity($adminUser['id'], 'update_quotation', "Updated quotation {$quote['quote_number']}");
                set_flash('success', "Quotation {$quote['quote_number']} updated successfully.");
            }
        } else {
            // Insert new quotation
            $quoteNumber = generate_ref('QP');
            $newQuoteId = db_insert('quotations', [
                'quote_number'    => $quoteNumber,
                'enquiry_id'      => $enquiryId,
                'user_id'         => $userId,
                'customer_name'   => $customerName,
                'customer_email'  => $customerEmail,
                'subtotal'        => $subtotal,
                'tax_rate'        => $taxRate,
                'tax_amount'      => $taxAmount,
                'delivery_charge' => $deliveryCharge,
                'grand_total'     => $grandTotal,
                'validity_days'   => $validityDays,
                'valid_until'     => $validUntil,
                'admin_notes'     => $adminNotes,
                'status'          => $status
            ]);

            foreach ($processedItems as $pItem) {
                $pItem['quotation_id'] = $newQuoteId;
                db_insert('quotation_items', $pItem);
            }

            // If linked to enquiry, mark enquiry as quoted
            if ($enquiryId) {
                db_update('enquiries', ['status' => 'quoted'], 'id = :id', ['id' => $enquiryId]);
            }

            log_activity($adminUser['id'], 'create_quotation', "Created quotation {$quoteNumber} for {$customerName}");
            set_flash('success', "Quotation {$quoteNumber} generated and saved successfully.");
            $quoteId = $newQuoteId;
        }

        header('Location: ' . url('admin/quotations.php?quote_id=' . $quoteId));
        exit;
    }

    if ($formAction === 'delete_quotation') {
        $quoteId = intval($_POST['quote_id'] ?? 0);
        $q = db_fetch_one("SELECT * FROM quotations WHERE id = :id", ['id' => $quoteId]);
        if ($q) {
            db_delete('quotation_items', 'quotation_id = :id', ['id' => $quoteId]);
            db_delete('quotations', 'id = :id', ['id' => $quoteId]);
            log_activity($adminUser['id'], 'delete_quotation', "Deleted quotation {$q['quote_number']}");
            set_flash('success', "Quotation {$q['quote_number']} was deleted.");
        }
        header('Location: ' . url('admin/quotations.php'));
        exit;
    }
}

// Mode determination: list, create, edit
$action = $_GET['action'] ?? '';
$selectedQuoteId = intval($_GET['quote_id'] ?? 0);
$fromEnquiryId = intval($_GET['enquiry_id'] ?? 0);

$editQuote = null;
$quoteItems = [];
$linkedEnquiry = null;

if ($selectedQuoteId) {
    $editQuote = db_fetch_one("
        SELECT q.*, e.ref_number as enquiry_ref, e.customer_phone, e.company_name, e.service_name
        FROM quotations q
        LEFT JOIN enquiries e ON q.enquiry_id = e.id
        WHERE q.id = :id
    ", ['id' => $selectedQuoteId]);

    if ($editQuote) {
        $quoteItems = db_fetch_all("SELECT * FROM quotation_items WHERE quotation_id = :qid ORDER BY id ASC", ['qid' => $selectedQuoteId]);
        if ($editQuote['enquiry_id']) {
            $linkedEnquiry = db_fetch_one("SELECT * FROM enquiries WHERE id = :id", ['id' => $editQuote['enquiry_id']]);
        }
    }
} elseif ($action === 'create' && $fromEnquiryId) {
    $linkedEnquiry = db_fetch_one("SELECT * FROM enquiries WHERE id = :id", ['id' => $fromEnquiryId]);
    $linkedFiles = db_fetch_all("SELECT * FROM enquiry_files WHERE enquiry_id = :id", ['id' => $fromEnquiryId]);
}

// Search & Filter for List View
$statusFilter = trim($_GET['status'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');

$listSql = "SELECT q.*, e.ref_number as enquiry_ref, (SELECT COUNT(*) FROM quotation_items qi WHERE qi.quotation_id = q.id) as items_count
            FROM quotations q
            LEFT JOIN enquiries e ON q.enquiry_id = e.id
            WHERE 1=1";
$listParams = [];

if ($statusFilter) {
    $listSql .= " AND q.status = :status";
    $listParams['status'] = $statusFilter;
}

if ($searchTerm) {
    $listSql .= " AND (q.quote_number LIKE :s OR q.customer_name LIKE :s OR q.customer_email LIKE :s OR e.ref_number LIKE :s)";
    $listParams['s'] = '%' . $searchTerm . '%';
}

$listSql .= " ORDER BY q.created_at DESC";
$quotationsList = db_fetch_all($listSql, $listParams);

$adminTitle = "Manage Client Quotations";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Client Quotations</h1>
        <p class="admin-page-subtitle">Formulate itemized pricing proposals, apply GST & delivery, and publish formal quotes to clients.</p>
    </div>
    <div class="admin-topbar-actions">
        <?php if ($editQuote || $action === 'create'): ?>
            <a href="<?= url('admin/quotations.php') ?>" class="btn btn-outline btn-sm">
                &larr; Back to Quotations List
            </a>
        <?php else: ?>
            <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-primary btn-sm">
                <span>+ Create New Quotation</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($action === 'create' || $editQuote): ?>
    <!-- CREATE OR EDIT QUOTATION FORM -->
    <form method="POST" action="<?= url('admin/quotations.php') ?>" id="quotation_form">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="form_action" value="save_quotation">
        <input type="hidden" name="quote_id" value="<?= $editQuote ? $editQuote['id'] : 0 ?>">
        <input type="hidden" name="enquiry_id" value="<?= $editQuote ? $editQuote['enquiry_id'] : ($linkedEnquiry ? $linkedEnquiry['id'] : '') ?>">

        <div class="admin-split-grid">
            
            <!-- Left: Items & Specs -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                <?php if ($linkedEnquiry): ?>
                    <div class="admin-card" style="border-left: 4px solid var(--color-gold); background: var(--color-ivory);">
                        <div class="admin-card-header" style="background: transparent; border-bottom: none; padding-bottom: 0;">
                            <h3 class="admin-card-title" style="font-size: 0.95rem;">📎 Attached Client Enquiry (<?= e($linkedEnquiry['ref_number']) ?>)</h3>
                        </div>
                        <div class="admin-card-body" style="padding: 14px 20px; font-size: 0.85rem;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 140px), 1fr)); gap: 10px;">
                                <div>
                                    <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Service Requested:</span>
                                    <strong><?= e($linkedEnquiry['service_name'] ?: 'Custom Printing') ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Client Dimensions:</span>
                                    <strong><?= ($linkedEnquiry['width_inches'] && $linkedEnquiry['height_inches']) ? ($linkedEnquiry['width_inches'] . '" &times; ' . $linkedEnquiry['height_inches'] . '"') : 'Custom' ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Requested Qty:</span>
                                    <strong><?= intval($linkedEnquiry['quantity']) ?> Units</strong>
                                </div>
                                <div>
                                    <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Client Phone:</span>
                                    <strong><?= e($linkedEnquiry['customer_phone']) ?></strong>
                                </div>
                            </div>
                            <?php if (!empty($linkedEnquiry['customer_notes'])): ?>
                                <div style="margin-top: 10px; font-style: italic; color: var(--color-text-muted);">
                                    "<?= e($linkedEnquiry['customer_notes']) ?>"
                                </div>
                            <?php endif; ?>
                            <div style="margin-top: 10px;">
                                <a href="<?= url('admin/enquiries.php?enquiry_id=' . $linkedEnquiry['id']) ?>" target="_blank" class="btn btn-outline btn-xs">
                                    Open Enquiry & Artwork in New Tab &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Customer Details -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">1. Client Recipient Information</h2>
                    </div>
                    <div class="admin-card-body" style="padding: 20px;">
                        <div class="admin-form-2col">
                            <div class="form-group">
                                <label class="form-label required">Customer / Company Name</label>
                                <input type="text" name="customer_name" class="form-input" required 
                                       value="<?= e($editQuote ? $editQuote['customer_name'] : ($linkedEnquiry ? $linkedEnquiry['customer_name'] : '')) ?>" placeholder="Full Name or Business Name">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Customer Email Address</label>
                                <input type="email" name="customer_email" class="form-input" required 
                                       value="<?= e($editQuote ? $editQuote['customer_email'] : ($linkedEnquiry ? $linkedEnquiry['customer_email'] : '')) ?>" placeholder="client@example.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotation Items Repeater -->
                <div class="admin-card">
                    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="admin-card-title">2. Quotation Line Items & Specifications</h2>
                        <button type="button" id="add_quotation_item_btn" class="btn btn-secondary btn-sm">
                            + Add Line Item
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Item Description</th>
                                    <th style="min-width: 120px;">Dimensions</th>
                                    <th style="width: 90px;">Qty</th>
                                    <th style="width: 130px;">Unit Price (₹)</th>
                                    <th style="width: 120px;">Total (₹)</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="quotation_items_body">
                                <?php if (!empty($quoteItems)): ?>
                                    <?php foreach ($quoteItems as $idx => $item): ?>
                                        <tr class="quote-item-row">
                                            <td>
                                                <input type="text" name="items[<?= $idx ?>][description]" class="form-input form-input-sm" value="<?= e($item['item_description']) ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" name="items[<?= $idx ?>][dimensions]" class="form-input form-input-sm" value="<?= e($item['dimensions']) ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $idx ?>][quantity]" class="form-input form-input-sm item-qty" value="<?= $item['quantity'] ?>" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="items[<?= $idx ?>][unit_price]" class="form-input form-input-sm item-price" value="<?= $item['unit_price'] ?>" min="0" required>
                                            </td>
                                            <td class="item-total" style="font-weight: 600; vertical-align: middle;">
                                                <?= format_price($item['total_price']) ?>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <button type="button" class="btn btn-outline btn-xs remove-row-btn" style="color: var(--color-crimson);">&times;</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php elseif ($linkedEnquiry): ?>
                                    <!-- Seed initial item from linked enquiry -->
                                    <tr class="quote-item-row">
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-input form-input-sm" value="<?= e($linkedEnquiry['service_name'] ?: 'Custom Print Production') ?>" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][dimensions]" class="form-input form-input-sm" value="<?= ($linkedEnquiry['width_inches'] && $linkedEnquiry['height_inches']) ? ($linkedEnquiry['width_inches'] . 'x' . $linkedEnquiry['height_inches'] . ' in') : '' ?>" placeholder="e.g. 22x40 in">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-input form-input-sm item-qty" value="<?= max(1, intval($linkedEnquiry['quantity'])) ?>" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-input form-input-sm item-price" value="0.00" min="0" required>
                                        </td>
                                        <td class="item-total" style="font-weight: 600; vertical-align: middle;">₹0.00</td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <button type="button" class="btn btn-outline btn-xs remove-row-btn" style="color: var(--color-crimson);">&times;</button>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <!-- Blank Row -->
                                    <tr class="quote-item-row">
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-input form-input-sm" placeholder="e.g. Premium DTF Transfers - 22 inch Gang Sheet" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][dimensions]" class="form-input form-input-sm" placeholder="e.g. 22 x 40 in">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-input form-input-sm item-qty" value="1" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-input form-input-sm item-price" value="0.00" min="0" required>
                                        </td>
                                        <td class="item-total" style="font-weight: 600; vertical-align: middle;">₹0.00</td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <button type="button" class="btn btn-outline btn-xs remove-row-btn" style="color: var(--color-crimson);">&times;</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Admin Terms & Notes -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">3. Terms, Specifications & Production Remarks</h2>
                    </div>
                    <div class="admin-card-body" style="padding: 20px;">
                        <div class="form-group">
                            <label class="form-label">Notes for Client (Printed on Quote)</label>
                            <textarea name="admin_notes" class="form-input" rows="4" placeholder="Mention turnaround time, heat press recommendations (e.g. 160°C for 15s), proofing remarks, and dispatch details..."><?= e($editQuote['admin_notes'] ?? "1. High-speed Japanese dual-head industrial print quality with hot-melt adhesive backing.\n2. Standard turn-around: 24-48 business hours after proof confirmation.\n3. Safe pan-India insured courier dispatch.") ?></textarea>
                        </div>

                        <?php if ($editQuote && !empty($editQuote['customer_feedback'])): ?>
                            <div style="background: rgba(168, 62, 60, 0.08); border-left: 3px solid var(--color-crimson); padding: 12px; border-radius: 4px; margin-top: 14px;">
                                <strong style="color: var(--color-crimson); font-size: 0.85rem; display: block;">Client Revision Feedback:</strong>
                                <p style="margin: 4px 0 0; font-size: 0.85rem;"><?= e($editQuote['customer_feedback']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Right: Commercial Pricing Summary & Publish -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                
                <div class="admin-card" style="border: 2px solid var(--admin-gold, #D4AF37);">
                    <div class="admin-card-header" style="background: var(--admin-sidebar-bg, #0C1017); color: #fff; border-bottom: 1px solid var(--admin-sidebar-border, rgba(255,255,255,0.1));">
                        <h2 class="admin-card-title" style="color: #fff;">Quotation Summary</h2>
                        <?php if ($editQuote): ?>
                            <span style="font-size: 0.8rem; color: var(--color-gold); font-weight: 700;"><?= e($editQuote['quote_number']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="admin-card-body" style="padding: 20px;">
                        
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: var(--color-text-muted);">Estimated Subtotal:</span>
                                <strong id="calc_subtotal"><?= format_price($editQuote['subtotal'] ?? 0) ?></strong>
                            </div>

                            <div class="admin-form-2col" style="align-items: center;">
                                <div>
                                    <label class="form-label" style="font-size: 0.75rem; margin-bottom: 2px;">GST Rate (%)</label>
                                    <input type="number" step="0.1" name="tax_rate" id="calc_tax_rate" class="form-input form-input-sm" value="<?= $editQuote['tax_rate'] ?? 18.0 ?>" required>
                                </div>
                                <div style="text-align: right;">
                                    <span style="color: var(--color-text-muted); font-size: 0.75rem; display: block;">GST Amount:</span>
                                    <strong id="calc_tax_amount"><?= format_price($editQuote['tax_amount'] ?? 0) ?></strong>
                                </div>
                            </div>

                            <div>
                                <label class="form-label" style="font-size: 0.75rem; margin-bottom: 2px;">Packaging & Delivery Fee (₹)</label>
                                <input type="number" step="0.01" name="delivery_charge" id="calc_delivery" class="form-input form-input-sm" value="<?= $editQuote['delivery_charge'] ?? 0.0 ?>" placeholder="0.00">
                            </div>

                            <hr style="border: none; border-top: 1px solid var(--color-border); margin: 6px 0;">

                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-weight: 700; font-size: 1rem; color: var(--color-navy);">Grand Total:</span>
                                <strong id="calc_grand_total" style="font-size: 1.4rem; color: var(--color-royal);">
                                    <?= format_price($editQuote['grand_total'] ?? 0) ?>
                                </strong>
                            </div>
                        </div>

                        <hr style="border: none; border-top: 1px solid var(--color-border); margin: 20px 0;">

                        <div class="form-group">
                            <label class="form-label">Quotation Validity</label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="number" name="validity_days" class="form-input form-input-sm" value="<?= $editQuote['validity_days'] ?? 15 ?>" min="1" max="90" style="width: 80px;">
                                <span style="font-size: 0.85rem; color: var(--color-text-muted);">Days from creation</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Quotation Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" <?= ($editQuote && $editQuote['status'] === 'draft') ? 'selected' : '' ?>>Draft (Internal Only)</option>
                                <option value="sent" <?= (!$editQuote || $editQuote['status'] === 'sent') ? 'selected' : '' ?>>Sent / Published to Client</option>
                                <option value="accepted" <?= ($editQuote && $editQuote['status'] === 'accepted') ? 'selected' : '' ?>>Accepted by Client</option>
                                <option value="change_requested" <?= ($editQuote && $editQuote['status'] === 'change_requested') ? 'selected' : '' ?>>Change Requested</option>
                                <option value="rejected" <?= ($editQuote && $editQuote['status'] === 'rejected') ? 'selected' : '' ?>>Declined / Rejected</option>
                            </select>
                            <small style="color: var(--color-text-muted); display: block; margin-top: 4px; font-size: 0.75rem;">
                                Setting to "Sent" enables the client to view, accept, or request revisions directly in their portal.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-size: 0.95rem; font-weight: 700; justify-content: center; margin-top: 20px;">
                            <?= $editQuote ? '💾 Update Quotation' : '🚀 Publish & Save Quotation' ?>
                        </button>

                    </div>
                </div>

                <?php if ($editQuote): ?>
                    <div class="admin-card">
                        <div class="admin-card-body" style="padding: 16px;">
                            <form method="POST" action="<?= url('admin/quotations.php') ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="form_action" value="delete_quotation">
                                <input type="hidden" name="quote_id" value="<?= $editQuote['id'] ?>">
                                <button type="button" data-confirm="Are you sure you want to permanently delete this quotation?" data-confirm-title="Delete Quotation?" data-confirm-item="Quote #<?= e($editQuote['quote_number'] ?? $editQuote['id']) ?>" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-outline btn-sm btn-block" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
                                    Delete Quotation
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </form>

<?php else: ?>
    <!-- QUOTATIONS LIST TABLE VIEW -->
    
    <?php
    $totalQuotesCount = db_fetch_one("SELECT COUNT(*) as c FROM quotations")['c'] ?? 0;
    $totalSentCount = db_fetch_one("SELECT COUNT(*) as c FROM quotations WHERE status = 'sent'")['c'] ?? 0;
    $totalAcceptedCount = db_fetch_one("SELECT COUNT(*) as c FROM quotations WHERE status = 'accepted'")['c'] ?? 0;
    $totalValue = db_fetch_one("SELECT SUM(grand_total) as s FROM quotations WHERE status != 'rejected'")['s'] ?? 0;
    ?>

    <div class="admin-metrics-grid">
        <div class="admin-metric-card">
            <span class="admin-metric-label">Total Quotations</span>
            <div class="admin-metric-value"><?= $totalQuotesCount ?></div>
        </div>
        <div class="admin-metric-card">
            <span class="admin-metric-label">Awaiting Client Approval</span>
            <div class="admin-metric-value" style="color: var(--color-gold);"><?= $totalSentCount ?></div>
        </div>
        <div class="admin-metric-card">
            <span class="admin-metric-label">Approved & Converted</span>
            <div class="admin-metric-value" style="color: #2e7d32;"><?= $totalAcceptedCount ?></div>
        </div>
        <div class="admin-metric-card">
            <span class="admin-metric-label">Total Pipeline Value</span>
            <div class="admin-metric-value" style="color: var(--color-royal);"><?= format_price($totalValue) ?></div>
        </div>
    </div>

    <div class="admin-filter-bar">
        <div class="admin-filter-tabs">
            <a href="<?= url('admin/quotations.php') ?>" class="admin-tab-btn <?= empty($statusFilter) ? 'active' : '' ?>">All</a>
            <a href="<?= url('admin/quotations.php?status=draft') ?>" class="admin-tab-btn <?= $statusFilter === 'draft' ? 'active' : '' ?>">Drafts</a>
            <a href="<?= url('admin/quotations.php?status=sent') ?>" class="admin-tab-btn <?= $statusFilter === 'sent' ? 'active' : '' ?>">Sent / Pending</a>
            <a href="<?= url('admin/quotations.php?status=change_requested') ?>" class="admin-tab-btn <?= $statusFilter === 'change_requested' ? 'active' : '' ?>">Change Requested</a>
            <a href="<?= url('admin/quotations.php?status=accepted') ?>" class="admin-tab-btn <?= $statusFilter === 'accepted' ? 'active' : '' ?>">Accepted</a>
            <a href="<?= url('admin/quotations.php?status=rejected') ?>" class="admin-tab-btn <?= $statusFilter === 'rejected' ? 'active' : '' ?>">Rejected</a>
        </div>

        <form method="GET" action="<?= url('admin/quotations.php') ?>" class="admin-search-form">
            <?php if ($statusFilter): ?>
                <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
            <?php endif; ?>
            <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search quote #, customer, email..." class="form-input form-input-sm">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <?php if ($searchTerm || $statusFilter): ?>
                <a href="<?= url('admin/quotations.php') ?>" class="btn btn-outline btn-sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Quote Number</th>
                        <th>Client Details</th>
                        <th>Linked Enquiry</th>
                        <th>Items</th>
                        <th>Grand Total</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quotationsList)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No quotations found. Click "+ Create New Quotation" to generate your first proposal.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quotationsList as $q): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--color-navy);"><?= e($q['quote_number']) ?></strong>
                                    <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= date('d M Y', strtotime($q['created_at'])) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= e($q['customer_name']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($q['customer_email']) ?></div>
                                </td>
                                <td>
                                    <?php if ($q['enquiry_ref']): ?>
                                        <a href="<?= url('admin/enquiries.php?enquiry_id=' . $q['enquiry_id']) ?>" style="font-size: 0.85rem; color: var(--color-royal);">
                                            <?= e($q['enquiry_ref']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="font-size: 0.8rem; color: var(--color-text-muted);">Direct Proposal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size: 0.75rem;"><?= $q['items_count'] ?> item(s)</span>
                                </td>
                                <td>
                                    <strong style="color: var(--color-navy); font-size: 0.95rem;"><?= format_price($q['grand_total']) ?></strong>
                                    <div style="font-size: 0.75rem; color: var(--color-text-muted);">incl. <?= $q['tax_rate'] ?>% GST</div>
                                </td>
                                <td style="font-size: 0.8rem;">
                                    <?php if ($q['valid_until']): ?>
                                        <span style="<?= strtotime($q['valid_until']) < time() ? 'color: var(--color-crimson); font-weight: 600;' : '' ?>">
                                            <?= date('d M Y', strtotime($q['valid_until'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span><?= $q['validity_days'] ?> days</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= status_badge($q['status']) ?>
                                </td>
                                <td>
                                    <a href="<?= url('admin/quotations.php?quote_id=' . $q['id']) ?>" class="btn btn-outline btn-xs">
                                        View / Edit &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
