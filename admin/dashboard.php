<?php
/**
 * OrderPrintex - Admin Master Dashboard
 * Fully Database-Driven Real Metrics
 */

$adminTitle = "Dashboard Overview";
require_once __DIR__ . '/header.php';

// Actual Database-Driven Metrics
$totalEnquiries   = db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries")['cnt'] ?? 0;
$pendingQuotes    = db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status = 'sent'")['cnt'] ?? 0;
$totalOrders      = db_fetch_one("SELECT COUNT(*) as cnt FROM orders")['cnt'] ?? 0;
$activeOrders     = db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE order_status NOT IN ('delivered', 'cancelled')")['cnt'] ?? 0;
$completedOrders  = db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE order_status = 'delivered'")['cnt'] ?? 0;
$totalCustomers   = db_fetch_one("SELECT COUNT(*) as cnt FROM users WHERE role = 'customer'")['cnt'] ?? 0;
$totalRevenue     = db_fetch_one("SELECT SUM(total_amount) as rev FROM orders WHERE payment_status = 'paid'")['rev'] ?? 0.00;

// Recent Enquiries
$recentEnquiries = db_fetch_all("
    SELECT e.*, ef.file_path, ef.original_name 
    FROM enquiries e 
    LEFT JOIN enquiry_files ef ON ef.enquiry_id = e.id 
    ORDER BY e.created_at DESC LIMIT 5
");

// Recent Orders
$recentOrders = db_fetch_all("
    SELECT o.*, q.quote_number 
    FROM orders o 
    LEFT JOIN quotations q ON o.quotation_id = q.id 
    ORDER BY o.created_at DESC LIMIT 5
");

// Recent Activity Logs
$recentActivity = db_fetch_all("
    SELECT a.*, u.name as user_name, u.email as user_email 
    FROM activity_logs a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC LIMIT 6
");
?>

<!-- Metrics Summary Grid -->
<div class="metrics-grid">
  <div class="metric-card">
    <div>
      <div class="metric-label">Total Enquiries</div>
      <div class="metric-value"><?= (int)$totalEnquiries ?></div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
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
      <div class="metric-label">Total Orders</div>
      <div class="metric-value"><?= (int)$totalOrders ?></div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">Active in Production</div>
      <div class="metric-value text-blue"><?= (int)$activeOrders ?></div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
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

  <div class="metric-card">
    <div>
      <div class="metric-label">Registered Customers</div>
      <div class="metric-value"><?= (int)$totalCustomers ?></div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 20 20">
        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
      </svg>
    </div>
  </div>
</div>

<!-- Two-Column Grid: Recent Enquiries & Recent Orders -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:2rem;">
  <!-- Recent Enquiries -->
  <div class="panel-card" style="margin-bottom:0;">
    <div class="panel-header">
      <h3 class="panel-title">Latest Customer Enquiries</h3>
      <a href="<?= url('admin/enquiries.php') ?>" class="text-gold" style="font-size:0.85rem; font-weight:600;">View All &rarr;</a>
    </div>
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Ref #</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Artwork</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentEnquiries)): ?>
            <tr>
              <td colspan="6" style="text-align:center; padding:2rem; color:var(--slate);">No enquiries submitted yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentEnquiries as $enq): ?>
              <tr>
                <td class="ref-cell"><?= e($enq['ref_number']) ?></td>
                <td>
                  <strong><?= e($enq['customer_name']) ?></strong><br>
                  <small style="color:var(--slate);"><?= e($enq['customer_phone']) ?></small>
                </td>
                <td><?= e($enq['service_name']) ?></td>
                <td>
                  <?php if ($enq['file_path']): ?>
                    <a href="<?= url(e($enq['file_path'])) ?>" target="_blank" class="text-blue" download style="font-weight:600; font-size:0.8rem;">
                      Download File
                    </a>
                  <?php else: ?>
                    <span class="text-muted">None</span>
                  <?php endif; ?>
                </td>
                <td><?= get_status_badge($enq['status']) ?></td>
                <td>
                  <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $enq['id']) ?>" class="btn btn-sm btn-primary" style="padding:0.35rem 0.65rem; font-size:0.75rem;">
                    Quote
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Orders -->
  <div class="panel-card" style="margin-bottom:0;">
    <div class="panel-header">
      <h3 class="panel-title">Active Production Orders</h3>
      <a href="<?= url('admin/orders.php') ?>" class="text-gold" style="font-size:0.85rem; font-weight:600;">View All &rarr;</a>
    </div>
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentOrders)): ?>
            <tr>
              <td colspan="5" style="text-align:center; padding:2rem; color:var(--slate);">No orders placed yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentOrders as $ord): ?>
              <tr>
                <td class="ref-cell"><?= e($ord['order_number']) ?></td>
                <td>
                  <strong><?= e($ord['customer_name']) ?></strong><br>
                  <small style="color:var(--slate);"><?= e($ord['city']) ?></small>
                </td>
                <td style="font-weight:700; color:var(--navy); font-family:var(--font-mono);">
                  <?= format_currency($ord['total_amount']) ?>
                </td>
                <td><?= get_status_badge($ord['order_status']) ?></td>
                <td>
                  <a href="<?= url('admin/orders.php?order_id=' . $ord['id']) ?>" class="btn btn-sm btn-outline" style="padding:0.35rem 0.65rem; font-size:0.75rem;">
                    Manage
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

<!-- Recent Activity Logs Table -->
<div class="panel-card">
  <div class="panel-header">
    <h3 class="panel-title">Security &amp; Production Activity Log</h3>
  </div>
  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>User</th>
          <th>Action Type</th>
          <th>Details</th>
          <th>IP Address</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentActivity)): ?>
          <tr>
            <td colspan="5" style="text-align:center; padding:2rem; color:var(--slate);">No system logs recorded yet.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentActivity as $act): ?>
            <tr>
              <td style="font-size:0.82rem; color:var(--slate-light);"><?= format_date($act['created_at'], 'd M Y, h:i:s A') ?></td>
              <td><?= e($act['user_name'] ?: ($act['user_email'] ?: 'System Guest')) ?></td>
              <td><span class="status-badge badge-secondary"><?= e($act['action']) ?></span></td>
              <td style="font-size:0.85rem;"><?= e($act['details'] ?: '—') ?></td>
              <td style="font-family:var(--font-mono); font-size:0.8rem;"><?= e($act['ip_address']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
