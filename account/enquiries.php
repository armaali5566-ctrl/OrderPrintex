<?php
/**
 * OrderPrintex - Customer Enquiries History
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

$enquiries = db_fetch_all("
    SELECT e.*, 
           q.id as quotation_id, q.quote_number, q.status as quotation_status,
           ef.file_path, ef.original_name, ef.file_size
    FROM enquiries e
    LEFT JOIN quotations q ON q.enquiry_id = e.id
    LEFT JOIN enquiry_files ef ON ef.enquiry_id = e.id
    WHERE e.user_id = :uid OR e.customer_email = :email
    ORDER BY e.created_at DESC
", ['uid' => $user['id'], 'email' => $user['email']]);

$pageTitle = "My Enquiries & Artwork Files | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#F8FAFC; padding:2.5rem 0; min-height:85vh;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label">Account History</div>
        <h1 style="font-size:2.2rem; color:var(--navy);">My Enquiries &amp; Artwork</h1>
        <p style="color:var(--slate); margin:0;">
          All custom print requests and uploaded artwork files submitted under your account.
        </p>
      </div>
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary">
        <span>+ Submit New Artwork</span>
      </a>
    </div>

    <?= render_flash() ?>

    <div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-light); margin-bottom:2rem; overflow-x:auto;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn active">My Enquiries (<?= count($enquiries) ?>)</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn">My Quotations</a>
      <a href="<?= account_url('orders') ?>" class="gallery-filter-btn">My Orders</a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn">Settings</a>
    </div>

    <div class="panel-card">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Reference #</th>
              <th>Date</th>
              <th>Service</th>
              <th>Specifications</th>
              <th>Artwork File</th>
              <th>Status</th>
              <th>Quotation</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($enquiries)): ?>
              <tr>
                <td colspan="7" style="text-align:center; padding:3rem; color:var(--slate);">
                  You have not submitted any enquiries yet. 
                  <a href="<?= url('get-quote.php') ?>" class="text-gold" style="font-weight:600;">Upload your first design &rarr;</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($enquiries as $enq): ?>
                <tr>
                  <td class="ref-cell"><?= e($enq['ref_number']) ?></td>
                  <td><?= format_date($enq['created_at'], 'd M Y, h:i A') ?></td>
                  <td>
                    <strong><?= e($enq['service_name']) ?></strong>
                  </td>
                  <td>
                    <div style="font-size:0.85rem;">
                      Qty: <strong><?= e($enq['quantity']) ?></strong>
                      <?php if ($enq['width_inches'] && $enq['height_inches']): ?>
                        &bull; <?= e($enq['width_inches']) ?>" &times; <?= e($enq['height_inches']) ?>"
                      <?php endif; ?>
                    </div>
                    <?php if ($enq['material_options']): ?>
                      <small style="color:var(--slate);"><?= e($enq['material_options']) ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($enq['file_path']): ?>
                      <div style="display:flex; align-items:center; gap:0.45rem;">
                        <svg style="width:16px;height:16px;fill:var(--gold);" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <a href="<?= url(e($enq['file_path'])) ?>" target="_blank" class="text-blue" style="font-weight:600; font-size:0.85rem;" download>
                          <?= e(mb_strimwidth($enq['original_name'], 0, 18, '...')) ?>
                        </a>
                      </div>
                    <?php else: ?>
                      <span class="text-muted">No file</span>
                    <?php endif; ?>
                  </td>
                  <td><?= get_status_badge($enq['status']) ?></td>
                  <td>
                    <?php if ($enq['quotation_id']): ?>
                      <a href="<?= url('account/quotations.php?id=' . $enq['quotation_id']) ?>" class="btn btn-sm btn-primary">
                        View Quote (<?= e($enq['quote_number']) ?>)
                      </a>
                    <?php else: ?>
                      <span style="font-size:0.8rem; color:var(--slate);">Prepress Review</span>
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
