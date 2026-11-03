<?php
/**
 * OrderPrintex - Admin Master Dashboard
 * Fully Database-Driven Real Metrics & Fast Action Center
 */

$adminTitle = "Dashboard Overview";
require_once __DIR__ . '/header.php';

// Actual Database-Driven Metrics
$totalProducts    = db_fetch_one("SELECT COUNT(*) as cnt FROM products")['cnt'] ?? 0;
$totalVariations  = db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations")['cnt'] ?? 0;
$totalOrders      = db_fetch_one("SELECT COUNT(*) as cnt FROM orders")['cnt'] ?? 0;
$activeOrders     = db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE order_status NOT IN ('delivered', 'cancelled')")['cnt'] ?? 0;
$totalRevenue     = db_fetch_one("SELECT SUM(total_amount) as rev FROM orders WHERE payment_status = 'paid'")['rev'] ?? 0.00;
$totalEnquiries   = db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries")['cnt'] ?? 0;
$totalReviews     = db_fetch_one("SELECT COUNT(*) as cnt FROM product_reviews")['cnt'] ?? 0;
$totalBlogs       = db_fetch_one("SELECT COUNT(*) as cnt FROM blog_posts")['cnt'] ?? 0;

// Recent Enquiries
$recentEnquiries = db_fetch_all("
    SELECT e.*
    FROM enquiries e 
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

<!-- Quick Action Shortcuts Bar -->
<div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem 1.5rem; margin-bottom:1.75rem; box-shadow: var(--shadow-card); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
  <div>
    <h3 style="font-size:1.05rem; color:var(--navy); font-weight:800; margin:0 0 0.25rem;">Quick Management Actions</h3>
    <p style="margin:0; font-size:0.825rem; color:var(--slate);">Shortcuts to catalog updates, variation generator, media uploads, and website settings.</p>
  </div>
  <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
    <a href="<?= url('admin/products.php?action=new') ?>" class="btn btn-sm btn-primary">+ Add Product</a>
    <a href="<?= url('admin/variations.php') ?>" class="btn btn-sm btn-outline">⇄ Variations Workbench</a>
    <a href="<?= url('admin/attributes.php') ?>" class="btn btn-sm btn-outline">⚙ Attributes</a>
    <a href="<?= url('admin/media.php') ?>" class="btn btn-sm btn-outline">📁 Media Library</a>
    <a href="<?= url('admin/settings.php') ?>" class="btn btn-sm btn-navy">⚙ Site Settings</a>
  </div>
</div>

<!-- Metrics Summary Grid -->
<div class="metrics-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:1.25rem; margin-bottom:2rem;">
  
  <div class="metric-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08); display:flex; justify-content:space-between; align-items:center;">
    <div>
      <div class="metric-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Products Catalog</div>
      <div class="metric-value" style="font-size:1.8rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= (int)$totalProducts ?></div>
      <div style="font-size:0.75rem; color:var(--royal); font-weight:600; margin-top:2px;">
        <a href="<?= url('admin/products.php') ?>" style="color:var(--royal); text-decoration:none;">View all products &rarr;</a>
      </div>
    </div>
    <div style="font-size:2rem; color:var(--royal);">📦</div>
  </div>

  <div class="metric-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08); display:flex; justify-content:space-between; align-items:center;">
    <div>
      <div class="metric-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Active SKU Variations</div>
      <div class="metric-value" style="font-size:1.8rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= (int)$totalVariations ?></div>
      <div style="font-size:0.75rem; color:var(--royal); font-weight:600; margin-top:2px;">
        <a href="<?= url('admin/variations.php') ?>" style="color:var(--royal); text-decoration:none;">Manage variations &rarr;</a>
      </div>
    </div>
    <div style="font-size:2rem; color:var(--gold);">⇄</div>
  </div>

  <div class="metric-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08); display:flex; justify-content:space-between; align-items:center;">
    <div>
      <div class="metric-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Orders Placed</div>
      <div class="metric-value" style="font-size:1.8rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= (int)$totalOrders ?></div>
      <div style="font-size:0.75rem; color:#059669; font-weight:600; margin-top:2px;">
        <?= (int)$activeOrders ?> in production
      </div>
    </div>
    <div style="font-size:2rem; color:#059669;">🛍</div>
  </div>

  <div class="metric-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08); display:flex; justify-content:space-between; align-items:center;">
    <div>
      <div class="metric-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Customer Inquiries</div>
      <div class="metric-value" style="font-size:1.8rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= (int)$totalEnquiries ?></div>
      <div style="font-size:0.75rem; color:var(--royal); font-weight:600; margin-top:2px;">
        <a href="<?= url('admin/enquiries.php') ?>" style="color:var(--royal); text-decoration:none;">View inquiries &rarr;</a>
      </div>
    </div>
    <div style="font-size:2rem; color:var(--royal);">✉</div>
  </div>

  <div class="metric-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08); display:flex; justify-content:space-between; align-items:center;">
    <div>
      <div class="metric-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Customer Reviews</div>
      <div class="metric-value" style="font-size:1.8rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= (int)$totalReviews ?></div>
      <div style="font-size:0.75rem; color:var(--gold); font-weight:600; margin-top:2px;">
        <a href="<?= url('admin/reviews.php') ?>" style="color:var(--navy); text-decoration:none;">Moderate reviews &rarr;</a>
      </div>
    </div>
    <div style="font-size:2rem; color:#F59E0B;">★</div>
  </div>

</div>

<!-- Two-Column Grid: Recent Enquiries & Recent Orders -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:2rem;">
  
  <!-- Recent Enquiries -->
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
      <h3 style="font-size:1.1rem; color:var(--navy); font-weight:800; margin:0;">Latest Customer Enquiries</h3>
      <a href="<?= url('admin/enquiries.php') ?>" style="font-size:0.8rem; font-weight:700; color:var(--royal); text-decoration:none;">View All &rarr;</a>
    </div>
    <div class="table-responsive">
      <table class="data-table" style="width:100%; font-size:0.85rem;">
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
                <td style="font-weight:700; color:var(--navy);"><?= e($enq['ref_number']) ?></td>
                <td>
                  <strong><?= e($enq['customer_name']) ?></strong><br>
                  <small style="color:var(--slate);"><?= e($enq['customer_phone']) ?></small>
                </td>
                <td><?= e($enq['service_name']) ?></td>
                <td>
                  <?php if (!empty($enq['file_path'])): ?>
                    <a href="<?= url(e($enq['file_path'])) ?>" target="_blank" download style="color:var(--royal); font-weight:700; font-size:0.75rem;">
                      Download File
                    </a>
                  <?php else: ?>
                    <span style="color:var(--text-muted); font-size:0.75rem;">None</span>
                  <?php endif; ?>
                </td>
                <td><?= get_status_badge($enq['status']) ?></td>
                <td>
                  <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $enq['id']) ?>" class="btn btn-xs btn-primary">
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
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
      <h3 style="font-size:1.1rem; color:var(--navy); font-weight:800; margin:0;">Active Production Orders</h3>
      <a href="<?= url('admin/orders.php') ?>" style="font-size:0.8rem; font-weight:700; color:var(--royal); text-decoration:none;">View All &rarr;</a>
    </div>
    <div class="table-responsive">
      <table class="data-table" style="width:100%; font-size:0.85rem;">
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
                <td style="font-weight:700; color:var(--navy);"><?= e($ord['order_number']) ?></td>
                <td>
                  <strong><?= e($ord['customer_name']) ?></strong><br>
                  <small style="color:var(--slate);"><?= e($ord['city']) ?></small>
                </td>
                <td style="font-weight:700; color:var(--navy);">
                  <?= format_currency($ord['total_amount']) ?>
                </td>
                <td><?= get_status_badge($ord['order_status']) ?></td>
                <td>
                  <a href="<?= url('admin/orders.php?order_id=' . $ord['id']) ?>" class="btn btn-xs btn-outline">
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
<div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.08);">
  <div style="margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
    <h3 style="font-size:1.1rem; color:var(--navy); font-weight:800; margin:0;">Security &amp; Production Activity Log</h3>
  </div>
  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.85rem;">
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
              <td style="color:var(--text-muted); font-size:0.8rem;"><?= format_date($act['created_at'], 'd M Y, h:i A') ?></td>
              <td><?= e($act['user_name'] ?: ($act['user_email'] ?: 'System Guest')) ?></td>
              <td><span class="badge" style="background:rgba(20,35,60,0.06); color:var(--navy); font-weight:700;"><?= e($act['action']) ?></span></td>
              <td style="font-size:0.825rem;"><?= e($act['details'] ?: '—') ?></td>
              <td style="color:var(--text-muted); font-size:0.8rem; font-family:monospace;"><?= e($act['ip_address']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
