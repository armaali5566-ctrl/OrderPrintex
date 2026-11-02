<?php
/**
 * OrderPrintex - Admin Enquiry & Artwork Management
 */

$adminTitle = "Manage Customer Enquiries & Artwork";
require_once __DIR__ . '/header.php';

// Handle Actions: Status Update & Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'update_status') {
        $enquiryId = intval($_POST['enquiry_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? 'received');
        
        $validStatuses = ['received', 'under_review', 'quoted', 'converted', 'rejected', 'closed'];
        if (in_array($newStatus, $validStatuses, true)) {
            db_update('enquiries', ['status' => $newStatus], 'id = :id', ['id' => $enquiryId]);
            log_activity($adminUser['id'], 'update_enquiry', "Updated enquiry ID #{$enquiryId} status to {$newStatus}");
            set_flash('success', "Enquiry status updated to " . ucfirst(str_replace('_', ' ', $newStatus)));
        }
        header('Location: ' . url('admin/enquiries.php' . ($enquiryId ? '?enquiry_id=' . $enquiryId : '')));
        exit;
    }

    if ($action === 'delete_enquiry') {
        $enquiryId = intval($_POST['enquiry_id'] ?? 0);
        $enq = db_fetch_one("SELECT * FROM enquiries WHERE id = :id", ['id' => $enquiryId]);
        if ($enq) {
            // Check associated files
            $files = db_fetch_all("SELECT * FROM enquiry_files WHERE enquiry_id = :id", ['id' => $enquiryId]);
            foreach ($files as $f) {
                $filePath = UPLOADS_PATH . '/' . $f['stored_name'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            db_delete('enquiry_files', 'enquiry_id = :id', ['id' => $enquiryId]);
            db_delete('enquiries', 'id = :id', ['id' => $enquiryId]);
            log_activity($adminUser['id'], 'delete_enquiry', "Deleted enquiry {$enq['ref_number']}");
            set_flash('success', "Enquiry {$enq['ref_number']} was deleted successfully.");
        }
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }
}

// Filters & Search
$statusFilter = trim($_GET['status'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');
$selectedId = intval($_GET['enquiry_id'] ?? 0);

$sql = "SELECT e.*, u.name as user_account_name,
               (SELECT COUNT(*) FROM enquiry_files ef WHERE ef.enquiry_id = e.id) as files_count,
               q.quote_number, q.id as quote_id
        FROM enquiries e
        LEFT JOIN users u ON e.user_id = u.id
        LEFT JOIN quotations q ON q.enquiry_id = e.id
        WHERE 1=1";
$params = [];

if ($statusFilter) {
    $sql .= " AND e.status = :status";
    $params['status'] = $statusFilter;
}

if ($searchTerm) {
    $sql .= " AND (e.ref_number LIKE :s OR e.customer_name LIKE :s OR e.customer_email LIKE :s OR e.customer_phone LIKE :s OR e.company_name LIKE :s OR e.service_name LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY e.created_at DESC";
$enquiries = db_fetch_all($sql, $params);

// Selected Enquiry Details
$activeEnquiry = null;
$enquiryFiles = [];
if ($selectedId) {
    $activeEnquiry = db_fetch_one("
        SELECT e.*, u.name as registered_user, u.email as user_email,
               q.id as existing_quote_id, q.quote_number, q.status as quote_status, q.grand_total as quote_amount
        FROM enquiries e
        LEFT JOIN users u ON e.user_id = u.id
        LEFT JOIN quotations q ON q.enquiry_id = e.id
        WHERE e.id = :id
    ", ['id' => $selectedId]);

    if ($activeEnquiry) {
        $enquiryFiles = db_fetch_all("SELECT * FROM enquiry_files WHERE enquiry_id = :eid ORDER BY id ASC", ['eid' => $selectedId]);
    }
}
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Customer Enquiries</h1>
        <p class="admin-page-subtitle">Inspect client artwork uploads, review print requirements, and convert requests into detailed quotations.</p>
    </div>
    <div class="admin-topbar-actions">
        <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-primary btn-sm">
            <span>+ Create Custom Quotation</span>
        </a>
    </div>
</div>

<!-- Quick Stats / Tabs -->
<?php
$countAll = db_fetch_one("SELECT COUNT(*) as c FROM enquiries")['c'] ?? 0;
$countReceived = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'received'")['c'] ?? 0;
$countReview = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'under_review'")['c'] ?? 0;
$countQuoted = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'quoted'")['c'] ?? 0;
$countConverted = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'converted'")['c'] ?? 0;
?>

<div class="admin-filter-bar">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="<?= url('admin/enquiries.php') ?>" class="admin-tab-btn <?= empty($statusFilter) ? 'active' : '' ?>">
            All (<?= $countAll ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=received') ?>" class="admin-tab-btn <?= $statusFilter === 'received' ? 'active' : '' ?>">
            New Received (<?= $countReceived ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=under_review') ?>" class="admin-tab-btn <?= $statusFilter === 'under_review' ? 'active' : '' ?>">
            Under Review (<?= $countReview ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=quoted') ?>" class="admin-tab-btn <?= $statusFilter === 'quoted' ? 'active' : '' ?>">
            Quoted (<?= $countQuoted ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=converted') ?>" class="admin-tab-btn <?= $statusFilter === 'converted' ? 'active' : '' ?>">
            Converted to Order (<?= $countConverted ?>)
        </a>
    </div>

    <form method="GET" action="<?= url('admin/enquiries.php') ?>" class="admin-search-form" style="display: flex; gap: 8px;">
        <?php if ($statusFilter): ?>
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
        <?php endif; ?>
        <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search ref, customer, email, phone..." class="form-input form-input-sm" style="width: 260px;">
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        <?php if ($searchTerm || $statusFilter): ?>
            <a href="<?= url('admin/enquiries.php') ?>" class="btn btn-outline btn-sm">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Main Enquiries Split View -->
<div class="admin-grid-layout" style="display: grid; grid-template-columns: <?= $activeEnquiry ? '1.2fr 1fr' : '1fr' ?>; gap: 24px; align-items: start;">
    
    <!-- Enquiries Table Column -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Enquiry Submissions (<?= count($enquiries) ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Service / Specs</th>
                        <th>Qty</th>
                        <th>Artwork</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enquiries)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No customer enquiries found matching the criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enquiries as $enq): ?>
                            <tr class="<?= ($activeEnquiry && $activeEnquiry['id'] === $enq['id']) ? 'table-row-selected' : '' ?>">
                                <td>
                                    <strong style="color: var(--color-navy);"><?= e($enq['ref_number']) ?></strong>
                                    <?php if ($enq['quote_number']): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-royal);">
                                            <a href="<?= url('admin/quotations.php?quote_id=' . $enq['quote_id']) ?>">Quote: <?= e($enq['quote_number']) ?></a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?= e($enq['customer_name']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($enq['customer_email']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($enq['customer_phone']) ?></div>
                                    <?php if (!empty($enq['company_name'])): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-gold); font-weight: 500;"><?= e($enq['company_name']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--color-navy);"><?= e($enq['service_name'] ?: 'Custom Print') ?></span>
                                    <?php if ($enq['width_inches'] && $enq['height_inches']): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= $enq['width_inches'] ?>" &times; <?= $enq['height_inches'] ?>"</div>
                                    <?php endif; ?>
                                    <?php if ($enq['material_options']): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= e($enq['material_options']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= intval($enq['quantity']) ?></strong></td>
                                <td>
                                    <?php if ($enq['files_count'] > 0): ?>
                                        <span class="badge badge-info" style="font-size: 0.75rem;">
                                            📎 <?= $enq['files_count'] ?> File<?= $enq['files_count'] > 1 ? 's' : '' ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= status_badge($enq['status']) ?>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--color-text-muted); white-space: nowrap;">
                                    <?= date('d M Y', strtotime($enq['created_at'])) ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="<?= url('admin/enquiries.php?enquiry_id=' . $enq['id'] . ($statusFilter ? '&status=' . urlencode($statusFilter) : '')) ?>" class="btn btn-outline btn-xs">
                                            Inspect
                                        </a>
                                        <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $enq['id']) ?>" class="btn btn-primary btn-xs" title="Generate Quotation">
                                            Quote
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Enquiry Detail Inspector Column -->
    <?php if ($activeEnquiry): ?>
        <div class="admin-card" style="border: 2px solid var(--color-gold);">
            <div class="admin-card-header" style="background: var(--color-ivory); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="admin-card-title" style="margin-bottom: 2px;">Enquiry <?= e($activeEnquiry['ref_number']) ?></h2>
                    <span style="font-size: 0.8rem; color: var(--color-text-muted);">Received on <?= date('d M Y, h:i A', strtotime($activeEnquiry['created_at'])) ?></span>
                </div>
                <a href="<?= url('admin/enquiries.php' . ($statusFilter ? '?status=' . urlencode($statusFilter) : '')) ?>" style="color: var(--color-text-muted); font-size: 1.2rem; text-decoration: none;">&times;</a>
            </div>

            <div class="admin-card-body" style="padding: 20px;">
                <!-- Status Control Box -->
                <form method="POST" action="<?= url('admin/enquiries.php') ?>" style="margin-bottom: 20px; background: #fafafa; padding: 14px; border-radius: 6px; border: 1px solid var(--color-border);">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="enquiry_id" value="<?= $activeEnquiry['id'] ?>">

                    <div style="display: flex; gap: 10px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--color-navy); display: block; margin-bottom: 4px;">Update Pipeline Status:</label>
                            <select name="status" class="form-select" style="min-width: 170px; padding: 6px 10px;">
                                <option value="received" <?= $activeEnquiry['status'] === 'received' ? 'selected' : '' ?>>Received</option>
                                <option value="under_review" <?= $activeEnquiry['status'] === 'under_review' ? 'selected' : '' ?>>Under Review</option>
                                <option value="quoted" <?= $activeEnquiry['status'] === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                                <option value="converted" <?= $activeEnquiry['status'] === 'converted' ? 'selected' : '' ?>>Converted to Order</option>
                                <option value="rejected" <?= $activeEnquiry['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="closed" <?= $activeEnquiry['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm" style="margin-top: 18px;">Update Status</button>
                    </div>
                </form>

                <!-- Customer Info -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 12px;">Customer Details</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Name</span>
                            <strong><?= e($activeEnquiry['customer_name']) ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Company / Brand</span>
                            <strong><?= e($activeEnquiry['company_name'] ?: 'Individual / N/A') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Email</span>
                            <a href="mailto:<?= e($activeEnquiry['customer_email']) ?>" style="color: var(--color-royal); word-break: break-all;"><?= e($activeEnquiry['customer_email']) ?></a>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Phone / WhatsApp</span>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $activeEnquiry['customer_phone']) ?>" target="_blank" style="color: #25D366; font-weight: 600;">
                                💬 <?= e($activeEnquiry['customer_phone']) ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Print Specifications -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 12px;">Print Specifications</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Selected Service</span>
                            <strong style="color: var(--color-navy);"><?= e($activeEnquiry['service_name'] ?: 'Custom Print') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Requested Quantity</span>
                            <strong style="font-size: 1.1rem; color: var(--color-gold);"><?= intval($activeEnquiry['quantity']) ?> Units / Meters</strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Dimensions</span>
                            <strong><?= ($activeEnquiry['width_inches'] && $activeEnquiry['height_inches']) ? ($activeEnquiry['width_inches'] . '" &times; ' . $activeEnquiry['height_inches'] . '"') : 'Custom / Not Specified' ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Material / Variant</span>
                            <strong><?= e($activeEnquiry['material_options'] ?: 'Standard Production') ?></strong>
                        </div>
                    </div>

                    <?php if (!empty($activeEnquiry['customer_notes'])): ?>
                        <div style="margin-top: 14px; background: var(--color-ivory); padding: 12px; border-radius: 4px; font-size: 0.85rem; border-left: 3px solid var(--color-gold);">
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-navy); display: block; margin-bottom: 4px;">Customer Notes / Instructions:</span>
                            <p style="margin: 0; color: var(--color-text); line-height: 1.5; white-space: pre-line;"><?= e($activeEnquiry['customer_notes']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Uploaded Artwork Files -->
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 12px;">
                        Customer Artwork (<?= count($enquiryFiles) ?>)
                    </h3>
                    
                    <?php if (empty($enquiryFiles)): ?>
                        <p style="font-size: 0.85rem; color: var(--color-text-muted); font-style: italic;">No artwork was attached to this request (Text-only quote enquiry).</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php foreach ($enquiryFiles as $file): ?>
                                <?php 
                                    $isImage = in_array(strtolower($file['file_extension']), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                    $fileUrl = upload_url($file['stored_name']);
                                ?>
                                <div style="display: flex; align-items: center; gap: 14px; padding: 12px; border: 1px solid var(--color-border); border-radius: 6px; background: #fff;">
                                    <?php if ($isImage): ?>
                                        <a href="<?= $fileUrl ?>" target="_blank" style="display: block; width: 64px; height: 64px; flex-shrink: 0; border: 1px solid var(--color-border); border-radius: 4px; overflow: hidden; background: #eee;">
                                            <img src="<?= $fileUrl ?>" alt="<?= e($file['original_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>
                                    <?php else: ?>
                                        <div style="width: 64px; height: 64px; flex-shrink: 0; background: var(--color-navy); color: #fff; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 4px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                                            <span>FILE</span>
                                            <span style="color: var(--color-gold); font-size: 0.65rem;"><?= e($file['file_extension']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div style="flex-grow: 1; min-width: 0;">
                                        <div style="font-weight: 600; font-size: 0.85rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?= e($file['original_name']) ?>">
                                            <?= e($file['original_name']) ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                            <?= round($file['file_size'] / 1024, 1) ?> KB &bull; <?= strtoupper($file['file_extension']) ?>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="<?= $fileUrl ?>" download="<?= e($file['original_name']) ?>" target="_blank" class="btn btn-secondary btn-xs">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quotation Status or Generator CTA -->
                <div style="border-top: 1px solid var(--color-border); padding-top: 18px; display: flex; flex-direction: column; gap: 10px;">
                    <?php if ($activeEnquiry['existing_quote_id']): ?>
                        <div style="background: rgba(49, 89, 166, 0.08); border: 1px solid var(--color-royal); padding: 14px; border-radius: 6px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--color-royal);">Linked Quotation</span>
                                    <div style="font-weight: 700; font-size: 1.1rem; color: var(--color-navy);"><?= e($activeEnquiry['quote_number']) ?></div>
                                    <div style="font-size: 0.85rem; color: var(--color-text-muted);">Amount: <?= format_price($activeEnquiry['quote_amount']) ?> &bull; Status: <?= status_badge($activeEnquiry['quote_status']) ?></div>
                                </div>
                                <a href="<?= url('admin/quotations.php?quote_id=' . $activeEnquiry['existing_quote_id']) ?>" class="btn btn-primary btn-sm">
                                    View / Edit Quote &rarr;
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $activeEnquiry['id']) ?>" class="btn btn-primary btn-block" style="text-align: center; justify-content: center; font-weight: 600;">
                            ✨ Generate Formal Quotation for this Enquiry
                        </a>
                    <?php endif; ?>

                    <form method="POST" action="<?= url('admin/enquiries.php') ?>" onsubmit="return confirm('Are you sure you want to delete this enquiry and its uploaded artwork?');" style="margin-top: 10px; text-align: right;">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="delete_enquiry">
                        <input type="hidden" name="enquiry_id" value="<?= $activeEnquiry['id'] ?>">
                        <button type="submit" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.3);">
                            Delete Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
