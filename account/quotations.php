<?php
/**
 * OrderPrintex - Customer Quotation Review & Approval Workflow
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

$quoteId = intval($_GET['id'] ?? 0);

// Handle Customer Approval / Change Request / Rejection Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('account/quotations.php' . ($quoteId ? '?id=' . $quoteId : '')));
        exit;
    }

    $targetQuoteId = intval($_POST['quotation_id'] ?? 0);
    $action = $_POST['action'];
    $feedback = trim($_POST['customer_feedback'] ?? '');

    $quote = db_fetch_one("
        SELECT * FROM quotations 
        WHERE id = :id AND (user_id = :uid OR customer_email = :email)
    ", ['id' => $targetQuoteId, 'uid' => $user['id'], 'email' => $user['email']]);

    if (!$quote) {
        set_flash('error', 'Quotation not found or unauthorized.');
        header('Location: ' . url('account/quotations.php'));
        exit;
    }

    if ($action === 'accept') {
        // 1. Mark quotation as accepted
        db_update('quotations', [
            'status' => 'accepted',
            'customer_feedback' => $feedback ?: 'Quotation approved by customer.'
        ], 'id = :id', ['id' => $targetQuoteId]);

        // 2. Convert into a working Order in `orders` table!
        $orderNum = generate_ref('OP-ORD');
        $shippingAddr = trim($_POST['shipping_address'] ?? ($user['address'] ?: 'Customer address on file'));
        $city = trim($_POST['shipping_city'] ?? ($user['city'] ?: 'New Delhi'));
        $state = trim($_POST['shipping_state'] ?? ($user['state'] ?: 'Delhi'));
        $pincode = trim($_POST['shipping_pincode'] ?? ($user['pincode'] ?: '110001'));

        $orderId = db_insert('orders', [
            'order_number'     => $orderNum,
            'quotation_id'     => $targetQuoteId,
            'enquiry_id'       => $quote['enquiry_id'],
            'user_id'          => $user['id'],
            'customer_name'    => $quote['customer_name'],
            'customer_email'   => $quote['customer_email'],
            'customer_phone'   => $user['phone'] ?? '+91 93540 28199',
            'shipping_address' => $shippingAddr,
            'city'             => $city,
            'state'            => $state,
            'pincode'          => $pincode,
            'total_amount'     => $quote['grand_total'],
            'payment_status'   => 'pending',
            'order_status'     => 'quotation_approved'
        ]);

        // 3. Insert into order_status_history
        db_insert('order_status_history', [
            'order_id'           => $orderId,
            'previous_status'    => 'quotation_sent',
            'new_status'         => 'quotation_approved',
            'remarks'            => 'Customer approved Quotation ' . $quote['quote_number'] . ' and initiated order.',
            'changed_by_user_id' => $user['id']
        ]);

        // 4. Update enquiry status to converted
        db_update('enquiries', ['status' => 'converted'], 'id = :id', ['id' => $quote['enquiry_id']]);

        log_activity($user['id'], 'approve_quotation', "Customer approved quote {$quote['quote_number']} and generated order {$orderNum}");

        set_flash('success', "Quotation {$quote['quote_number']} accepted! Your new production order {$orderNum} is now active.");
        header('Location: ' . url('account/order-details.php?id=' . $orderId));
        exit;

    } elseif ($action === 'request_change') {
        db_update('quotations', [
            'status'            => 'change_requested',
            'customer_feedback' => $feedback
        ], 'id = :id', ['id' => $targetQuoteId]);

        log_activity($user['id'], 'quote_change_requested', "Customer requested changes for quote {$quote['quote_number']}");

        set_flash('info', "Your requested revisions for {$quote['quote_number']} have been sent to our prepress team.");
        header('Location: ' . url('account/quotations.php?id=' . $targetQuoteId));
        exit;

    } elseif ($action === 'reject') {
        db_update('quotations', [
            'status'            => 'rejected',
            'customer_feedback' => $feedback ?: 'Quotation declined by customer.'
        ], 'id = :id', ['id' => $targetQuoteId]);

        log_activity($user['id'], 'quote_rejected', "Customer declined quote {$quote['quote_number']}");

        set_flash('warning', "Quotation {$quote['quote_number']} has been declined.");
        header('Location: ' . url('account/quotations.php?id=' . $targetQuoteId));
        exit;
    }
}

// Fetch single quote if $quoteId provided
$singleQuote = null;
$quoteItems = [];
if ($quoteId) {
    $singleQuote = db_fetch_one("
        SELECT q.*, e.ref_number as enquiry_ref, e.service_name, e.customer_notes
        FROM quotations q
        LEFT JOIN enquiries e ON q.enquiry_id = e.id
        WHERE q.id = :id AND (q.user_id = :uid OR q.customer_email = :email)
    ", ['id' => $quoteId, 'uid' => $user['id'], 'email' => $user['email']]);

    if ($singleQuote) {
        $quoteItems = db_fetch_all("
            SELECT * FROM quotation_items 
            WHERE quotation_id = :qid ORDER BY id ASC
        ", ['qid' => $singleQuote['id']]);
    }
}

// Fetch all quotes for listing
$allQuotes = db_fetch_all("
    SELECT q.*, e.ref_number as enquiry_ref, e.service_name 
    FROM quotations q
    LEFT JOIN enquiries e ON q.enquiry_id = e.id
    WHERE q.user_id = :uid OR q.customer_email = :email
    ORDER BY q.created_at DESC
", ['uid' => $user['id'], 'email' => $user['email']]);

$pageTitle = $singleQuote ? "Quotation {$singleQuote['quote_number']} Review | OrderPrintex" : "My Quotations | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#F8FAFC; padding:2.5rem 0; min-height:85vh;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label">Quotation Management</div>
        <h1 style="font-size:2.2rem; color:var(--navy);">
          <?= $singleQuote ? 'Review Quotation ' . e($singleQuote['quote_number']) : 'My Commercial Quotations' ?>
        </h1>
        <p style="color:var(--slate); margin:0;">
          Transparent prepress pricing, tiered bulk savings, and one-click order approval.
        </p>
      </div>

      <?php if ($singleQuote): ?>
        <a href="<?= url('account/quotations.php') ?>" class="btn btn-outline">
          &larr; Back to All Quotations
        </a>
      <?php else: ?>
        <a href="<?= url('get-quote.php') ?>" class="btn btn-primary">
          <span>+ Request New Quotation</span>
        </a>
      <?php endif; ?>
    </div>

    <?= render_flash() ?>

    <div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-light); margin-bottom:2rem; overflow-x:auto;">
      <a href="<?= url('account/dashboard.php') ?>" class="gallery-filter-btn">Overview</a>
      <a href="<?= url('account/enquiries.php') ?>" class="gallery-filter-btn">My Enquiries</a>
      <a href="<?= url('account/quotations.php') ?>" class="gallery-filter-btn active">My Quotations (<?= count($allQuotes) ?>)</a>
      <a href="<?= url('account/orders.php') ?>" class="gallery-filter-btn">My Orders</a>
      <a href="<?= url('account/profile.php') ?>" class="gallery-filter-btn">Settings</a>
    </div>

    <?php if ($singleQuote): ?>
      <!-- Detailed Single Quotation Review View -->
      <div style="display:grid; grid-template-columns:1.8fr 1.2fr; gap:2rem; align-items:flex-start;">
        <!-- Left: Itemized Invoice Table -->
        <div class="panel-card">
          <div class="panel-header">
            <div>
              <span class="ref-cell" style="font-size:1.15rem;"><?= e($singleQuote['quote_number']) ?></span>
              <span style="margin-left:0.75rem;"><?= get_status_badge($singleQuote['status']) ?></span>
            </div>
            <div style="font-size:0.85rem; color:var(--slate);">
              Linked Enquiry: <strong><?= e($singleQuote['enquiry_ref']) ?></strong>
            </div>
          </div>

          <div style="padding:1.5rem; border-bottom:1px solid var(--border-light); display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; font-size:0.88rem;">
            <div>
              <span class="text-muted">Issued To:</span><br>
              <strong><?= e($singleQuote['customer_name']) ?></strong><br>
              <?= e($singleQuote['customer_email']) ?>
            </div>
            <div style="text-align:right;">
              <span class="text-muted">Date Generated:</span><br>
              <strong><?= format_date($singleQuote['created_at'], 'd M Y') ?></strong><br>
              <span style="color:var(--crimson);">
                Valid until: <?= $singleQuote['valid_until'] ? format_date($singleQuote['valid_until'], 'd M Y') : '15 days from issue' ?>
              </span>
            </div>
          </div>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Description</th>
                  <th>Dimensions</th>
                  <th style="text-align:center;">Qty</th>
                  <th style="text-align:right;">Unit Price</th>
                  <th style="text-align:right;">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($quoteItems)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; padding:2rem; color:var(--slate);">
                      Quotation items are being finalized by prepress.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($quoteItems as $it): ?>
                    <tr>
                      <td><strong><?= e($it['item_description']) ?></strong></td>
                      <td><?= e($it['dimensions'] ?: '—') ?></td>
                      <td style="text-align:center;"><?= (int)$it['quantity'] ?></td>
                      <td style="text-align:right; font-family:var(--font-mono);"><?= format_currency($it['unit_price']) ?></td>
                      <td style="text-align:right; font-family:var(--font-mono); font-weight:700; color:var(--navy);">
                        <?= format_currency($it['total_price']) ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Totals Calculation Summary -->
          <div style="padding:1.5rem; background-color:var(--ivory-light); border-top:1px solid var(--border-light); display:flex; flex-direction:column; align-items:flex-end; gap:0.4rem; font-size:0.92rem;">
            <div>Subtotal: <strong style="font-family:var(--font-mono);"><?= format_currency($singleQuote['subtotal']) ?></strong></div>
            <div>GST (<?= e($singleQuote['tax_rate']) ?>%): <strong style="font-family:var(--font-mono);"><?= format_currency($singleQuote['tax_amount']) ?></strong></div>
            <div>Delivery &amp; Packaging: <strong style="font-family:var(--font-mono);"><?= format_currency($singleQuote['delivery_charge']) ?></strong></div>
            <div style="font-size:1.25rem; font-weight:700; color:var(--navy); border-top:2px solid var(--gold); padding-top:0.5rem; margin-top:0.35rem;">
              Grand Total: <span style="font-family:var(--font-mono); color:var(--navy);"><?= format_currency($singleQuote['grand_total']) ?></span>
            </div>
          </div>

          <?php if (!empty($singleQuote['admin_notes'])): ?>
            <div style="padding:1.25rem 1.5rem; background-color:#EFF6FF; border-top:1px solid #BFDBFE; font-size:0.88rem; color:#1E40AF;">
              <strong>Studio Notes:</strong> <?= nl2br(e($singleQuote['admin_notes'])) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Right: Action Form & Customer Decisions -->
        <div class="upload-card">
          <h3 style="margin-bottom:0.75rem;">Quotation Action</h3>
          <p style="font-size:0.88rem; color:var(--slate); margin-bottom:1.5rem;">
            Current Status: <?= get_status_badge($singleQuote['status']) ?>
          </p>

          <?php if ($singleQuote['status'] === 'accepted'): ?>
            <div class="alert alert-success">
              <div class="alert-content">
                &check; <strong>Quotation Accepted!</strong> This quote has been converted into a live production order.
              </div>
            </div>
            <a href="<?= url('account/orders.php') ?>" class="btn btn-navy" style="width:100%;">
              <span>View Production Orders</span> &rarr;
            </a>

          <?php elseif ($singleQuote['status'] === 'rejected'): ?>
            <div class="alert alert-error">
              <div class="alert-content">
                This quotation was declined. You can submit new artwork anytime.
              </div>
            </div>
            <a href="<?= url('get-quote.php') ?>" class="btn btn-outline" style="width:100%;">
              <span>Submit New Artwork</span>
            </a>

          <?php else: ?>
            <!-- Form to Accept and Convert into Order -->
            <form action="<?= url('account/quotations.php') ?>" method="POST" style="margin-bottom:1.75rem;">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="accept">

              <div style="border-bottom:1px solid var(--border-light); padding-bottom:1rem; margin-bottom:1rem;">
                <h4 style="font-size:0.95rem; margin-bottom:0.75rem;">Delivery Destination Details:</h4>
                <div class="form-group" style="margin-bottom:0.75rem;">
                  <label class="form-label" style="font-size:0.8rem;">Delivery Address <span class="required">*</span></label>
                  <input type="text" name="shipping_address" class="form-control" value="<?= e($user['address'] ?? '') ?>" placeholder="Address" required>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                  <div class="form-group">
                    <label class="form-label" style="font-size:0.8rem;">City <span class="required">*</span></label>
                    <input type="text" name="shipping_city" class="form-control" value="<?= e($user['city'] ?? '') ?>" placeholder="City" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label" style="font-size:0.8rem;">Pincode <span class="required">*</span></label>
                    <input type="text" name="shipping_pincode" class="form-control" value="<?= e($user['pincode'] ?? '') ?>" placeholder="Pincode" required>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary btn-lg" style="width:100%;" onclick="return confirm('Accept this quotation and proceed to production order?');">
                <span>&check; Approve Quote &amp; Start Production</span>
              </button>
            </form>

            <!-- Revisions / Change Request Form -->
            <form action="<?= url('account/quotations.php') ?>" method="POST" style="margin-bottom:1rem;">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="request_change">

              <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="font-size:0.8rem;">Request Price / Quantity Adjustments</label>
                <textarea name="customer_feedback" class="form-control" placeholder="Specify changes (e.g. increase gang sheet length to 60 inches, change garment sizes)..." style="min-height:75px;"></textarea>
              </div>

              <button type="submit" class="btn btn-sm btn-outline" style="width:100%;">
                Request Revision
              </button>
            </form>

            <!-- Reject Button -->
            <form action="<?= url('account/quotations.php') ?>" method="POST">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="reject">
              <button type="submit" class="btn btn-sm btn-outline text-crimson" style="width:100%;" onclick="return confirm('Decline this quotation?');">
                Decline Quotation
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>

    <?php else: ?>
      <!-- All Quotations Table -->
      <div class="panel-card">
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Quote Number</th>
                <th>Enquiry Reference</th>
                <th>Date Generated</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Valid Until</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($allQuotes)): ?>
                <tr>
                  <td colspan="7" style="text-align:center; padding:3rem; color:var(--slate);">
                    No quotations generated yet. 
                    <a href="<?= url('get-quote.php') ?>" class="text-gold" style="font-weight:600;">Upload artwork &amp; request a quote &rarr;</a>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($allQuotes as $q): ?>
                  <tr>
                    <td class="ref-cell"><?= e($q['quote_number']) ?></td>
                    <td><?= e($q['enquiry_ref']) ?> &bull; <?= e($q['service_name']) ?></td>
                    <td><?= format_date($q['created_at'], 'd M Y') ?></td>
                    <td style="font-weight:700; color:var(--navy); font-family:var(--font-mono);">
                      <?= format_currency($q['grand_total']) ?>
                    </td>
                    <td><?= get_status_badge($q['status']) ?></td>
                    <td><?= $q['valid_until'] ? format_date($q['valid_until'], 'd M Y') : '15 days' ?></td>
                    <td>
                      <a href="<?= url('account/quotations.php?id=' . $q['id']) ?>" class="btn btn-sm btn-primary">
                        Review &amp; Approve
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
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
