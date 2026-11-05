<?php
/**
 * OrderPrintex - Admin Gallery Management
 */

$adminTitle = "Manage Work Showcase & Gallery";
require_once __DIR__ . '/header.php';

// Handle Actions (Add, Edit, Delete, Toggle Featured)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'add_item') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'dtf');
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($title)) {
            set_flash('error', 'Please provide a title for the showcase item.');
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        if (empty($_FILES['gallery_image']['name'])) {
            set_flash('error', 'Please select an image file to upload.');
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        $upload = handle_file_upload($_FILES['gallery_image'], ['jpg', 'jpeg', 'png', 'webp']);
        if (!$upload['success']) {
            set_flash('error', 'Upload failed: ' . $upload['error']);
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        $imageUrl = 'uploads/' . $upload['stored_name'];

        db_insert('gallery', [
            'title'         => $title,
            'category'      => $category,
            'image_url'     => $imageUrl,
            'description'   => $description,
            'is_featured'   => $isFeatured,
            'display_order' => $displayOrder
        ]);

        log_activity($adminUser['id'], 'add_gallery', "Added showcase item: {$title}");
        set_flash('success', "New project added to gallery.");
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    if ($action === 'delete_item') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $item = db_fetch_one("SELECT * FROM gallery WHERE id = :id", ['id' => $itemId]);
        if ($item) {
            // Delete physical file if inside uploads/
            if (str_starts_with($item['image_url'], 'uploads/')) {
                $filePath = ROOT_PATH . '/' . $item['image_url'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            db_delete('gallery', 'id = :id', ['id' => $itemId]);
            log_activity($adminUser['id'], 'delete_gallery', "Deleted showcase item: {$item['title']}");
            set_flash('success', "Gallery item removed.");
        }
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    if ($action === 'toggle_featured') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $item = db_fetch_one("SELECT id, is_featured FROM gallery WHERE id = :id", ['id' => $itemId]);
        if ($item) {
            $newVal = $item['is_featured'] ? 0 : 1;
            db_update('gallery', ['is_featured' => $newVal], 'id = :id', ['id' => $itemId]);
            set_flash('success', "Gallery spotlight visibility updated.");
        }
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }
}

// Category filter
$catFilter = trim($_GET['category'] ?? '');
$sql = "SELECT * FROM gallery WHERE 1=1";
$params = [];
if ($catFilter) {
    $sql .= " AND category = :cat";
    $params['cat'] = $catFilter;
}
$sql .= " ORDER BY display_order ASC, id DESC";
$galleryItems = db_fetch_all($sql, $params);
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Studio Work & Gallery</h1>
        <p class="admin-page-subtitle">Upload genuine production samples, set category classifications, and curate the client-facing portfolio.</p>
    </div>
</div>

<div class="admin-grid-layout" style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
    
    <!-- Upload New Project Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">+ Upload New Work</h2>
        </div>
        <div class="admin-card-body" style="padding: 20px;">
            <form method="POST" action="<?= url('admin/gallery.php') ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="add_item">

                <div class="form-group">
                    <label class="form-label required">Project / Sample Title</label>
                    <input type="text" name="title" class="form-input" required placeholder="e.g. Neon Cyber DTF Hoodies">
                </div>

                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="dtf">DTF Textile Transfers</option>
                        <option value="uv_dtf">UV DTF Hard Decals</option>
                        <option value="tshirt">Custom Screen & DTF T-Shirts</option>
                        <option value="corporate">Corporate Kits & Merchandise</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Showcase Photograph</label>
                    <input type="file" name="gallery_image" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp" required>
                    <small style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: 4px;">
                        Upload crisp photography showcasing the print clarity, gloss, or peel quality.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Production Remarks / Materials</label>
                    <input type="text" name="description" class="form-input" placeholder="e.g. 5-pass high-density white underbase on heavy cotton">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" checked>
                        Spotlight on Homepage Gallery
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Priority</label>
                    <input type="number" name="display_order" class="form-input form-input-sm" value="0" style="width: 100px;">
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                    Upload to Portfolio
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery Grid View -->
    <div>
        <div class="admin-filter-bar" style="margin-bottom: 16px;">
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= url('admin/gallery.php') ?>" class="admin-tab-btn <?= empty($catFilter) ? 'active' : '' ?>">All (<?= count($galleryItems) ?>)</a>
                <a href="<?= url('admin/gallery.php?category=dtf') ?>" class="admin-tab-btn <?= $catFilter === 'dtf' ? 'active' : '' ?>">DTF Transfers</a>
                <a href="<?= url('admin/gallery.php?category=uv_dtf') ?>" class="admin-tab-btn <?= $catFilter === 'uv_dtf' ? 'active' : '' ?>">UV DTF</a>
                <a href="<?= url('admin/gallery.php?category=tshirt') ?>" class="admin-tab-btn <?= $catFilter === 'tshirt' ? 'active' : '' ?>">T-Shirts</a>
                <a href="<?= url('admin/gallery.php?category=corporate') ?>" class="admin-tab-btn <?= $catFilter === 'corporate' ? 'active' : '' ?>">Corporate Kits</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
            <?php if (empty($galleryItems)): ?>
                <div class="admin-card" style="grid-column: 1 / -1; padding: 40px; text-align: center; color: var(--color-text-muted);">
                    No showcase images in this category yet.
                </div>
            <?php else: ?>
                <?php foreach ($galleryItems as $item): ?>
                    <div class="admin-card" style="overflow: hidden; display: flex; flex-direction: column;">
                        <div style="height: 160px; overflow: hidden; position: relative; background: #eee;">
                            <img src="<?= asset_url($item['image_url']) ?>" alt="<?= e($item['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-info" style="position: absolute; top: 8px; left: 8px; font-size: 0.7rem;">
                                <?= strtoupper(str_replace('_', ' ', $item['category'])) ?>
                            </span>
                        </div>
                        <div style="padding: 12px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--color-navy); margin-bottom: 4px;"><?= e($item['title']) ?></h4>
                                <?php if (!empty($item['description'])): ?>
                                    <p style="font-size: 0.75rem; color: var(--color-text-muted); margin: 0; line-height: 1.4;"><?= e($item['description']) ?></p>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; border-top: 1px solid var(--color-border); padding-top: 8px;">
                                <form method="POST" action="<?= url('admin/gallery.php') ?>" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="action" value="toggle_featured">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-xs <?= $item['is_featured'] ? 'btn-outline' : 'btn-secondary' ?>" style="<?= $item['is_featured'] ? 'color: var(--color-gold); border-color: var(--color-gold);' : 'opacity: 0.6;' ?>" title="Toggle Homepage Spotlight">
                                        <?= $item['is_featured'] ? '★ Featured' : '☆ Standard' ?>
                                    </button>
                                </form>

                                <form method="POST" action="<?= url('admin/gallery.php') ?>" onsubmit="return confirm('Remove this image from gallery?');" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="action" value="delete_item">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
