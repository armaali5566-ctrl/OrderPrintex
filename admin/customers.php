<?php
/**
 * OrderPrintex - Admin Customer Management
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_panel();
$adminUser = auth_user();

// Handle Customer Actions (Toggle Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/customers.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'toggle_status') {
        $userId = intval($_POST['user_id'] ?? 0);
        $user = db_fetch_one("SELECT * FROM users WHERE id = :id AND role = 'customer'", ['id' => $userId]);
        if ($user) {
            $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';
            db_update('users', ['status' => $newStatus], 'id = :id', ['id' => $userId]);
            log_activity($adminUser['id'], 'toggle_customer_status', "Changed status of {$user['email']} to {$newStatus}");
            set_flash('success', "Customer status changed to " . ucfirst($newStatus));
        }
        header('Location: ' . url('admin/customers.php' . ($userId ? '?customer_id=' . $userId : '')));
        exit;
    }
}

// Search and Selection
$searchTerm = trim($_GET['search'] ?? '');
$selectedCustomerId = intval($_GET['customer_id'] ?? 0);

$sql = "SELECT u.*,
               (SELECT COUNT(*) FROM enquiries e WHERE e.user_id = u.id OR e.customer_email = u.email) as enquiries_count,
               (SELECT COUNT(*) FROM quotations q WHERE q.user_id = u.id OR q.customer_email = u.email) as quotations_count,
               (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id OR o.customer_email = u.email) as orders_count,
               (SELECT COALESCE(SUM(o.total_amount), 0) FROM orders o WHERE (o.user_id = u.id OR o.customer_email = u.email) AND o.order_status != 'cancelled') as total_spend
        FROM users u
        WHERE u.role = 'customer'";
$params = [];

if ($searchTerm) {
    $sql .= " AND (u.name LIKE :s OR u.email LIKE :s OR u.phone LIKE :s OR u.company_name LIKE :s OR u.city LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY u.created_at DESC";
$customers = db_fetch_all($sql, $params);

// Selected Customer Detailed Breakdown
$activeCustomer = null;
$customerEnquiries = [];
$customerQuotations = [];
$customerOrders = [];

if ($selectedCustomerId) {
    $activeCustomer = db_fetch_one("SELECT * FROM users WHERE id = :id AND role = 'customer'", ['id' => $selectedCustomerId]);
    if ($activeCustomer) {
        $cEmail = $activeCustomer['email'];
        $cId = $activeCustomer['id'];

        $customerEnquiries = db_fetch_all("
            SELECT * FROM enquiries 
            WHERE user_id = :uid OR customer_email = :email 
            ORDER BY created_at DESC LIMIT 10
        ", ['uid' => $cId, 'email' => $cEmail]);

        $customerQuotations = db_fetch_all("
            SELECT * FROM quotations 
            WHERE user_id = :uid OR customer_email = :email 
            ORDER BY created_at DESC LIMIT 10
        ", ['uid' => $cId, 'email' => $cEmail]);

        $customerOrders = db_fetch_all("
            SELECT * FROM orders 
            WHERE user_id = :uid OR customer_email = :email 
            ORDER BY created_at DESC LIMIT 10
        ", ['uid' => $cId, 'email' => $cEmail]);
    }
}

$adminTitle = "Manage Registered Customers";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Registered Clients</h1>
        <p class="admin-page-subtitle">Inspect client accounts, view historical orders, quotations, and artwork requests.</p>
    </div>
</div>

<div class="admin-filter-bar">
    <div>
        <span style="font-size: 0.9rem; font-weight: 600; color: var(--admin-text-main);">
            Total Registered Customers: <?= count($customers) ?>
        </span>
    </div>

    <form method="GET" action="<?= url('admin/customers.php') ?>" class="admin-search-form">
        <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search name, email, phone, company..." class="form-input form-input-sm" style="min-width: 0; width: 280px; max-width: 100%;">
        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
        <?php if ($searchTerm): ?>
            <a href="<?= url('admin/customers.php') ?>" class="btn btn-outline btn-sm">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="<?= $activeCustomer ? 'admin-split-grid' : '' ?>">
    
    <!-- Customers Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Enquiries</th>
                        <th>Quotes</th>
                        <th>Orders</th>
                        <th>Total Spend</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No registered customer records found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $c): ?>
                            <tr class="<?= ($activeCustomer && $activeCustomer['id'] === $c['id']) ? 'table-row-selected' : '' ?>">
                                <td>
                                    <strong style="color: var(--color-navy);"><?= e($c['name']) ?></strong>
                                    <?php if ($c['company_name']): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-gold); font-weight: 500;"><?= e($c['company_name']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem;"><?= e($c['email']) ?></div>
                                    <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($c['phone'] ?: '—') ?></div>
                                </td>
                                <td style="font-size: 0.8rem;">
                                    <?= e($c['city'] ? $c['city'] . ', ' . $c['state'] : '—') ?>
                                </td>
                                <td style="font-weight: 600; text-align: center;"><?= $c['enquiries_count'] ?></td>
                                <td style="font-weight: 600; text-align: center;"><?= $c['quotations_count'] ?></td>
                                <td style="font-weight: 600; text-align: center;"><?= $c['orders_count'] ?></td>
                                <td style="font-weight: 600; color: var(--color-navy);"><?= format_currency($c['total_spend']) ?></td>
                                <td>
                                    <span class="badge <?= $c['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst($c['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= url('admin/customers.php?customer_id=' . $c['id']) ?>" class="btn btn-outline btn-xs">
                                        Profile &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Customer Profile & History Pane -->
    <?php if ($activeCustomer): ?>
        <div class="admin-card" style="border: 2px solid var(--color-gold);">
            <div class="admin-card-header" style="background: var(--color-ivory); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="admin-card-title" style="margin-bottom: 2px;"><?= e($activeCustomer['name']) ?></h2>
                    <span style="font-size: 0.8rem; color: var(--color-text-muted);">Registered <?= date('d M Y', strtotime($activeCustomer['created_at'])) ?></span>
                </div>
                <a href="<?= url('admin/customers.php') ?>" style="color: var(--color-text-muted); font-size: 1.2rem; text-decoration: none;">&times;</a>
            </div>

            <div class="admin-card-body" style="padding: 20px;">
                <!-- Profile Summary -->
                <div style="margin-bottom: 20px;">
                    <div class="admin-form-2col" style="gap: 12px; font-size: 0.85rem;">
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Email:</span>
                            <a href="mailto:<?= e($activeCustomer['email']) ?>" style="color: var(--color-royal); word-break: break-all;"><?= e($activeCustomer['email']) ?></a>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Phone:</span>
                            <strong><?= e($activeCustomer['phone'] ?: 'N/A') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Company:</span>
                            <strong><?= e($activeCustomer['company_name'] ?: 'Individual') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--color-text-muted); display: block; font-size: 0.75rem;">Account Status:</span>
                            <form method="POST" action="<?= url('admin/customers.php') ?>" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="user_id" value="<?= $activeCustomer['id'] ?>">
                                <button type="submit" class="btn btn-xs <?= $activeCustomer['status'] === 'active' ? 'btn-outline' : 'btn-secondary' ?>">
                                    <?= $activeCustomer['status'] === 'active' ? 'Active (Click to Suspend)' : 'Suspended (Click to Activate)' ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if (!empty($activeCustomer['address'])): ?>
                        <div style="margin-top: 12px; background: var(--admin-bg-card-secondary, #171C2B); padding: 10px; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--color-border);">
                            <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block;">Default Shipping Address:</span>
                            <?= e($activeCustomer['address']) ?>, <?= e($activeCustomer['city']) ?>, <?= e($activeCustomer['state']) ?> - <?= e($activeCustomer['pincode']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Orders Tab -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 10px;">
                        Orders (<?= count($customerOrders) ?>)
                    </h3>
                    <?php if (empty($customerOrders)): ?>
                        <p style="font-size: 0.8rem; color: var(--color-text-muted);">No orders placed yet.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($customerOrders as $ord): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--admin-bg-card-secondary, #171C2B); border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
                                    <div>
                                        <a href="<?= url('admin/orders.php?order_id=' . $ord['id']) ?>" style="font-weight: 700; color: var(--color-royal);">
                                            <?= e($ord['order_number']) ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= date('d M Y', strtotime($ord['created_at'])) ?></div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-weight: 600;"><?= format_currency($ord['total_amount']) ?></div>
                                        <?= status_badge($ord['order_status']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quotations Tab -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 10px;">
                        Quotations (<?= count($customerQuotations) ?>)
                    </h3>
                    <?php if (empty($customerQuotations)): ?>
                        <p style="font-size: 0.8rem; color: var(--color-text-muted);">No quotations generated yet.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($customerQuotations as $q): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--admin-bg-card-secondary, #171C2B); border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
                                    <div>
                                        <a href="<?= url('admin/quotations.php?quote_id=' . $q['id']) ?>" style="font-weight: 700; color: var(--color-royal);">
                                            <?= e($q['quote_number']) ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= date('d M Y', strtotime($q['created_at'])) ?></div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-weight: 600;"><?= format_currency($q['grand_total']) ?></div>
                                        <?= status_badge($q['status']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Enquiries Tab -->
                <div>
                    <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--color-navy); border-bottom: 1px solid var(--color-border); padding-bottom: 6px; margin-bottom: 10px;">
                        Artwork Enquiries (<?= count($customerEnquiries) ?>)
                    </h3>
                    <?php if (empty($customerEnquiries)): ?>
                        <p style="font-size: 0.8rem; color: var(--color-text-muted);">No enquiries submitted.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($customerEnquiries as $enq): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--admin-bg-card-secondary, #171C2B); border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
                                    <div>
                                        <a href="<?= url('admin/enquiries.php?enquiry_id=' . $enq['id']) ?>" style="font-weight: 700; color: var(--color-royal);">
                                            <?= e($enq['ref_number']) ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= e($enq['service_name'] ?: 'Print') ?></div>
                                    </div>
                                    <div>
                                        <?= status_badge($enq['status']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
