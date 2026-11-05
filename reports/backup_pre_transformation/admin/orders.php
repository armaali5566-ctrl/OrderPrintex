<?php
/**
 * OrderPrintex - Admin Order Management
 */

$adminTitle = "Manage Production Orders";
require_once __DIR__ . '/header.php';

// Handle Order Status & Tracking Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/orders.php'));
        exit;
    }

    $orderId = intval($_POST['order_id'] ?? 0);
    $newStatus = trim($_POST['order_status'] ?? '');
    $paymentStatus = trim($_POST['payment_status'] ?? 'pending');
    $courier = trim($_POST['courier_name'] ?? '');
    $tracking = trim($_POST['tracking_number'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    $internalNotes = trim($_POST['internal_notes'] ?? '');

    $currentOrder = db_fetch_one("SELECT * FROM orders WHERE id = :id", ['id' => $orderId]);
    if ($currentOrder) {
        $prevStatus = $currentOrder['order_status'];

        db_update('orders', [
            'order_status'    => $newStatus,
            'payment_status'  => $paymentStatus,
            'courier_name'    => $courier,
            'tracking_number' => $tracking,
            'internal_notes'  => $internalNotes
        ], 'id = :id', ['id' => $orderId]);

        // If status changed or remarks provided, add to history
        if ($prevStatus !== $newStatus || !empty($remarks)) {
            db_insert('order_status_history', [
                'order_id'           => $orderId,
                'previous_status'    => $prevStatus,
                'new_status'         => $newStatus,
                'remarks'            => $remarks ?: 'Status updated by administrator to ' . ucfirst(str_replace('_', ' ', $newStatus)),
                'changed_by_user_id' => $adminUser['id']
            ]);
        }

        log_activity($adminUser['id'], 'update_order', "Updated order {$currentOrder['order_number']} to {$newStatus}");
        set_flash('success', "Order {$currentOrder['order_number']} updated successfully.");
    }
    header('Location: ' . url('admin/orders.php?order_id=' . $orderId));
    exit;
}

// Filters & Search
$statusFilter = trim($_GET['status'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');
$selectedOrderId = intval($_GET['order_id'] ?? 0);

$sql = "SELECT o.*, q.quote_number, e.service_name, e.ref_number as enquiry_ref,
               ef.file_path, ef.original_name
        FROM orders o
        LEFT JOIN quotations q ON o.quotation_id = q.id
        LEFT JOIN enquiries e ON o.enquiry_id = e.id
        LEFT JOIN enquiry_files ef ON ef.enquiry_id = e.id
        WHERE 1=1";
$params = [];

if ($statusFilter) {
    $sql .= " AND o.order_status = :status";
    $params['status'] = $statusFilter;
}

if ($searchTerm) {
    $sql .= " AND (o.order_number LIKE :s OR o.customer_name LIKE :s OR o.customer_email LIKE :s OR o.tracking_number LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY o.created_at DESC";
$orders = db_fetch_all($sql, $params);

// If an order is selected for editing
$activeOrder = null;
$activeHistory = [];
$activeItems = [];
if ($selectedOrderId) {
    $activeOrder = db_fetch_one("
        SELECT o.*, q.quote_number, e.service_name, e.customer_notes as enquiry_notes,
               ef.file_path, ef.original_name
        FROM orders o
        LEFT JOIN quotations q ON o.quotation_id = q.id
        LEFT JOIN enquiries e ON o.enquiry_id = e.id
        LEFT JOIN enquiry_files ef ON ef.enquiry_id = e.id
        WHERE o.id = :id
    ", ['id' => $selectedOrderId]);

    if ($activeOrder) {
        $activeHistory = db_fetch_all("
            SELECT * FROM order_status_history 
            WHERE order_id = :oid 
            ORDER BY created_at ASC
        ", ['oid' => $selectedOrderId]);

        if ($activeOrder['quotation_id']) {
            $activeItems = db_fetch_all("
                SELECT * FROM quotation_items 
                WHERE quotation_id = :qid
            ", ['qid' => $activeOrder['quotation_id']]);
        }
    }
}
?>

<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.75rem; color:var(--navy);">Order Management</h1>
    <p style="color:var(--slate); margin:0; font-size:0.9rem;">
      Track live customer production jobs, update dispatch courier details, and log stage milestones.
    </p>
  </div>

  <div class="toolbar-form">
    <form action="<?= url('admin/orders.php') ?>" method="GET" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
      <select name="status" class="form-control" style="width:190px;" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="quotation_approved" <?= $statusFilter === 'quotation_approved' ? 'selected' : '' ?>>Quotation Approved</option>
        <option value="payment_pending" <?= $statusFilter === 'payment_pending' ? 'selected' : '' ?>>Payment Pending</option>
        <option value="artwork_review" <?= $statusFilter === 'artwork_review' ? 'selected' : '' ?>>Artwork Review</option>
        <option value="proof_approval" <?= $statusFilter === 'proof_approval' ? 'selected' : '' ?>>Proof Approval</option>
        <option value="production" <?= $statusFilter === 'production' ? 'selected' : '' ?>>In Production</option>
        <option value="quality_check" <?= $statusFilter === 'quality_check' ? 'selected' : '' ?>>Quality Check</option>
        <option value="dispatched" <?= $statusFilter === 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
        <option value="delivered" <?= $statusFilter === 'delivered' ? 'selected' : '' ?>>Delivered</option>
        <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>

      <div class="search-input-wrap">
        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
        <input type="text" name="search" value="<?= e($searchTerm) ?>" class="form-control" placeholder="Search order #, customer, AWB...">
      </div>
      <button type="submit" class="btn btn-sm btn-navy">Filter</button>
      <?php if ($statusFilter || $searchTerm): ?>
        <a href="<?= url('admin/orders.php') ?>" class="btn btn-sm btn-outline">Reset</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php if ($activeOrder): ?>
  <!-- Edit / Manage Single Order View -->
  <div style="display:grid; grid-template-columns:1.6fr 1.4fr; gap:2rem; align-items:flex-start; margin-bottom:2rem;">
    <!-- Order Details & Timeline -->
    <div class="panel-card" style="margin-bottom:0;">
      <div class="panel-header">
        <div>
          <span class="ref-cell" style="font-size:1.15rem;"><?= e($activeOrder['order_number']) ?></span>
          <span style="margin-left:0.5rem;"><?= get_status_badge($activeOrder['order_status']) ?></span>
        </div>
        <a href="<?= url('admin/orders.php') ?>" class="btn btn-sm btn-outline">Close Editor</a>
      </div>

      <div style="padding:1.5rem; border-bottom:1px solid var(--border-light); font-size:0.88rem; line-height:1.7;">
        <strong>Customer:</strong> <?= e($activeOrder['customer_name']) ?> (<?= e($activeOrder['customer_email']) ?> &bull; <?= e($activeOrder['customer_phone']) ?>)<br>
        <strong>Shipping:</strong> <?= nl2br(e($activeOrder['shipping_address'])) ?>, <?= e($activeOrder['city']) ?>, <?= e($activeOrder['state']) ?> - <?= e($activeOrder['pincode']) ?><br>
        <strong>Total Amount:</strong> <span style="font-family:var(--font-mono); font-weight:700; color:var(--navy);"><?= format_currency($activeOrder['total_amount']) ?></span> &bull; 
        Payment: <?= get_status_badge($activeOrder['payment_status']) ?><br>
        <strong>Artwork File:</strong> 
        <?php if ($activeOrder['file_path']): ?>
          <a href="<?= url(e($activeOrder['file_path'])) ?>" target="_blank" class="text-blue" download style="font-weight:600;">
            <?= e($activeOrder['original_name']) ?> (Download)
          </a>
        <?php else: ?>
          <span class="text-muted">None attached</span>
        <?php endif; ?>
      </div>

      <!-- Timeline in Editor -->
      <div style="padding:1.5rem;">
        <h4 style="font-size:1rem; margin-bottom:1rem; color:var(--navy);">Status Milestone Log</h4>
        <div class="status-timeline">
          <?php foreach ($activeHistory as $hist): ?>
            <div class="timeline-event">
              <div class="timeline-dot"></div>
              <div class="timeline-event-content">
                <div class="timeline-event-title"><?= ucfirst(str_replace('_', ' ', $hist['new_status'])) ?></div>
                <div class="timeline-event-time"><?= format_date($hist['created_at'], 'd M Y, h:i A') ?></div>
                <div class="timeline-event-notes"><?= nl2br(e($hist['remarks'])) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Status & Tracking Update Form -->
    <div class="upload-card">
      <h3 style="font-size:1.2rem; margin-bottom:1.25rem;">Update Order Status &amp; Tracking</h3>

      <form action="<?= url('admin/orders.php') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_order">
        <input type="hidden" name="order_id" value="<?= e($activeOrder['id']) ?>">

        <div class="form-group" style="margin-bottom:1rem;">
          <label class="form-label">Production Order Status</label>
          <select name="order_status" class="form-control" required>
            <option value="quotation_approved" <?= $activeOrder['order_status'] === 'quotation_approved' ? 'selected' : '' ?>>Quotation Approved</option>
            <option value="payment_pending" <?= $activeOrder['order_status'] === 'payment_pending' ? 'selected' : '' ?>>Payment Pending</option>
            <option value="artwork_review" <?= $activeOrder['order_status'] === 'artwork_review' ? 'selected' : '' ?>>Artwork Review</option>
            <option value="proof_approval" <?= $activeOrder['order_status'] === 'proof_approval' ? 'selected' : '' ?>>Proof Approval (Client Review)</option>
            <option value="production" <?= $activeOrder['order_status'] === 'production' ? 'selected' : '' ?>>In Production</option>
            <option value="quality_check" <?= $activeOrder['order_status'] === 'quality_check' ? 'selected' : '' ?>>Quality Check &amp; Finishing</option>
            <option value="dispatched" <?= $activeOrder['order_status'] === 'dispatched' ? 'selected' : '' ?>>Dispatched (In Transit)</option>
            <option value="delivered" <?= $activeOrder['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="cancelled" <?= $activeOrder['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
          </select>
        </div>

        <div class="form-group" style="margin-bottom:1rem;">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-control" required>
            <option value="pending" <?= $activeOrder['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="partial" <?= $activeOrder['payment_status'] === 'partial' ? 'selected' : '' ?>>Partially Paid</option>
            <option value="paid" <?= $activeOrder['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid in Full</option>
            <option value="refunded" <?= $activeOrder['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
          </select>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
          <div class="form-group">
            <label class="form-label">Courier Name</label>
            <input type="text" name="courier_name" class="form-control" value="<?= e($activeOrder['courier_name']) ?>" placeholder="e.g. DTDC / Delhivery / Bluedart">
          </div>
          <div class="form-group">
            <label class="form-label">Tracking / AWB #</label>
            <input type="text" name="tracking_number" class="form-control" value="<?= e($activeOrder['tracking_number']) ?>" placeholder="e.g. 7482910394">
          </div>
        </div>

        <div class="form-group" style="margin-bottom:1rem;">
          <label class="form-label">Customer-Visible Milestone Remark</label>
          <input type="text" name="remarks" class="form-control" placeholder="e.g. DTF gang roll printed and cured. Transferred to QC.">
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
          <label class="form-label">Internal Studio Notes</label>
          <textarea name="internal_notes" class="form-control" placeholder="Private internal notes..."><?= e($activeOrder['internal_notes']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
          <span>Save Changes &amp; Log Milestone</span>
        </button>
      </form>
    </div>
  </div>
<?php endif; ?>

<!-- Orders Table -->
<div class="panel-card">
  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Date</th>
          <th>Customer</th>
          <th>Service</th>
          <th>Total</th>
          <th>Payment</th>
          <th>Production Status</th>
          <th>Tracking</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="9" style="text-align:center; padding:3rem; color:var(--slate);">
              No orders found matching your filter criteria.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $ord): ?>
            <tr>
              <td class="ref-cell"><?= e($ord['order_number']) ?></td>
              <td><?= format_date($ord['created_at'], 'd M Y') ?></td>
              <td>
                <strong><?= e($ord['customer_name']) ?></strong><br>
                <small style="color:var(--slate);"><?= e($ord['customer_email']) ?></small>
              </td>
              <td><?= e($ord['service_name'] ?: 'Custom Print') ?></td>
              <td style="font-weight:700; color:var(--navy); font-family:var(--font-mono);">
                <?= format_currency($ord['total_amount']) ?>
              </td>
              <td><?= get_status_badge($ord['payment_status']) ?></td>
              <td><?= get_status_badge($ord['order_status']) ?></td>
              <td>
                <?php if ($ord['tracking_number']): ?>
                  <span style="font-family:var(--font-mono); font-size:0.8rem;"><?= e($ord['courier_name']) ?>: <?= e($ord['tracking_number']) ?></span>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= url('admin/orders.php?order_id=' . $ord['id']) ?>" class="btn btn-sm btn-navy">
                  Edit &amp; Status
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
