<?php
/**
 * OrderPrintex - Admin Product Reviews Moderation
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();
$adminUser = auth_user();

// Handle Moderation Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/reviews.php'));
        exit;
    }

    $action = $_POST['action'];
    $reviewId = intval($_POST['review_id'] ?? 0);

    if ($action === 'approve' && $reviewId > 0) {
        db_update('product_reviews', ['status' => 'approved'], 'id = :id', ['id' => $reviewId]);
        log_activity($adminUser['id'], 'approve_review', "Approved review #{$reviewId}");
        set_flash('success', "Review #{$reviewId} approved successfully.");
    } elseif ($action === 'reject' && $reviewId > 0) {
        db_update('product_reviews', ['status' => 'rejected'], 'id = :id', ['id' => $reviewId]);
        log_activity($adminUser['id'], 'reject_review', "Rejected review #{$reviewId}");
        set_flash('success', "Review #{$reviewId} rejected.");
    } elseif ($action === 'delete' && $reviewId > 0) {
        db_delete('product_reviews', 'id = :id', ['id' => $reviewId]);
        log_activity($adminUser['id'], 'delete_review', "Deleted review #{$reviewId}");
        set_flash('success', "Review #{$reviewId} deleted.");
    } elseif ($action === 'toggle_verified' && $reviewId > 0) {
        $rev = db_fetch_one("SELECT is_verified_purchase FROM product_reviews WHERE id = :id", ['id' => $reviewId]);
        if ($rev) {
            $newVal = $rev['is_verified_purchase'] ? 0 : 1;
            db_update('product_reviews', ['is_verified_purchase' => $newVal], 'id = :id', ['id' => $reviewId]);
            set_flash('success', "Review verification status updated.");
        }
    } elseif ($action === 'add_manual_review') {
        $productId = intval($_POST['product_id'] ?? 0);
        $customerName = trim($_POST['customer_name'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? '');
        $rating = max(1, min(5, intval($_POST['rating'] ?? 5)));
        $title = trim($_POST['title'] ?? '');
        $reviewText = trim($_POST['review_text'] ?? '');
        $isVerified = isset($_POST['is_verified']) ? 1 : 0;
        $status = in_array($_POST['status'] ?? '', ['approved', 'pending'], true) ? $_POST['status'] : 'approved';

        if ($productId > 0 && !empty($customerName) && !empty($reviewText)) {
            db_insert('product_reviews', [
                'product_id'           => $productId,
                'customer_name'        => $customerName,
                'customer_email'       => $customerEmail,
                'rating'               => $rating,
                'title'                => $title ?: 'Verified Buyer Review',
                'review_text'          => $reviewText,
                'is_verified_purchase' => $isVerified,
                'status'               => $status,
                'created_at'           => date('Y-m-d H:i:s')
            ]);
            log_activity($adminUser['id'], 'create_review', "Added manual customer review for product #{$productId}");
            set_flash('success', "Customer review added successfully.");
        } else {
            set_flash('error', "Please select a product and provide reviewer name and review text.");
        }
    }

    header('Location: ' . url('admin/reviews.php'));
    exit;
}

// Filters & Query
$statusFilter = trim($_GET['status'] ?? '');
$ratingFilter = intval($_GET['rating'] ?? 0);
$searchTerm = trim($_GET['search'] ?? '');

$sql = "SELECT r.*, p.title as product_title, p.slug as product_slug, p.sku as product_sku
        FROM product_reviews r
        LEFT JOIN products p ON r.product_id = p.id
        WHERE 1=1";
$params = [];

if ($statusFilter !== '') {
    $sql .= " AND r.status = :st";
    $params['st'] = $statusFilter;
}

if ($ratingFilter > 0) {
    $sql .= " AND r.rating = :rf";
    $params['rf'] = $ratingFilter;
}

if ($searchTerm !== '') {
    $sql .= " AND (r.customer_name LIKE :s OR r.title LIKE :s OR r.review_text LIKE :s OR p.title LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY r.created_at DESC";
$reviews = db_fetch_all($sql, $params);

// Products for manual review dropdown
$allProducts = db_fetch_all("SELECT id, title, sku FROM products ORDER BY title ASC");

// Counts
$countAll = count(db_fetch_all("SELECT id FROM product_reviews"));
$countApproved = count(db_fetch_all("SELECT id FROM product_reviews WHERE status = 'approved'"));
$countPending = count(db_fetch_all("SELECT id FROM product_reviews WHERE status = 'pending'"));
$countRejected = count(db_fetch_all("SELECT id FROM product_reviews WHERE status = 'rejected'"));

$adminTitle = "Moderate Product Reviews";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Product Reviews Moderation</h1>
        <p class="admin-page-subtitle">Approve, reject, or manage authentic customer reviews and ratings for your products.</p>
    </div>
    <div class="admin-topbar-actions">
        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('manualReviewCard').style.display = (document.getElementById('manualReviewCard').style.display === 'none' ? 'block' : 'none');">
            + Add Review Manually
        </button>
    </div>
</div>

<!-- Add Manual Review Card (Collapsible) -->
<div id="manualReviewCard" class="admin-card" style="display: none; margin-bottom: 24px; border: 2px solid var(--admin-gold, #D4AF37);">
    <div class="admin-card-header" style="background: var(--admin-bg-card-secondary, #171C2B); display: flex; justify-content: space-between; align-items: center;">
        <h3 class="admin-card-title">+ Add Offline / Imported Customer Review</h3>
        <button type="button" onclick="document.getElementById('manualReviewCard').style.display='none'" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--color-text-muted);">&times;</button>
    </div>
    <div class="admin-card-body" style="padding: 20px;">
        <form method="POST" action="<?= url('admin/reviews.php') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add_manual_review">

            <div class="admin-form-2col" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label required">Product</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Select Product --</option>
                        <?php foreach ($allProducts as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['title']) ?> (<?= e($p['sku']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label required">Star Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="5" selected>★★★★★ (5 Stars - Excellent)</option>
                        <option value="4">★★★★☆ (4 Stars - Very Good)</option>
                        <option value="3">★★★☆☆ (3 Stars - Average)</option>
                        <option value="2">★★☆☆☆ (2 Stars - Poor)</option>
                        <option value="1">★☆☆☆☆ (1 Star - Terrible)</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-2col" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label required">Customer Name</label>
                    <input type="text" name="customer_name" class="form-input" required placeholder="e.g. Rahul Sharma">
                </div>
                <div class="form-group">
                    <label class="form-label">Customer Email</label>
                    <input type="email" name="customer_email" class="form-input" placeholder="e.g. rahul@example.com">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label">Review Headline / Title</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. Exceptional Print Quality & Vibrant Colors">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label required">Review Body / Feedback</label>
                <textarea name="review_text" rows="3" class="form-input" required placeholder="Detailed customer feedback on washability, peel ease, or adhesion..."></textarea>
            </div>

            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="is_verified" value="1" checked>
                    Mark as Verified Purchase
                </label>
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; cursor: pointer;">
                    <input type="radio" name="status" value="approved" checked>
                    Publish Directly (Approved)
                </label>
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; cursor: pointer;">
                    <input type="radio" name="status" value="pending">
                    Save as Pending
                </label>
                <button type="submit" class="btn btn-primary btn-sm" style="margin-left: auto;">Save Review</button>
            </div>
        </form>
    </div>
</div>

<div class="admin-filter-bar">
    <div class="admin-filter-tabs">
        <a href="<?= url('admin/reviews.php') ?>" class="admin-tab-btn <?= ($statusFilter === '') ? 'active' : '' ?>">
            All (<?= $countAll ?>)
        </a>
        <a href="<?= url('admin/reviews.php?status=pending') ?>" class="admin-tab-btn <?= ($statusFilter === 'pending') ? 'active' : '' ?>">
            Pending (<?= $countPending ?>)
        </a>
        <a href="<?= url('admin/reviews.php?status=approved') ?>" class="admin-tab-btn <?= ($statusFilter === 'approved') ? 'active' : '' ?>">
            Approved (<?= $countApproved ?>)
        </a>
        <a href="<?= url('admin/reviews.php?status=rejected') ?>" class="admin-tab-btn <?= ($statusFilter === 'rejected') ? 'active' : '' ?>">
            Rejected (<?= $countRejected ?>)
        </a>
    </div>

    <form method="GET" action="<?= url('admin/reviews.php') ?>" class="admin-search-form">
        <?php if ($statusFilter !== ''): ?>
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
        <?php endif; ?>
        <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search reviewer or product..." class="form-input form-input-sm" style="width: 240px;">
        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
        <?php if ($searchTerm || $statusFilter !== ''): ?>
            <a href="<?= url('admin/reviews.php') ?>" class="btn btn-outline btn-sm">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Product</th>
                    <th style="width: 110px;">Rating</th>
                    <th>Review Content</th>
                    <th style="width: 150px;">Customer</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 110px;">Date</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: var(--color-text-muted);">
                            <div style="font-size: 2rem; margin-bottom: 8px;">💬</div>
                            <strong>No customer reviews found.</strong>
                            <p style="margin: 4px 0 0; font-size: 0.85rem;">Reviews submitted by buyers on product pages will appear here for verification and approval.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--color-navy); display: block; font-size: 0.88rem;">
                                    <?= e($rev['product_title'] ?? 'Unknown Product') ?>
                                </strong>
                                <?php if (!empty($rev['product_sku'])): ?>
                                    <span style="font-size: 0.72rem; color: var(--color-gold); font-weight: 700;">SKU: <?= e($rev['product_sku']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($rev['product_slug'])): ?>
                                    <a href="<?= product_url($rev['product_slug']) ?>" target="_blank" style="display: block; font-size: 0.75rem; color: var(--color-royal); margin-top: 2px;">
                                        View Page ↗
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="color: #f59e0b; font-size: 0.95rem; letter-spacing: 2px;">
                                    <?= str_repeat('★', intval($rev['rating'])) . str_repeat('☆', 5 - intval($rev['rating'])) ?>
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted);">
                                    <?= intval($rev['rating']) ?> out of 5
                                </span>
                            </td>
                            <td>
                                <strong style="display: block; color: var(--color-navy); font-size: 0.92rem; margin-bottom: 4px;">
                                    <?= e($rev['title']) ?>
                                </strong>
                                <p style="font-size: 0.85rem; color: var(--color-text-body); line-height: 1.5; margin: 0;">
                                    <?= nl2br(e($rev['review_text'])) ?>
                                </p>
                            </td>
                            <td>
                                <strong style="display: block; font-size: 0.85rem; color: var(--color-navy);"><?= e($rev['customer_name']) ?></strong>
                                <?php if (!empty($rev['customer_email'])): ?>
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted);"><?= e($rev['customer_email']) ?></span>
                                <?php endif; ?>
                                <div style="margin-top: 4px;">
                                    <?php if ($rev['is_verified_purchase']): ?>
                                        <span class="badge badge-success" style="font-size: 0.7rem;">✓ Verified Purchase</span>
                                    <?php else: ?>
                                        <span class="badge badge-info" style="font-size: 0.7rem; opacity: 0.7;">Unverified</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($rev['status'] === 'approved'): ?>
                                    <span class="badge badge-success" style="font-size: 0.75rem;">● Approved</span>
                                <?php elseif ($rev['status'] === 'rejected'): ?>
                                    <span class="badge badge-danger" style="font-size: 0.75rem;">✕ Rejected</span>
                                <?php else: ?>
                                    <span class="badge badge-warning" style="font-size: 0.75rem;">⏳ Pending</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--color-text-muted);">
                                <?= date('d M Y', strtotime($rev['created_at'])) ?>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <?php if ($rev['status'] !== 'approved'): ?>
                                        <form method="POST" action="<?= url('admin/reviews.php') ?>">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                            <button type="submit" class="btn btn-xs btn-primary" style="width: 100%;">
                                                ✓ Approve
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($rev['status'] !== 'rejected'): ?>
                                        <form method="POST" action="<?= url('admin/reviews.php') ?>">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                            <button type="submit" class="btn btn-xs btn-outline" style="width: 100%; color: #d97706; border-color: #d97706;">
                                                ✕ Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="POST" action="<?= url('admin/reviews.php') ?>" onsubmit="return confirm('Delete this review permanently?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                        <button type="submit" class="btn btn-xs btn-outline" style="width: 100%; color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
