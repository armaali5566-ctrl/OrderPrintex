<?php
/**
 * OrderPrintex - Customer Enquiries History
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

$enquiries = db_fetch_all("
    SELECT e.*, 
           q.id as quotation_id, q.quote_number, q.status as quotation_status, q.grand_total
    FROM enquiries e
    LEFT JOIN quotations q ON q.enquiry_id = e.id
    WHERE e.user_id = :uid OR e.customer_email = :email
    ORDER BY e.created_at DESC
", ['uid' => $uid, 'email' => $uemail]);

$pendingQuotesCount = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE (user_id = :uid OR customer_email = :email) AND status = 'sent'", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);

$pageTitle = "My Print Enquiries | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0 4rem; min-height:85vh; color:#F5F1E8;">
  <div class="container">
    <!-- Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label" style="color:#D4AF37; margin-bottom:0.25rem;">Account History</div>
        <h1 style="font-size:clamp(1.75rem, 3vw, 2.25rem); color:#F5F1E8; margin:0 0 0.35rem; font-weight:800; font-family:var(--font-serif);">
          My Print Enquiries
        </h1>
        <p style="color:#B8B1A3; margin:0; font-size:0.95rem;">
          Track prepress specifications, custom parameters, and quotation statuses.
        </p>
      </div>
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary" style="padding:0.65rem 1.4rem;">
        <span>+ Request New Quotation</span>
      </a>
    </div>

    <?= render_flash() ?>

    <!-- Sub-navigation Tabs -->
    <div style="display:flex; gap:0.5rem; border-bottom:1px solid #2A2415; margin-bottom:2rem; overflow-x:auto; padding-bottom:2px;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn" style="text-decoration:none;">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn active" style="text-decoration:none;">
        My Enquiries (<?= count($enquiries) ?>)
      </a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn" style="text-decoration:none;">
        My Quotations <?= $pendingQuotesCount > 0 ? '<span style="background:#D4AF37; color:#080808; padding:2px 7px; border-radius:10px; font-size:0.75rem; font-weight:800; margin-left:4px;">' . $pendingQuotesCount . '</span>' : '' ?>
      </a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn" style="text-decoration:none;">Settings</a>
    </div>

    <!-- Enquiries Table Card -->
    <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #2A2415; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
        <div>
          <h2 style="font-size:1.15rem; color:#F5F1E8; margin:0 0 0.2rem; font-weight:700;">Submitted Print Requests</h2>
          <p style="font-size:0.85rem; color:#B8B1A3; margin:0;">All commercial enquiries submitted from your registered account</p>
        </div>
      </div>

      <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem; text-align:left;">
          <thead>
            <tr style="border-bottom:1px solid #2A2415; background:rgba(212,175,55,0.03);">
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Ref Number</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Date Submitted</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Service / Category</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Specifications</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700;">Enquiry Status</th>
              <th style="padding:0.85rem 1.25rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B8B1A3; font-weight:700; text-align:right;">Quotation Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($enquiries)): ?>
              <tr>
                <td colspan="6" style="padding:3.5rem 1.5rem; text-align:center; color:#B8B1A3;">
                  <div style="font-size:1.1rem; margin-bottom:0.5rem; color:#F5F1E8; font-weight:600;">No enquiries submitted yet</div>
                  <p style="margin:0 0 1.25rem; font-size:0.9rem;">Submit your commercial printing parameters to receive a custom quotation from our studio.</p>
                  <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-sm">Request Your First Quote</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($enquiries as $enq): ?>
                <tr style="border-bottom:1px solid #1A1712;">
                  <td style="padding:0.9rem 1.25rem; font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                    <?= e($enq['ref_number']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; color:#B8B1A3; font-size:0.85rem;">
                    <?= format_date($enq['created_at'], 'd M Y, h:i A') ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; font-weight:600; color:#F5F1E8;">
                    <?= e($enq['service_name']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; font-size:0.85rem; color:#B8B1A3;">
                    Quantity: <strong style="color:#F5F1E8;"><?= (int)$enq['quantity'] ?></strong>
                    <?php if (!empty($enq['width_inches']) && !empty($enq['height_inches'])): ?>
                      &bull; <?= e($enq['width_inches']) ?>&Prime; &times; <?= e($enq['height_inches']) ?>&Prime;
                    <?php endif; ?>
                    <?php if (!empty($enq['material_options'])): ?>
                      <div style="font-size:0.78rem; color:#8A8275; margin-top:2px;"><?= e($enq['material_options']) ?></div>
                    <?php endif; ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem;">
                    <?= get_status_badge($enq['status']) ?>
                  </td>
                  <td style="padding:0.9rem 1.25rem; text-align:right;">
                    <?php if ($enq['quotation_id']): ?>
                      <a href="<?= url('account/quotations.php?id=' . $enq['quotation_id']) ?>" class="btn btn-sm <?= $enq['quotation_status'] === 'sent' ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.35rem 0.85rem; font-size:0.8rem;">
                        View Quote (<?= e($enq['quote_number']) ?>)
                      </a>
                    <?php else: ?>
                      <span style="font-size:0.82rem; color:#8A8275; font-style:italic;">Prepress Review</span>
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
