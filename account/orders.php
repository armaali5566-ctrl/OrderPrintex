<?php
/**
 * OrderPrintex - Customer Orders List
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

// OrderPrintex has transitioned to an Enquiry & Quotation business model. Orders interface redirected to Quotations.
header('Location: ' . url('account/quotations.php'));
exit;

$orders = db_fetch_all("
    SELECT o.*, q.quote_number, e.service_name
    FROM orders o
    LEFT JOIN quotations q ON o.quotation_id = q.id
    LEFT JOIN enquiries e ON o.enquiry_id = e.id
    WHERE o.user_id = :uid OR o.customer_email = :email
    ORDER BY o.created_at DESC
", ['uid' => $user['id'], 'email' => $user['email']]);

$pageTitle = "My Production Orders & Tracking | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0; min-height:85vh;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label" style="color:#D4AF37;">Production Tracker</div>
        <h1 style="font-size:2.2rem; color:#F5F1E8;">My Production Orders</h1>
        <p style="color:#B8B1A3; margin:0;">
          Live status milestones, tracking numbers, and proof approval for approved production jobs.
        </p>
      </div>
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary">
        <span>+ Place New Print Order</span>
      </a>
    </div>

    <?= render_flash() ?>

    <div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-light); margin-bottom:2rem; overflow-x:auto;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn">My Enquiries</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn">My Quotations</a>
      <a href="<?= account_url('orders') ?>" class="gallery-filter-btn active">My Orders (<?= count($orders) ?>)</a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn">Settings</a>
    </div>

    <div class="panel-card">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Order Number</th>
              <th>Placed On</th>
              <th>Service</th>
              <th>Total Amount</th>
              <th>Payment Status</th>
              <th>Current Production Status</th>
              <th>Dispatch / Courier</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($orders)): ?>
              <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:var(--slate);">
                  No active orders found. Approved quotations automatically convert to production orders here.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($orders as $ord): ?>
                <tr>
                  <td class="ref-cell"><?= e($ord['order_number']) ?></td>
                  <td><?= format_date($ord['created_at'], 'd M Y') ?></td>
                  <td><strong><?= e($ord['service_name'] ?: 'Custom Printing') ?></strong></td>
                  <td style="font-weight:700; color:#FFD76A; font-family:var(--font-mono);">
                    <?= format_currency($ord['total_amount']) ?>
                  </td>
                  <td><?= get_status_badge($ord['payment_status']) ?></td>
                  <td><?= get_status_badge($ord['order_status']) ?></td>
                  <td>
                    <?php if ($ord['tracking_number']): ?>
                      <div style="font-size:0.8rem; font-family:var(--font-mono); font-weight:600;">
                        <?= e($ord['courier_name']) ?><br>
                        <span class="text-blue"><?= e($ord['tracking_number']) ?></span>
                      </div>
                    <?php else: ?>
                      <span class="text-muted" style="font-size:0.8rem;">In Studio Processing</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?= url('account/orders/' . $ord['id'] . '/') ?>" class="btn btn-sm btn-navy">
                      Track &amp; View
                    </a>
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
