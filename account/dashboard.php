<?php
/**
 * OrderPrintex - Customer Dashboard
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

// Real Database-driven metrics for this customer
$totalEnquiries = db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE user_id = :uid", ['uid' => $user['id']])['cnt'] ?? 0;
$pendingQuotes  = db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE user_id = :uid AND status = 'sent'", ['uid' => $user['id']])['cnt'] ?? 0;
$activeOrders   = db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE user_id = :uid AND order_status NOT IN ('delivered', 'cancelled')", ['uid' => $user['id']])['cnt'] ?? 0;
$completedOrders= db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE user_id = :uid AND order_status = 'delivered'", ['uid' => $user['id']])['cnt'] ?? 0;

// Recent Orders
$recentOrders = db_fetch_all("
    SELECT * FROM orders 
    WHERE user_id = :uid 
    ORDER BY created_at DESC LIMIT 5
", ['uid' => $user['id']]);

// Recent Quotations
$recentQuotes = db_fetch_all("
    SELECT * FROM quotations 
    WHERE user_id = :uid 
    ORDER BY created_at DESC LIMIT 5
", ['uid' => $user['id']]);

$pageTitle = "My Account Dashboard | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#F8FAFC; padding:2.5rem 0; min-height:85vh;">
  <div class="container">
    <!-- Welcome Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2.5rem;">
      <div>
        <div class="section-label">Customer Portal</div>
        <h1 style="font-size:2.2rem; color:var(--navy);">Welcome, <?= e($user['name']) ?></h1>
        <p style="color:var(--slate); margin:0;">
          <?= e($user['company_name'] ? $user['company_name'] . ' &bull; ' : '') ?><?= e($user['email']) ?>
        </p>
      </div>
      <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <a href="<?= url('get-quote/') ?>" class="btn btn-primary">
          <span>+ Upload New Design</span>
        </a>
        <a href="<?= account_url('profile') ?>" class="btn btn-outline">Edit Profile</a>
      </div>
    </div>

    <?= render_flash() ?>

    <!-- Sub-navigation Tabs -->
    <div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-light); margin-bottom:2rem; overflow-x:auto;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn active">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn">My Enquiries (<?= $totalEnquiries ?>)</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn">My Quotations (<?= $pendingQuotes ?> pending)</a>
      <a href="<?= account_url('orders') ?>" class="gallery-filter-btn">My Orders (<?= $activeOrders ?> active)</a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn">Settings</a>
    </div>

    <!-- Metrics Grid -->
    <div class="metrics-grid">
      <div class="metric-card">
        <div>
          <div class="metric-label">Total Enquiries</div>
          <div class="metric-value"><?= (int)$totalEnquiries ?></div>
        </div>
        <div class="metric-icon-box">
          <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
          </svg>
        </div>
      </div>

      <div class="metric-card">
        <div>
          <div class="metric-label">Pending Quotations</div>
          <div class="metric-value text-gold"><?= (int)$pendingQuotes ?></div>
        </div>
        <div class="metric-icon-box">
          <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 8h3a1 1 0 110 2h-4a1 1 0 01-1-1V5a1 1 0 112 0v5z" clip-rule="evenodd"/>
          </svg>
        </div>
      </div>

      <div class="metric-card">
        <div>
          <div class="metric-label">Active Orders</div>
          <div class="metric-value text-blue"><?= (int)$activeOrders ?></div>
        </div>
        <div class="metric-icon-box">
          <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
          </svg>
        </div>
      </div>

      <div class="metric-card">
        <div>
          <div class="metric-label">Completed Deliveries</div>
          <div class="metric-value" style="color:#15803D;"><?= (int)$completedOrders ?></div>
        </div>
        <div class="metric-icon-box">
          <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Active Orders Panel -->
    <div class="panel-card">
      <div class="panel-header">
        <h3 class="panel-title">Recent Production Orders</h3>
        <a href="<?= account_url('orders') ?>" class="text-gold" style="font-size:0.85rem; font-weight:600;">View All Orders &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Order Ref</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Payment</th>
              <th>Order Status</th>
              <th>Tracking</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentOrders)): ?>
              <tr>
                <td colspan="7" style="text-align:center; padding:2.5rem; color:var(--slate);">
                  No orders placed yet. Once you approve a quotation, your orders will appear here.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentOrders as $ord): ?>
                <tr>
                  <td class="ref-cell"><?= e($ord['order_number']) ?></td>
                  <td><?= format_date($ord['created_at'], 'd M Y') ?></td>
                  <td style="font-weight:700; color:var(--navy);"><?= format_currency($ord['total_amount']) ?></td>
                  <td><?= get_status_badge($ord['payment_status']) ?></td>
                  <td><?= get_status_badge($ord['order_status']) ?></td>
                  <td>
                    <?php if ($ord['tracking_number']): ?>
                      <span style="font-family:var(--font-mono); font-size:0.8rem;"><?= e($ord['courier_name']) ?>: <?= e($ord['tracking_number']) ?></span>
                    <?php else: ?>
                      <span class="text-muted">Awaiting Dispatch</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?= url('account/orders/' . $ord['id'] . '/') ?>" class="btn btn-sm btn-outline">
                      Details &amp; Timeline
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pending Quotations Panel -->
    <div class="panel-card">
      <div class="panel-header">
        <h3 class="panel-title">Recent Quotations for Review</h3>
        <a href="<?= url('account/quotations.php') ?>" class="text-gold" style="font-size:0.85rem; font-weight:600;">View All Quotations &rarr;</a>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Quote #</th>
              <th>Date Issued</th>
              <th>Grand Total</th>
              <th>Status</th>
              <th>Validity</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentQuotes)): ?>
              <tr>
                <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--slate);">
                  No quotations currently generated. Submit an artwork design to receive your first quotation.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentQuotes as $qt): ?>
                <tr>
                  <td class="ref-cell"><?= e($qt['quote_number']) ?></td>
                  <td><?= format_date($qt['created_at'], 'd M Y') ?></td>
                  <td style="font-weight:700; color:var(--navy);"><?= format_currency($qt['grand_total']) ?></td>
                  <td><?= get_status_badge($qt['status']) ?></td>
                  <td>
                    <?= $qt['valid_until'] ? 'Valid until ' . format_date($qt['valid_until'], 'd M Y') : '15 Days' ?>
                  </td>
                  <td>
                    <a href="<?= url('account/quotations.php?id=' . $qt['id']) ?>" class="btn btn-sm btn-primary">
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
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
