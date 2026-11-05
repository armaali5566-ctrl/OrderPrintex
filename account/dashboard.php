<?php
/**
 * OrderPrintex - Customer Account Dashboard
 * Pure Enquiry & Quotation Workflow
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();
$uid = $user['id'];
$uemail = $user['email'];

// Database metrics for this customer
$totalEnquiries = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE user_id = :uid OR customer_email = :email", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);
$openEnquiries = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE (user_id = :uid OR customer_email = :email) AND status IN ('pending', 'under_review')", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);
$totalQuotes = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE user_id = :uid OR customer_email = :email", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);
$pendingQuotes = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE (user_id = :uid OR customer_email = :email) AND status = 'sent'", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);
$approvedQuotes = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE (user_id = :uid OR customer_email = :email) AND status = 'accepted'", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);

// Recent Enquiries (latest 5)
$recentEnquiries = db_fetch_all("
    SELECT e.*, q.id as quotation_id, q.quote_number, q.status as quotation_status 
    FROM enquiries e
    LEFT JOIN quotations q ON q.enquiry_id = e.id
    WHERE e.user_id = :uid OR e.customer_email = :email
    ORDER BY e.created_at DESC LIMIT 5
", ['uid' => $uid, 'email' => $uemail]);

// Recent Quotations (latest 5)
$recentQuotes = db_fetch_all("
    SELECT q.*, e.ref_number as enquiry_ref, e.service_name 
    FROM quotations q
    LEFT JOIN enquiries e ON e.id = q.enquiry_id
    WHERE q.user_id = :uid OR q.customer_email = :email
    ORDER BY q.created_at DESC LIMIT 5
", ['uid' => $uid, 'email' => $uemail]);

$pageTitle = "Client Portal Overview | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0 4rem; min-height:85vh; color:#F5F1E8;">
  <div class="container">
    <!-- Welcome Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label" style="color:#D4AF37; margin-bottom:0.25rem;">Client Portal</div>
        <h1 style="font-size:clamp(1.75rem, 3vw, 2.25rem); color:#F5F1E8; margin:0 0 0.35rem; font-weight:800; font-family:var(--font-serif);">
          Welcome, <?= e($user['name']) ?>
        </h1>
        <p style="color:#B8B1A3; margin:0; font-size:0.95rem;">
          <?= e($user['company_name'] ? $user['company_name'] . ' &bull; ' : '') ?><?= e($user['email']) ?>
        </p>
      </div>
      <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary" style="padding:0.65rem 1.4rem;">
          <span>+ Request New Quotation</span>
        </a>
        <a href="<?= account_url('profile') ?>" class="btn btn-outline" style="padding:0.65rem 1.25rem;">
          Account Settings
        </a>
      </div>
    </div>

    <?= render_flash() ?>

    <!-- Sub-navigation Tabs -->
    <div style="display:flex; gap:0.5rem; border-bottom:1px solid #2A2415; margin-bottom:2rem; overflow-x:auto; padding-bottom:2px;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn active" style="text-decoration:none;">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn" style="text-decoration:none;">
        My Enquiries (<?= $totalEnquiries ?>)
      </a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn" style="text-decoration:none;">
        My Quotations <?= $pendingQuotes > 0 ? '<span style="background:#D4AF37; color:#080808; padding:2px 7px; border-radius:10px; font-size:0.75rem; font-weight:800; margin-left:4px;">' . $pendingQuotes . '</span>' : '(' . $totalQuotes . ')' ?>
      </a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn" style="text-decoration:none;">Settings</a>
    </div>

    <!-- 5 KPI Metrics Grid -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:2.5rem;">
      <!-- Total Enquiries -->
      <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:600; margin-bottom:0.35rem;">Total Enquiries</div>
          <div style="font-size:2rem; font-weight:800; color:#F5F1E8; line-height:1; font-family:var(--font-mono);"><?= $totalEnquiries ?></div>
        </div>
        <div style="width:44px; height:44px; border-radius:8px; background:rgba(212,175,55,0.08); border:1px solid #40351E; display:flex; align-items:center; justify-content:center; color:#D4AF37;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
          </svg>
        </div>
      </div>

      <!-- Open Enquiries -->
      <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:600; margin-bottom:0.35rem;">Open Enquiries</div>
          <div style="font-size:2rem; font-weight:800; color:#60A5FA; line-height:1; font-family:var(--font-mono);"><?= $openEnquiries ?></div>
        </div>
        <div style="width:44px; height:44px; border-radius:8px; background:rgba(96,165,250,0.1); border:1px solid rgba(96,165,250,0.3); display:flex; align-items:center; justify-content:center; color:#60A5FA;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
        </div>
      </div>

      <!-- Total Quotations -->
      <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:600; margin-bottom:0.35rem;">Total Quotations</div>
          <div style="font-size:2rem; font-weight:800; color:#F5F1E8; line-height:1; font-family:var(--font-mono);"><?= $totalQuotes ?></div>
        </div>
        <div style="width:44px; height:44px; border-radius:8px; background:rgba(212,175,55,0.08); border:1px solid #40351E; display:flex; align-items:center; justify-content:center; color:#D4AF37;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
            <line x1="8" y1="21" x2="16" y2="21"></line>
            <line x1="12" y1="17" x2="12" y2="21"></line>
          </svg>
        </div>
      </div>

      <!-- Pending Quotations -->
      <div style="background:#121212; border:1px solid <?= $pendingQuotes > 0 ? '#D4AF37' : '#2A2415' ?>; border-radius:8px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#D4AF37; font-weight:700; margin-bottom:0.35rem;">Awaiting Review</div>
          <div style="font-size:2rem; font-weight:800; color:#FFD76A; line-height:1; font-family:var(--font-mono);"><?= $pendingQuotes ?></div>
        </div>
        <div style="width:44px; height:44px; border-radius:8px; background:rgba(212,175,55,0.15); border:1px solid #D4AF37; display:flex; align-items:center; justify-content:center; color:#FFD76A;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
      </div>

      <!-- Approved Quotations -->
      <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:600; margin-bottom:0.35rem;">Approved Quotes</div>
          <div style="font-size:2rem; font-weight:800; color:#34D399; line-height:1; font-family:var(--font-mono);"><?= $approvedQuotes ?></div>
        </div>
        <div style="width:44px; height:44px; border-radius:8px; background:rgba(52,211,153,0.1); border:1px solid rgba(52,211,153,0.3); display:flex; align-items:center; justify-content:center; color:#34D399;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
      </div>
    </div>

    <!-- Quotations Awaiting Review Section -->
    <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; margin-bottom:2rem; overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #2A2415; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
        <div>
          <h2 style="font-size:1.15rem; color:#F5F1E8; margin:0 0 0.2rem; font-weight:700;">Recent Quotations</h2>
          <p style="font-size:0.85rem; color:#B8B1A3; margin:0;">Transparent pricing breakdowns ready for your review and approval</p>
        </div>
        <a href="<?= account_url('quotations') ?>" style="color:#D4AF37; font-size:0.85rem; font-weight:600; text-decoration:none;">
          View All Quotations &rarr;
        </a>
      </div>

      <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem; text-align:left;">
          <thead>
            <tr style="border-bottom:1px solid #2A2415; background:rgba(212,175,55,0.03);">
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Quote #</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Enquiry Ref</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Date Issued</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Grand Total</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Status</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Validity</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700; text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentQuotes)): ?>
              <tr>
                <td colspan="7" style="padding:3rem 1.5rem; text-align:center; color:#B8B1A3;">
                  <div style="font-size:1.05rem; margin-bottom:0.5rem; color:#F5F1E8;">No quotations generated yet</div>
                  <p style="margin:0 0 1rem; font-size:0.88rem;">Submit your commercial printing specifications to receive an itemized quote.</p>
                  <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-sm">Request Your First Quote</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentQuotes as $qt): ?>
                <tr style="border-bottom:1px solid #1A1712;">
                  <td style="padding:0.9rem 1.25rem; font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                    <?= e($qt['quote_number']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                    <?= e($qt['enquiry_ref'] ?? 'Direct') ?> <?= !empty($qt['service_name']) ? '&bull; ' . e($qt['service_name']) : '' ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                    <?= format_date($qt['created_at'], 'd M Y') ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; font-weight:800; color:#F5F1E8; font-family:var(--font-mono);">
                    <?= format_currency($qt['grand_total']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem;">
                    <?= get_status_badge($qt['status']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                    <?= $qt['valid_until'] ? 'Valid till ' . format_date($qt['valid_until'], 'd M Y') : '15 Days' ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; text-align:right;">
                    <a href="<?= url('account/quotations.php?id=' . $qt['id']) ?>" class="btn btn-sm <?= $qt['status'] === 'sent' ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.35rem 0.85rem; font-size:0.8rem;">
                      <?= $qt['status'] === 'sent' ? 'Review &amp; Approve' : 'View Quote' ?>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Enquiries Section -->
    <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #2A2415; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
        <div>
          <h2 style="font-size:1.15rem; color:#F5F1E8; margin:0 0 0.2rem; font-weight:700;">Recent Print Enquiries</h2>
          <p style="font-size:0.85rem; color:#B8B1A3; margin:0;">Track prepress audit status and quotation progress for your submitted specifications</p>
        </div>
        <a href="<?= account_url('enquiries') ?>" style="color:#D4AF37; font-size:0.85rem; font-weight:600; text-decoration:none;">
          View All Enquiries &rarr;
        </a>
      </div>

      <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem; text-align:left;">
          <thead>
            <tr style="border-bottom:1px solid #2A2415; background:rgba(212,175,55,0.03);">
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Ref #</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Service / Product</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Specifications</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Date Submitted</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Status</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700; text-align:right;">Quotation</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentEnquiries)): ?>
              <tr>
                <td colspan="6" style="padding:3rem 1.5rem; text-align:center; color:#B8B1A3;">
                  <div style="font-size:1.05rem; margin-bottom:0.5rem; color:#F5F1E8;">No enquiries submitted yet</div>
                  <p style="margin:0 0 1rem; font-size:0.88rem;">Have a custom DTF, UV DTF, or garment printing requirement? Request a quote anytime.</p>
                  <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-sm">Submit New Enquiry</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentEnquiries as $enq): ?>
                <tr style="border-bottom:1px solid #1A1712;">
                  <td style="padding:0.9rem 1.25rem; font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                    <?= e($enq['ref_number']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; font-weight:600; color:#F5F1E8;">
                    <?= e($enq['service_name']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; font-size:0.85rem; color:#B8B1A3;">
                    Qty: <strong style="color:#F5F1E8;"><?= (int)$enq['quantity'] ?></strong>
                    <?php if (!empty($enq['width_inches']) && !empty($enq['height_inches'])): ?>
                      &bull; <?= e($enq['width_inches']) ?>&Prime; &times; <?= e($enq['height_inches']) ?>&Prime;
                    <?php endif; ?>
                    <?php if (!empty($enq['material_options'])): ?>
                      <div style="font-size:0.78rem; color:#8A8275; margin-top:2px;"><?= e($enq['material_options']) ?></div>
                    <?php endif; ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                    <?= format_date($enq['created_at'], 'd M Y') ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem;">
                    <?= get_status_badge($enq['status']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; text-align:right;">
                    <?php if ($enq['quotation_id']): ?>
                      <a href="<?= url('account/quotations.php?id=' . $enq['quotation_id']) ?>" class="btn btn-sm btn-primary" style="padding:0.35rem 0.85rem; font-size:0.8rem;">
                        View Quote (<?= e($enq['quote_number']) ?>)
                      </a>
                    <?php else: ?>
                      <span style="font-size:0.8rem; color:#8A8275; font-style:italic;">Prepress Review</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
