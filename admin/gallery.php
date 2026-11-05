<?php
/**
 * OrderPrintex - Portfolio Management CMS
 * Manage customer-facing print portfolio, showcase projects, high-resolution imagery & categories
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_permission('portfolio.view');
$adminUser = auth_user();

// Handle Actions (Add, Edit, Delete, Toggle Featured, Toggle Published)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Add New Project
    if ($action === 'add_item') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'dtf');
        $customCategory = trim($_POST['custom_category'] ?? '');
        if (strtolower($category) === 'other' || !empty($customCategory)) {
            if (!empty($customCategory)) {
                $category = $customCategory;
            } else {
                set_flash('error', 'Please specify a custom category name.');
                header('Location: ' . url('admin/gallery.php'));
                exit;
            }
        }
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($title)) {
            set_flash('error', 'Please provide a title for the portfolio project.');
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        if (empty($_FILES['gallery_image']['name'])) {
            set_flash('error', 'Please select an image file to upload.');
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        $upload = handle_file_upload($_FILES['gallery_image']);
        if (!$upload['success']) {
            set_flash('error', 'Upload failed: ' . $upload['error']);
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        $imageUrl = $upload['file_path'];

        db_insert('gallery', [
            'title'         => $title,
            'category'      => $category,
            'image_url'     => $imageUrl,
            'description'   => $description,
            'is_featured'   => $isFeatured,
            'is_published'  => $isPublished,
            'display_order' => $displayOrder
        ]);

        log_activity($adminUser['id'], 'add_portfolio', "Added portfolio project: {$title}");
        @seo_sync_all();
        set_flash('success', "New project '{$title}' added to portfolio.");
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    // 2. Edit Project
    if ($action === 'edit_item') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'dtf');
        $customCategory = trim($_POST['custom_category'] ?? '');
        if (strtolower($category) === 'other' || !empty($customCategory)) {
            if (!empty($customCategory)) {
                $category = $customCategory;
            } else {
                set_flash('error', 'Please specify a custom category name.');
                header('Location: ' . url('admin/gallery.php?edit_id=' . $itemId));
                exit;
            }
        }
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        $item = db_fetch_one("SELECT * FROM gallery WHERE id = :id", ['id' => $itemId]);
        if (!$item) {
            set_flash('error', 'Portfolio project not found.');
            header('Location: ' . url('admin/gallery.php'));
            exit;
        }

        $updateData = [
            'title'         => $title,
            'category'      => $category,
            'description'   => $description,
            'is_featured'   => $isFeatured,
            'is_published'  => $isPublished,
            'display_order' => $displayOrder
        ];

        // If replacement image uploaded
        if (!empty($_FILES['gallery_image']['name'])) {
            $upload = handle_file_upload($_FILES['gallery_image']);
            if ($upload['success']) {
                $updateData['image_url'] = $upload['file_path'];
            }
        }

        db_update('gallery', $updateData, 'id = :id', ['id' => $itemId]);
        log_activity($adminUser['id'], 'update_portfolio', "Updated portfolio project: {$title}");
        @seo_sync_all();
        set_flash('success', "Portfolio project '{$title}' updated successfully.");
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    // 3. Delete Project
    if ($action === 'delete_item') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $item = db_fetch_one("SELECT * FROM gallery WHERE id = :id", ['id' => $itemId]);
        if ($item) {
            if (str_starts_with($item['image_url'], 'uploads/')) {
                $filePath = ROOT_PATH . '/' . $item['image_url'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            db_delete('gallery', 'id = :id', ['id' => $itemId]);
            log_activity($adminUser['id'], 'delete_portfolio', "Deleted portfolio project: {$item['title']}");
            @seo_sync_all();
            set_flash('success', "Portfolio project removed.");
        }
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    // 4. Toggle Featured
    if ($action === 'toggle_featured') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $item = db_fetch_one("SELECT id, is_featured FROM gallery WHERE id = :id", ['id' => $itemId]);
        if ($item) {
            $newVal = $item['is_featured'] ? 0 : 1;
            db_update('gallery', ['is_featured' => $newVal], 'id = :id', ['id' => $itemId]);
            @seo_sync_all();
            set_flash('success', "Project spotlight status updated.");
        }
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }

    // 5. Toggle Published
    if ($action === 'toggle_published') {
        $itemId = intval($_POST['item_id'] ?? 0);
        $item = db_fetch_one("SELECT id, is_published FROM gallery WHERE id = :id", ['id' => $itemId]);
        if ($item) {
            $newVal = (!empty($item['is_published'])) ? 0 : 1;
            db_update('gallery', ['is_published' => $newVal], 'id = :id', ['id' => $itemId]);
            @seo_sync_all();
            set_flash('success', "Project visibility status updated.");
        }
        header('Location: ' . url('admin/gallery.php'));
        exit;
    }
}

// Category filter
$catFilter = trim($_GET['category'] ?? '');
$editId = intval($_GET['edit_id'] ?? 0);
$editItem = null;
if ($editId > 0) {
    $editItem = db_fetch_one("SELECT * FROM gallery WHERE id = :id", ['id' => $editId]);
}

$sql = "SELECT * FROM gallery WHERE 1=1";
$params = [];
if ($catFilter) {
    $sql .= " AND category = :cat";
    $params['cat'] = $catFilter;
}
$sql .= " ORDER BY display_order ASC, id DESC";
$galleryItems = db_fetch_all($sql, $params);

$adminTitle = "Portfolio Management";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Portfolio Management</h1>
        <p class="admin-page-subtitle">Showcase genuine commercial printing work, garment transfers, decals, and bespoke corporate merchandise to prospective clients.</p>
    </div>
    <div class="admin-topbar-actions">
        <a href="<?= url('gallery/') ?>" target="_blank" class="btn btn-outline btn-sm">
            <span>View Public Portfolio ↗</span>
        </a>
    </div>
</div>

<div class="admin-split-grid">
    
    <!-- Upload or Edit Project Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $editItem ? 'Edit Portfolio Project' : '+ Add Portfolio Project' ?></h2>
            <?php if ($editItem): ?>
                <a href="<?= url('admin/gallery.php') ?>" class="btn btn-xs btn-outline">Cancel Edit</a>
            <?php endif; ?>
        </div>
        <div class="admin-card-body" style="padding: 20px;">
            <form method="POST" action="<?= url('admin/gallery.php') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="<?= $editItem ? 'edit_item' : 'add_item' ?>">
                <?php if ($editItem): ?>
                    <input type="hidden" name="item_id" value="<?= $editItem['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label required">Project / Showcase Title</label>
                    <input type="text" name="title" class="form-input" required placeholder="e.g. Neon Cyber DTF Hoodies" value="<?= e($editItem['title'] ?? '') ?>">
                </div>

                <?php
                $standardGalleryCats = ['dtf', 'uv_dtf', 'tshirt', 'corporate', 'packaging'];
                $isCustomGalleryCat = ($editItem && !empty($editItem['category']) && !in_array($editItem['category'], $standardGalleryCats, true));
                ?>
                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <select name="category" class="form-select dynamic-other-select" required
                            data-enable-other="true"
                            data-other-name="custom_category"
                            data-other-label="Specify Custom Category Name"
                            data-other-placeholder="e.g. Stickers & Decals, Packaging & Boxes, Event Signage"
                            data-other-value-init="<?= $isCustomGalleryCat ? e($editItem['category']) : '' ?>">
                        <option value="dtf" <?= ($editItem && $editItem['category'] === 'dtf') ? 'selected' : '' ?>>DTF Textile Transfers</option>
                        <option value="uv_dtf" <?= ($editItem && $editItem['category'] === 'uv_dtf') ? 'selected' : '' ?>>UV DTF Hard Decals</option>
                        <option value="tshirt" <?= ($editItem && $editItem['category'] === 'tshirt') ? 'selected' : '' ?>>Custom Apparel &amp; T-Shirts</option>
                        <option value="corporate" <?= ($editItem && $editItem['category'] === 'corporate') ? 'selected' : '' ?>>Corporate Kits &amp; Swag</option>
                        <option value="packaging" <?= ($editItem && $editItem['category'] === 'packaging') ? 'selected' : '' ?>>Packaging &amp; Boxes</option>
                        <option value="other" <?= $isCustomGalleryCat ? 'selected' : '' ?>>Other (Specify Custom Category)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label <?= $editItem ? '' : 'required' ?>">
                        Project Photograph <?= $editItem ? '(Leave empty to keep current)' : '' ?>
                    </label>
                    <?php if ($editItem && !empty($editItem['image_url'])): ?>
                        <div style="margin-bottom:8px; width:80px; height:80px; border-radius:6px; overflow:hidden; border:1px solid rgba(255,255,255,0.15);">
                            <img src="<?= asset_url($editItem['image_url']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="gallery_image" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp" <?= $editItem ? '' : 'required' ?>>
                    <small style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">
                        Upload crisp photography showcasing print detail, vivid colours, or peel quality.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Project Details / Specifications</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="e.g. 5-pass high-density white underbase on 240 GSM heavy cotton..."><?= e($editItem['description'] ?? '') ?></textarea>
                </div>

                <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; color:var(--admin-text-main);">
                        <input type="checkbox" name="is_published" value="1" <?= (!$editItem || !empty($editItem['is_published'])) ? 'checked' : '' ?>>
                        Published on Public Website
                    </label>

                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; color:var(--admin-text-main);">
                        <input type="checkbox" name="is_featured" value="1" <?= (!$editItem || !empty($editItem['is_featured'])) ? 'checked' : '' ?>>
                        Spotlight on Homepage Portfolio
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Display Order / Priority</label>
                    <input type="number" name="display_order" class="form-input form-input-sm" value="<?= $editItem['display_order'] ?? 0 ?>" style="width: 100px;">
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                    <?= $editItem ? '💾 Save Changes' : '🚀 Add to Portfolio' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery Grid View -->
    <div>
        <div class="admin-filter-bar" style="margin-bottom: 16px;">
            <div class="admin-filter-tabs">
                <a href="<?= url('admin/gallery.php') ?>" class="admin-tab-btn <?= empty($catFilter) ? 'active' : '' ?>">All Projects (<?= count($galleryItems) ?>)</a>
                <a href="<?= url('admin/gallery.php?category=dtf') ?>" class="admin-tab-btn <?= $catFilter === 'dtf' ? 'active' : '' ?>">DTF Transfers</a>
                <a href="<?= url('admin/gallery.php?category=uv_dtf') ?>" class="admin-tab-btn <?= $catFilter === 'uv_dtf' ? 'active' : '' ?>">UV DTF</a>
                <a href="<?= url('admin/gallery.php?category=tshirt') ?>" class="admin-tab-btn <?= $catFilter === 'tshirt' ? 'active' : '' ?>">Apparel &amp; Tees</a>
                <a href="<?= url('admin/gallery.php?category=corporate') ?>" class="admin-tab-btn <?= $catFilter === 'corporate' ? 'active' : '' ?>">Corporate Kits</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 200px), 1fr)); gap: 16px;">
            <?php if (empty($galleryItems)): ?>
                <div class="admin-card" style="grid-column: 1 / -1; padding: 40px; text-align: center; color: var(--admin-text-muted);">
                    No portfolio projects in this category yet. Click <strong>+ Add Portfolio Project</strong> to showcase your work.
                </div>
            <?php else: ?>
                <?php foreach ($galleryItems as $item): ?>
                    <div class="admin-card" style="overflow: hidden; display: flex; flex-direction: column; border: 1px solid <?= ($editId === $item['id']) ? 'var(--admin-gold)' : 'rgba(255,255,255,0.08)' ?>;">
                        <div style="height: 160px; overflow: hidden; position: relative; background: var(--admin-bg-card-secondary, #171C2B);">
                            <img src="<?= asset_url($item['image_url']) ?>" alt="<?= e($item['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-info" style="position: absolute; top: 8px; left: 8px; font-size: 0.68rem;">
                                <?= strtoupper(str_replace('_', ' ', $item['category'])) ?>
                            </span>
                            <?php if (empty($item['is_published'])): ?>
                                <span class="badge badge-secondary" style="position: absolute; top: 8px; right: 8px; font-size: 0.65rem; background:#64748B;">
                                    Draft
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="padding: 12px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--admin-text-main); margin-bottom: 4px;"><?= e($item['title']) ?></h4>
                                <?php if (!empty($item['description'])): ?>
                                    <p style="font-size: 0.75rem; color: var(--admin-text-muted); margin: 0; line-height: 1.4;"><?= e($item['description']) ?></p>
                                <?php endif; ?>
                            </div>

                            <div style="margin-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 8px; display: flex; flex-direction: column; gap: 6px;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <form method="POST" action="<?= url('admin/gallery.php') ?>" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle_featured">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-xs <?= $item['is_featured'] ? 'btn-outline' : 'btn-secondary' ?>" style="<?= $item['is_featured'] ? 'color: var(--admin-gold); border-color: var(--admin-gold);' : 'opacity: 0.6;' ?>" title="Toggle Homepage Spotlight">
                                            <?= $item['is_featured'] ? '★ Featured' : '☆ Standard' ?>
                                        </button>
                                    </form>

                                    <form method="POST" action="<?= url('admin/gallery.php') ?>" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle_published">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-xs <?= !empty($item['is_published']) ? 'btn-outline' : 'btn-secondary' ?>" style="<?= !empty($item['is_published']) ? 'color: #10B981; border-color: #10B981;' : 'opacity: 0.5;' ?>" title="Toggle Public Visibility">
                                            <?= !empty($item['is_published']) ? '● Live' : '○ Hidden' ?>
                                        </button>
                                    </form>
                                </div>

                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2px;">
                                    <a href="<?= url('admin/gallery.php?edit_id=' . $item['id']) ?>" class="btn btn-outline btn-xs">
                                        ✏ Edit
                                    </a>

                                    <form method="POST" action="<?= url('admin/gallery.php') ?>" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete_item">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="button" data-confirm="Are you sure you want to permanently remove this project from your portfolio?" data-confirm-title="Delete Portfolio Project?" data-confirm-item="<?= e($item['title']) ?>" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
