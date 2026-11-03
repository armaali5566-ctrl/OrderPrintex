<?php
/**
 * OrderPrintex - Order Details & Status Tracking Timeline
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) {
    header('Location: ' . account_url('orders'));
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, 'order-details.php') !== false) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . url('account/orders/' . $orderId . '/'));
    exit;
}

$order = db_fetch_one("
    SELECT o.*, q.quote_number, e.ref_number as enquiry_ref, e.service_name,
           ef.file_path as artwork_file_path, ef.original_name as artwork_original_name
    FROM orders o
    LEFT JOIN quotations q ON o.quotation_id = q.id
    LEFT JOIN enquiries e ON o.enquiry_id = e.id
    LEFT JOIN enquiry_files ef ON ef.enquiry_id = e.id
    WHERE o.id = :id AND (o.user_id = :uid OR o.customer_email = :email OR :is_admin = 1)
", [
    'id'       => $orderId,
    'uid'      => $user['id'],
    'email'    => $user['email'],
    'is_admin' => ($user['role'] === 'admin' ? 1 : 0)
]);

if (!$order) {
    set_flash('error', 'Order not found or unauthorized.');
    header('Location: ' . account_url('orders'));
    exit;
}

// Fetch status history
$statusHistory = db_fetch_all("
    SELECT * FROM order_status_history 
    WHERE order_id = :oid 
    ORDER BY created_at ASC
", ['oid' => $orderId]);

// Fetch artwork proofs if uploaded by studio
$proofs = db_fetch_all("
    SELECT * FROM artwork_proofs 
    WHERE order_id = :oid 
    ORDER BY version DESC
", ['oid' => $orderId]);

// Fetch quotation items for this order
$orderItems = [];
if ($order['quotation_id']) {
    $orderItems = db_fetch_all("
        SELECT * FROM quotation_items 
        WHERE quotation_id = :qid
    ", ['qid' => $order['quotation_id']]);
}

$pageTitle = "Order {$order['order_number']} Status Timeline | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0; min-height:85vh;">
  <div class="container">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; margin-bottom:2rem;">
      <div>
        <div class="section-label" style="color:#D4AF37;">Order Details &amp; Milestone Timeline</div>
        <h1 style="font-size:2.2rem; color:#F5F1E8;">
          Order <span class="ref-cell" style="color:#FFD76A;"><?= e($order['order_number']) ?></span>
        </h1>
        <p style="color:#B8B1A3; margin:0;">
          Placed on <?= format_date($order['created_at'], 'd M Y, h:i A') ?> &bull; Linked Quotation: <?= e($order['quote_number']) ?>
        </p>
      </div>

      <div style="display:flex; gap:0.75rem;">
        <a href="<?= account_url('orders') ?>" class="btn btn-outline">
          &larr; Back to Orders
        </a>
      </div>
    </div>

    <?= render_flash() ?>

    <div style="display:grid; grid-template-columns:1.8fr 1.2fr; gap:2rem; align-items:flex-start;">
      <!-- Left Column: Current Status Banner & Chronological Timeline -->
      <div>
        <!-- Current Status Highlight Card -->
        <div class="panel-card" style="border-left:4px solid #D4AF37; padding:1.75rem; margin-bottom:2rem; background:#181818; border:1px solid #40351E; border-left:4px solid #D4AF37;">
          <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
            <div>
              <span class="text-muted" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.08em; font-weight:700; color:#B8B1A3;">Current Order Stage</span>
              <div style="font-size:1.45rem; font-weight:800; color:#FFD76A; margin-top:0.25rem;">
                <?= ucfirst(str_replace('_', ' ', $order['order_status'])) ?>
              </div>
            </div>
            <div>
              <?= get_status_badge($order['order_status']) ?>
            </div>
          </div>

          <?php
            // Map order status to 1-6 timeline stage
            $currentStatus = strtolower($order['order_status']);
            $stageMap = [
              'pending'          => 1,
              'confirmed'        => 1,
              'order_placed'     => 1,
              'artwork_review'   => 2,
              'processing'       => 2,
              'prepress'         => 2,
              'in_production'    => 3,
              'printing'         => 3,
              'quality_check'    => 4,
              'qc'               => 4,
              'dispatched'       => 5,
              'shipped'          => 5,
              'out_for_delivery' => 5,
              'delivered'        => 6,
              'completed'        => 6
            ];
            $currentStageIndex = $stageMap[$currentStatus] ?? 1;

            // Extract stage timestamps from actual status history
            $stageTimestamps = [1 => $order['created_at']];
            foreach ($statusHistory as $h) {
                $st = strtolower($h['new_status']);
                if (isset($stageMap[$st])) {
                    $stageTimestamps[$stageMap[$st]] = $h['created_at'];
                }
            }

            $stages = [
              1 => ['num' => 1, 'label' => 'Order Placed'],
              2 => ['num' => 2, 'label' => 'Artwork Review'],
              3 => ['num' => 3, 'label' => 'Production'],
              4 => ['num' => 4, 'label' => 'Quality Check'],
              5 => ['num' => 5, 'label' => 'Dispatched'],
              6 => ['num' => 6, 'label' => 'Delivered']
            ];
          ?>

          <!-- Visual 6-Stage Progressive Timeline -->
          <div class="order-tracker-timeline">
            <?php foreach ($stages as $sIdx => $stg): 
              $isCompleted = ($sIdx < $currentStageIndex);
              $isCurrent = ($sIdx === $currentStageIndex);
              $stepClass = $isCompleted ? 'is-completed' : ($isCurrent ? 'is-current' : 'is-upcoming');
            ?>
              <div class="order-tracker-step <?= $stepClass ?>">
                <div class="tracker-dot">
                  <?= $isCompleted ? '✓' : $stg['num'] ?>
                </div>
                <div class="tracker-label"><?= e($stg['label']) ?></div>
                <?php if (!empty($stageTimestamps[$sIdx])): ?>
                  <div class="tracker-time"><?= format_date($stageTimestamps[$sIdx], 'd M, h:i A') ?></div>
                <?php else: ?>
                  <div class="tracker-time" style="opacity:0.4;">—</div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if ($order['tracking_number']): ?>
            <div style="padding:1rem; background-color:#111111; border-radius:6px; border:1px solid #40351E; font-size:0.88rem; margin-top:1.25rem;">
              <strong style="color:#D4AF37;">Courier Dispatch Details:</strong><br>
              Courier: <strong style="color:#F5F1E8;"><?= e($order['courier_name']) ?></strong> &bull; Tracking AWB: <strong style="color:#FFD76A; font-family:monospace;"><?= e($order['tracking_number']) ?></strong>
            </div>
          <?php endif; ?>
        </div>

        <!-- Milestone Status History Timeline -->
        <div class="panel-card">
          <div class="panel-header">
            <h3 class="panel-title">Production Progress Timeline</h3>
          </div>
          <div style="padding:2rem;">
            <div class="status-timeline">
              <?php if (empty($statusHistory)): ?>
                <div class="timeline-event">
                  <div class="timeline-dot"></div>
                  <div class="timeline-event-content">
                    <div class="timeline-event-title">Quotation Approved &amp; Order Created</div>
                    <div class="timeline-event-time"><?= format_date($order['created_at'], 'd M Y, h:i A') ?></div>
                    <div class="timeline-event-notes">Order registered in production queue.</div>
                  </div>
                </div>
              <?php else: ?>
                <?php foreach ($statusHistory as $hist): ?>
                  <div class="timeline-event">
                    <div class="timeline-dot"></div>
                    <div class="timeline-event-content">
                      <div class="timeline-event-title"><?= ucfirst(str_replace('_', ' ', $hist['new_status'])) ?></div>
                      <div class="timeline-event-time"><?= format_date($hist['created_at'], 'd M Y, h:i A') ?></div>
                      <?php if (!empty($hist['remarks'])): ?>
                        <div class="timeline-event-notes"><?= nl2br(e($hist['remarks'])) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Order Items Breakdown -->
        <div class="panel-card">
          <div class="panel-header">
            <h3 class="panel-title">Items in Production</h3>
          </div>
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Description</th>
                  <th>Dimensions</th>
                  <th style="text-align:center;">Qty</th>
                  <th style="text-align:right;">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($orderItems)): ?>
                  <tr>
                    <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--slate);">
                      <?= e($order['service_name'] ?: 'Custom Production Order') ?>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($orderItems as $it): ?>
                    <tr>
                      <td><strong><?= e($it['item_description']) ?></strong></td>
                      <td><?= e($it['dimensions'] ?: '—') ?></td>
                      <td style="text-align:center;"><?= (int)$it['quantity'] ?></td>
                      <td style="text-align:right; font-family:var(--font-mono); font-weight:700;">
                        <?= format_currency($it['total_price']) ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Right Column: Shipping Details, Payment, Artwork Proofs -->
      <div>
        <!-- Shipping Destination -->
        <div class="panel-card" style="padding:1.75rem; margin-bottom:2rem; background:#181818; border:1px solid #40351E;">
          <h4 style="font-size:1.1rem; margin-bottom:1rem; color:#FFD76A;">Shipping Address</h4>
          <div style="font-size:0.9rem; color:#F5F1E8; line-height:1.7;">
            <strong><?= e($order['customer_name']) ?></strong><br>
            <?= nl2br(e($order['shipping_address'])) ?><br>
            <?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?><br>
            Phone: <?= e($order['customer_phone']) ?>
          </div>
        </div>

        <!-- Payment & Total Card -->
        <div class="panel-card" style="padding:1.75rem; margin-bottom:2rem; background:#181818; border:1px solid #40351E;">
          <h4 style="font-size:1.1rem; margin-bottom:1rem; color:#FFD76A;">Payment Summary</h4>
          <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; font-size:0.9rem; color:#B8B1A3;">
            <span>Payment Status:</span>
            <span><?= get_status_badge($order['payment_status']) ?></span>
          </div>
          <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; font-size:0.9rem; color:#B8B1A3;">
            <span>Payment Method:</span>
            <strong style="color:#F5F1E8;"><?= e($order['payment_method']) ?></strong>
          </div>
          <div style="display:flex; justify-content:space-between; border-top:1px solid #40351E; padding-top:0.75rem; font-size:1.15rem; font-weight:700; color:#F5F1E8;">
            <span>Total Payable:</span>
            <span style="font-family:var(--font-mono); color:#FFD76A; font-weight:800;"><?= format_currency($order['total_amount']) ?></span>
          </div>
        </div>

        <!-- Uploaded Artwork Card -->
        <div class="panel-card" style="padding:1.75rem; background:#181818; border:1px solid #40351E;">
          <h4 style="font-size:1.1rem; margin-bottom:1rem; color:#FFD76A;">Submitted Artwork</h4>
          <?php if ($order['artwork_file_path']): ?>
            <div style="display:flex; align-items:center; gap:0.75rem; background-color:#111111; padding:0.85rem 1rem; border-radius:6px; border:1px solid #40351E;">
              <svg style="width:24px;height:24px;fill:var(--gold);" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
              </svg>
              <div style="overflow:hidden; flex-grow:1;">
                <div style="font-size:0.85rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#F5F1E8;">
                  <?= e($order['artwork_original_name']) ?>
                </div>
              </div>
              <a href="<?= url(e($order['artwork_file_path'])) ?>" target="_blank" class="btn btn-sm btn-outline" style="border-color:#40351E; color:#D4AF37;" download>
                Download
              </a>
            </div>
          <?php else: ?>
            <p style="font-size:0.88rem; color:var(--slate); margin:0;">No direct file attached.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
