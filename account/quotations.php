<?php
/**
 * OrderPrintex - Customer Quotation Review & Approval Workflow
 * Pure Enquiry & Quotation Model (No direct checkout or order table mutations)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();
$uid = $user['id'];
$uemail = $user['email'];

$quoteId = intval($_GET['id'] ?? 0);

// Handle Customer Approval / Revision / Decline Actions
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
    ", ['id' => $targetQuoteId, 'uid' => $uid, 'email' => $uemail]);

    if (!$quote) {
        set_flash('error', 'Quotation not found or unauthorized.');
        header('Location: ' . url('account/quotations.php'));
        exit;
    }

    if ($action === 'accept') {
        // Mark quotation as accepted
        db_update('quotations', [
            'status' => 'accepted',
            'customer_feedback' => $feedback ?: 'Quotation accepted by customer.'
        ], 'id = :id', ['id' => $targetQuoteId]);

        // Update linked enquiry status to converted
        if (!empty($quote['enquiry_id'])) {
            db_update('enquiries', ['status' => 'converted'], 'id = :id', ['id' => $quote['enquiry_id']]);
        }

        log_activity($user['id'], 'approve_quotation', "Customer approved quotation {$quote['quote_number']}");

        set_flash('success', "Quotation {$quote['quote_number']} accepted! Thank you for confirming. Our commercial team will contact you shortly to finalize scheduling and details.");
        header('Location: ' . url('account/quotations.php?id=' . $targetQuoteId));
        exit;

    } elseif ($action === 'request_change') {
        db_update('quotations', [
            'status'            => 'change_requested',
            'customer_feedback' => $feedback
        ], 'id = :id', ['id' => $targetQuoteId]);

        log_activity($user['id'], 'quote_change_requested', "Customer requested changes for quote {$quote['quote_number']}");

        set_flash('info', "Your requested revisions for {$quote['quote_number']} have been sent to our prepress team. We will review and update your quote shortly.");
        header('Location: ' . url('account/quotations.php?id=' . $targetQuoteId));
        exit;

    } elseif ($action === 'reject') {
        db_update('quotations', [
            'status'            => 'rejected',
            'customer_feedback' => $feedback ?: 'Declined by customer.'
        ], 'id = :id', ['id' => $targetQuoteId]);

        log_activity($user['id'], 'reject_quotation', "Customer declined quotation {$quote['quote_number']}");

        set_flash('warning', "Quotation {$quote['quote_number']} has been declined. You can submit new specifications anytime.");
        header('Location: ' . url('account/quotations.php?id=' . $targetQuoteId));
        exit;
    }
}

// Fetch single quote if requested
$singleQuote = null;
$quoteItems = [];
if ($quoteId > 0) {
    $singleQuote = db_fetch_one("
        SELECT q.*, e.ref_number as enquiry_ref, e.service_name 
        FROM quotations q
        LEFT JOIN enquiries e ON e.id = q.enquiry_id
        WHERE q.id = :id AND (q.user_id = :uid OR q.customer_email = :email)
    ", ['id' => $quoteId, 'uid' => $uid, 'email' => $uemail]);

    if ($singleQuote) {
        $quoteItems = db_fetch_all("
            SELECT * FROM quotation_items 
            WHERE quotation_id = :qid 
            ORDER BY id ASC
        ", ['qid' => $singleQuote['id']]);
    }
}

// Fetch all quotes for listing
$allQuotes = db_fetch_all("
    SELECT q.*, e.ref_number as enquiry_ref, e.service_name 
    FROM quotations q
    LEFT JOIN enquiries e ON e.id = q.enquiry_id
    WHERE q.user_id = :uid OR q.customer_email = :email
    ORDER BY q.created_at DESC
", ['uid' => $uid, 'email' => $uemail]);

$pendingQuotesCount = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE (user_id = :uid OR customer_email = :email) AND status = 'sent'", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);

$pageTitle = $singleQuote ? "Review Quotation {$singleQuote['quote_number']} | OrderPrintex" : "My Quotations | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0 4rem; min-height:85vh; color:#F5F1E8;">
  <div class="container">
    <!-- Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label" style="color:#D4AF37; margin-bottom:0.25rem;">Quotation Management</div>
        <h1 style="font-size:clamp(1.75rem, 3vw, 2.25rem); color:#F5F1E8; margin:0 0 0.35rem; font-weight:800; font-family:var(--font-serif);">
          <?= $singleQuote ? 'Quotation ' . e($singleQuote['quote_number']) : 'My Commercial Quotations' ?>
        </h1>
        <p style="color:#B8B1A3; margin:0; font-size:0.95rem;">
          Itemized prepress pricing, bulk tier discounts, and commercial confirmation.
        </p>
      </div>

      <?php if ($singleQuote): ?>
        <a href="<?= account_url('quotations') ?>" class="btn btn-outline" style="padding:0.65rem 1.25rem;">
          &larr; Back to All Quotations
        </a>
      <?php else: ?>
        <a href="<?= url('get-quote') ?>" class="btn btn-primary" style="padding:0.65rem 1.4rem;">
          <span>+ Request New Quotation</span>
        </a>
      <?php endif; ?>
    </div>

    <?= render_flash() ?>

    <!-- Sub-navigation Tabs -->
    <div style="display:flex; gap:0.5rem; border-bottom:1px solid #2A2415; margin-bottom:2rem; overflow-x:auto; padding-bottom:2px;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn" style="text-decoration:none;">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn" style="text-decoration:none;">My Enquiries</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn active" style="text-decoration:none;">
        My Quotations (<?= count($allQuotes) ?>)
      </a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn" style="text-decoration:none;">Settings</a>
    </div>

    <?php if ($singleQuote): ?>
      <!-- Single Quotation Review View -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2rem; align-items:flex-start;">
        
        <!-- Left: Itemized Invoice Card -->
        <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; overflow:hidden;">
          <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #2A2415; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
            <div>
              <span style="font-family:var(--font-mono); font-size:1.2rem; font-weight:800; color:#FFD76A;">
                <?= e($singleQuote['quote_number']) ?>
              </span>
              <span style="margin-left:0.75rem;"><?= get_status_badge($singleQuote['status']) ?></span>
            </div>
            <div style="font-size:0.85rem; color:#B8B1A3;">
              Linked Enquiry: <strong style="color:#F5F1E8;"><?= e($singleQuote['enquiry_ref'] ?? 'Direct') ?></strong>
            </div>
          </div>

          <div style="padding:1.5rem; border-bottom:1px solid #2A2415; display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; font-size:0.88rem;">
            <div>
              <span style="color:#8A8275; text-transform:uppercase; font-size:0.72rem; letter-spacing:0.05em; font-weight:700;">Issued To:</span><br>
              <strong style="color:#F5F1E8; font-size:0.95rem;"><?= e($singleQuote['customer_name']) ?></strong><br>
              <span style="color:#B8B1A3;"><?= e($singleQuote['customer_email']) ?></span>
            </div>
            <div style="text-align:right;">
              <span style="color:#8A8275; text-transform:uppercase; font-size:0.72rem; letter-spacing:0.05em; font-weight:700;">Issued Date:</span><br>
              <strong style="color:#F5F1E8;"><?= format_date($singleQuote['created_at'], 'd M Y') ?></strong><br>
              <span style="color:#D4AF37; font-size:0.82rem;">
                Valid until: <?= $singleQuote['valid_until'] ? format_date($singleQuote['valid_until'], 'd M Y') : '15 days from issue' ?>
              </span>
            </div>
          </div>

          <!-- Items Table -->
          <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9rem; text-align:left;">
              <thead>
                <tr style="border-bottom:1px solid #2A2415; background:rgba(212,175,55,0.03);">
                  <th style="padding:0.75rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3;">Description</th>
                  <th style="padding:0.75rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3;">Dimensions</th>
                  <th style="padding:0.75rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; text-align:center;">Qty</th>
                  <th style="padding:0.75rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; text-align:right;">Unit Price</th>
                  <th style="padding:0.75rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; text-align:right;">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($quoteItems)): ?>
                  <tr>
                    <td colspan="5" style="padding:2rem; text-align:center; color:#B8B1A3;">
                      Quotation items are being finalized by prepress engineering.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($quoteItems as $it): ?>
                    <tr style="border-bottom:1px solid #1A1712;">
                      <td style="padding:0.85rem 1.25rem; color:#F5F1E8; font-weight:600;">
                        <?= e($it['item_description']) ?>
                      </td>
                      <td style="padding:0.85rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                        <?= e($it['dimensions'] ?: '—') ?>
                      </td>
                      <td style="padding:0.85rem 1.25rem; text-align:center; color:#F5F1E8; font-weight:600;">
                        <?= (int)$it['quantity'] ?>
                      </td>
                      <td style="padding:0.85rem 1.25rem; text-align:right; font-family:var(--font-mono); color:#B8B1A3;">
                        <?= format_currency($it['unit_price']) ?>
                      </td>
                      <td style="padding:0.85rem 1.25rem; text-align:right; font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                        <?= format_currency($it['total_price']) ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Totals Summary -->
          <div style="padding:1.5rem; background:rgba(212,175,55,0.02); border-top:1px solid #2A2415; display:flex; flex-direction:column; align-items:flex-end; gap:0.4rem; font-size:0.92rem;">
            <div style="color:#B8B1A3;">Subtotal: <strong style="font-family:var(--font-mono); color:#F5F1E8; margin-left:0.5rem;"><?= format_currency($singleQuote['subtotal']) ?></strong></div>
            <div style="color:#B8B1A3;">GST (<?= e($singleQuote['tax_rate']) ?>%): <strong style="font-family:var(--font-mono); color:#F5F1E8; margin-left:0.5rem;"><?= format_currency($singleQuote['tax_amount']) ?></strong></div>
            <div style="color:#B8B1A3;">Delivery &amp; Packaging: <strong style="font-family:var(--font-mono); color:#F5F1E8; margin-left:0.5rem;"><?= format_currency($singleQuote['delivery_charge']) ?></strong></div>
            <div style="font-size:1.35rem; font-weight:800; color:#FFD76A; border-top:1px solid #40351E; padding-top:0.65rem; margin-top:0.35rem; width:100%; text-align:right;">
              <span style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; margin-right:0.75rem; font-weight:600;">Grand Total:</span>
              <span style="font-family:var(--font-mono);"><?= format_currency($singleQuote['grand_total']) ?></span>
            </div>
          </div>

          <?php if (!empty($singleQuote['admin_notes'])): ?>
            <div style="padding:1.25rem 1.5rem; background:#161616; border-top:1px solid #2A2415; font-size:0.88rem; color:#D4AF37;">
              <strong style="color:#F5F1E8; display:block; margin-bottom:0.25rem;">Prepress Technical Notes:</strong>
              <div style="color:#B8B1A3; line-height:1.6;"><?= nl2br(e($singleQuote['admin_notes'])) ?></div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Right: Action Form & Status Decisions -->
        <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:1.5rem;">
          <h3 style="font-size:1.2rem; color:#F5F1E8; margin:0 0 0.5rem; font-weight:700;">Quotation Action</h3>
          <p style="font-size:0.88rem; color:#B8B1A3; margin:0 0 1.5rem;">
            Current Status: <?= get_status_badge($singleQuote['status']) ?>
          </p>

          <?php if ($singleQuote['status'] === 'accepted'): ?>
            <div style="background:rgba(52,211,153,0.1); border:1px solid rgba(52,211,153,0.3); border-radius:6px; padding:1.25rem; margin-bottom:1.5rem;">
              <div style="color:#34D399; font-weight:700; margin-bottom:0.35rem; display:flex; align-items:center; gap:0.4rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Quotation Accepted!
              </div>
              <p style="color:#B8B1A3; font-size:0.85rem; margin:0; line-height:1.5;">
                Thank you for accepting this quote. Our commercial team has received your confirmation and will contact you directly to schedule prepress proofs and dispatch arrangements.
              </p>
            </div>
            <a href="https://wa.me/919354028199?text=Hi%2C%20I%20have%20accepted%20quotation%20<?= urlencode($singleQuote['quote_number']) ?>" target="_blank" rel="noopener" class="btn btn-primary" style="width:100%; text-align:center; display:block;">
              Contact Commercial Team on WhatsApp &rarr;
            </a>

          <?php elseif ($singleQuote['status'] === 'rejected'): ?>
            <div style="background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.3); border-radius:6px; padding:1.25rem; margin-bottom:1.5rem;">
              <div style="color:#F87171; font-weight:700; margin-bottom:0.35rem;">Quotation Declined</div>
              <p style="color:#B8B1A3; font-size:0.85rem; margin:0; line-height:1.5;">
                This quotation was declined. You can submit a new enquiry with updated specifications at any time.
              </p>
            </div>
            <a href="<?= url('get-quote') ?>" class="btn btn-outline" style="width:100%; text-align:center; display:block;">
              Submit New Print Enquiry
            </a>

          <?php else: ?>
            <!-- Accept Form -->
            <form action="<?= url('account/quotations.php') ?>" method="POST" style="margin-bottom:1.5rem;">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="accept">

              <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label" style="font-size:0.82rem; color:#B8B1A3;">Special Notes / Dispatch Remarks (Optional)</label>
                <textarea name="customer_feedback" class="form-control" placeholder="Any delivery preferences, GST invoicing details, or deadline instructions..." style="min-height:75px; font-size:0.85rem;"></textarea>
              </div>

              <button type="submit" class="btn btn-primary btn-lg" style="width:100%;" onclick="return confirm('Confirm acceptance of Quotation <?= e($singleQuote['quote_number']) ?>?');">
                &check; Accept &amp; Confirm Quotation
              </button>
            </form>

            <!-- Revision Form -->
            <form action="<?= url('account/quotations.php') ?>" method="POST" style="margin-bottom:1rem;">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="request_change">

              <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="font-size:0.82rem; color:#B8B1A3;">Need Adjustments? Request Revisions</label>
                <textarea name="customer_feedback" class="form-control" placeholder="Specify required changes (e.g., increase quantity to 500, change garment size mix, adjust gang sheet layout)..." style="min-height:70px; font-size:0.85rem;" required></textarea>
              </div>

              <button type="submit" class="btn btn-sm btn-outline" style="width:100%;">
                Request Prepress Revision
              </button>
            </form>

            <!-- Decline Form -->
            <form action="<?= url('account/quotations.php') ?>" method="POST">
              <?= csrf_field() ?>
              <input type="hidden" name="quotation_id" value="<?= e($singleQuote['id']) ?>">
              <input type="hidden" name="action" value="reject">
              <button type="submit" class="btn btn-sm" style="width:100%; background:none; border:1px solid #40351E; color:#f87171;" onclick="return confirm('Are you sure you want to decline Quotation <?= e($singleQuote['quote_number']) ?>?');">
                Decline Quotation
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>

    <?php else: ?>
      <!-- All Quotations Table Card -->
      <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; overflow:hidden;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #2A2415; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
          <div>
            <h2 style="font-size:1.15rem; color:#F5F1E8; margin:0 0 0.2rem; font-weight:700;">Commercial Quotation History</h2>
            <p style="font-size:0.85rem; color:#B8B1A3; margin:0;">All custom quotations generated for your business</p>
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse; font-size:0.9rem; text-align:left;">
            <thead>
              <tr style="border-bottom:1px solid #2A2415; background:rgba(212,175,55,0.03);">
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Quote Number</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Enquiry Reference</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Date Generated</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Grand Total</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Status</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Valid Until</th>
                <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700; text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($allQuotes)): ?>
                <tr>
                  <td colspan="7" style="padding:3.5rem 1.5rem; text-align:center; color:#B8B1A3;">
                    <div style="font-size:1.1rem; margin-bottom:0.5rem; color:#F5F1E8; font-weight:600;">No quotations generated yet</div>
                    <p style="margin:0 0 1.25rem; font-size:0.9rem;">Submit your commercial printing specifications to receive an itemized quotation.</p>
                    <a href="<?= url('get-quote') ?>" class="btn btn-primary btn-sm">Request Your First Quote</a>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($allQuotes as $q): ?>
                  <tr style="border-bottom:1px solid #1A1712;">
                    <td style="padding:0.9rem 1.25rem; font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                      <?= e($q['quote_number']) ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                      <?= e($q['enquiry_ref'] ?? 'Direct') ?> <?= !empty($q['service_name']) ? '&bull; ' . e($q['service_name']) : '' ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                      <?= format_date($q['created_at'], 'd M Y') ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem; font-weight:800; color:#F5F1E8; font-family:var(--font-mono);">
                      <?= format_currency($q['grand_total']) ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem;">
                      <?= get_status_badge($q['status']) ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                      <?= $q['valid_until'] ? format_date($q['valid_until'], 'd M Y') : '15 days' ?>
                    </td>
                    <td style="padding:0.9rem 1.25rem; text-align:right;">
                      <a href="<?= url('account/quotations.php?id=' . $q['id']) ?>" class="btn btn-sm <?= $q['status'] === 'sent' ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.35rem 0.85rem; font-size:0.8rem;">
                        <?= $q['status'] === 'sent' ? 'Review &amp; Approve' : 'View Quote' ?>
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
