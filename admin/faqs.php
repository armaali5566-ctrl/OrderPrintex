<?php
/**
 * OrderPrintex - Admin FAQ Management
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();
$adminUser = auth_user();

// FAQs CMS module decommissioned in simplified workflow.
header('Location: ' . url('admin/dashboard.php'));
exit;

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/faqs.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'save_faq') {
        $faqId = intval($_POST['faq_id'] ?? 0);
        $category = trim($_POST['category'] ?? 'General');
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            set_flash('error', 'Both question and answer are required.');
            header('Location: ' . url('admin/faqs.php' . ($faqId ? '?edit_id=' . $faqId : '')));
            exit;
        }

        if ($faqId > 0) {
            db_update('faqs', [
                'category'      => $category,
                'question'      => $question,
                'answer'        => $answer,
                'display_order' => $displayOrder,
                'is_active'     => $isActive
            ], 'id = :id', ['id' => $faqId]);
            log_activity($adminUser['id'], 'update_faq', "Updated FAQ #{$faqId}");
            set_flash('success', "FAQ updated successfully.");
        } else {
            db_insert('faqs', [
                'category'      => $category,
                'question'      => $question,
                'answer'        => $answer,
                'display_order' => $displayOrder,
                'is_active'     => $isActive
            ]);
            log_activity($adminUser['id'], 'create_faq', "Created new FAQ: {$question}");
            set_flash('success', "New FAQ added.");
        }

        header('Location: ' . url('admin/faqs.php'));
        exit;
    }

    if ($action === 'delete_faq') {
        $faqId = intval($_POST['faq_id'] ?? 0);
        db_delete('faqs', 'id = :id', ['id' => $faqId]);
        log_activity($adminUser['id'], 'delete_faq', "Deleted FAQ #{$faqId}");
        set_flash('success', "FAQ deleted.");
        header('Location: ' . url('admin/faqs.php'));
        exit;
    }
}

// Edit Mode
$editId = intval($_GET['edit_id'] ?? 0);
$editFaq = null;
if ($editId) {
    $editFaq = db_fetch_one("SELECT * FROM faqs WHERE id = :id", ['id' => $editId]);
}

// Category Filter
$catFilter = trim($_GET['category'] ?? '');
$sql = "SELECT * FROM faqs WHERE 1=1";
$params = [];
if ($catFilter) {
    $sql .= " AND category = :cat";
    $params['cat'] = $catFilter;
}
$sql .= " ORDER BY display_order ASC, id ASC";
$faqs = db_fetch_all($sql, $params);

// Distinct Categories
$categories = db_fetch_all("SELECT DISTINCT category FROM faqs ORDER BY category ASC");

$adminTitle = "Manage Frequently Asked Questions";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Studio FAQs</h1>
        <p class="admin-page-subtitle">Configure client help center items, artwork preparation guides, heat press settings, and order FAQs.</p>
    </div>
</div>

<div class="admin-split-grid">
    
    <!-- Add / Edit FAQ Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $editFaq ? 'Edit FAQ' : '+ Add New Question' ?></h2>
            <?php if ($editFaq): ?>
                <a href="<?= url('admin/faqs.php') ?>" class="btn btn-outline btn-xs">Cancel</a>
            <?php endif; ?>
        </div>
        <div class="admin-card-body" style="padding: 20px;">
            <form method="POST" action="<?= url('admin/faqs.php') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="save_faq">
                <input type="hidden" name="faq_id" value="<?= $editFaq ? $editFaq['id'] : 0 ?>">

                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <input type="text" name="category" list="faq_categories" class="form-input" required 
                           value="<?= e($editFaq['category'] ?? 'General') ?>" placeholder="e.g. DTF Printing, UV DTF Decals, Shipping">
                    <datalist id="faq_categories">
                        <option value="General">
                        <option value="DTF Printing">
                        <option value="UV DTF Transfers">
                        <option value="Artwork & Files">
                        <option value="Turnaround & Shipping">
                    </datalist>
                </div>

                <div class="form-group">
                    <label class="form-label required">Question</label>
                    <input type="text" name="question" class="form-input" required 
                           value="<?= e($editFaq['question'] ?? '') ?>" placeholder="e.g. What heat press settings do you recommend for DTF?">
                </div>

                <div class="form-group">
                    <label class="form-label required">Detailed Answer</label>
                    <textarea name="answer" class="form-input" rows="6" required placeholder="Provide clear technical guidance, press temperatures, peel instructions, file recommendations..."><?= e($editFaq['answer'] ?? '') ?></textarea>
                </div>

                <div class="admin-form-2col" style="align-items: center; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Sort Priority</label>
                        <input type="number" name="display_order" class="form-input form-input-sm" value="<?= $editFaq['display_order'] ?? 0 ?>">
                    </div>

                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; margin-top: 18px;">
                        <input type="checkbox" name="is_active" value="1" <?= (!isset($editFaq) || $editFaq['is_active']) ? 'checked' : '' ?>>
                        Active on Website
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                    <?= $editFaq ? '💾 Update FAQ' : '✨ Add to FAQ Help Center' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- FAQs Table -->
    <div>
        <div class="admin-filter-bar" style="margin-bottom: 16px;">
            <div class="admin-filter-tabs">
                <a href="<?= url('admin/faqs.php') ?>" class="admin-tab-btn <?= empty($catFilter) ? 'active' : '' ?>">All (<?= count($faqs) ?>)</a>
                <?php foreach ($categories as $c): ?>
                    <a href="<?= url('admin/faqs.php?category=' . urlencode($c['category'])) ?>" class="admin-tab-btn <?= $catFilter === $c['category'] ? 'active' : '' ?>">
                        <?= e($c['category']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Category</th>
                            <th>Question & Answer</th>
                            <th style="width: 60px;">Order</th>
                            <th style="width: 70px;">Status</th>
                            <th style="width: 110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($faqs)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                    No FAQs found in this category.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($faqs as $f): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-info" style="font-size: 0.75rem;"><?= e($f['category']) ?></span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--color-navy); display: block; margin-bottom: 4px;"><?= e($f['question']) ?></strong>
                                        <div style="font-size: 0.8rem; color: var(--color-text-muted); line-height: 1.4;">
                                            <?= nl2br(e(mb_strimwidth($f['answer'], 0, 160, '...'))) ?>
                                        </div>
                                    </td>
                                    <td style="font-weight: 600; text-align: center;"><?= $f['display_order'] ?></td>
                                    <td>
                                        <span class="badge <?= $f['is_active'] ? 'badge-success' : 'badge-neutral' ?>">
                                            <?= $f['is_active'] ? 'Active' : 'Hidden' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <a href="<?= url('admin/faqs.php?edit_id=' . $f['id']) ?>" class="btn btn-outline btn-xs">
                                                Edit
                                            </a>
                                            <form method="POST" action="<?= url('admin/faqs.php') ?>" style="display: inline;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_faq">
                                                <input type="hidden" name="faq_id" value="<?= $f['id'] ?>">
                                                <button type="button" data-confirm="Are you sure you want to delete this FAQ entry?" data-confirm-title="Delete FAQ?" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
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
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
