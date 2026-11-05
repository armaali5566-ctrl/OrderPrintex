<?php
/**
 * OrderPrintex - Admin Category Management
 * Full-width Vertical Layout: Add/Edit Form at Top, Categories Listing Below
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_permission('categories.view');
$adminUser = auth_user();

// Check if database table supports image_url
$hasImageCol = false;
try {
    $checkImg = db_fetch_one("SHOW COLUMNS FROM categories LIKE 'image_url'");
    $hasImageCol = !empty($checkImg);
} catch (Exception $e) {
    $hasImageCol = false;
}

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/categories.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save Category (Create or Update)
    if ($action === 'save_category') {
        $catId = intval($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($name)) {
            set_flash('error', 'Category name is required.');
            header('Location: ' . url('admin/categories.php'));
            exit;
        }

        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }

        // Image upload or text url (if supported)
        $imageUrl = null;
        if ($hasImageCol) {
            if (!empty($_FILES['category_image']['name'])) {
                $uploadResult = handle_file_upload($_FILES['category_image']);
                if ($uploadResult['success']) {
                    $imageUrl = $uploadResult['file_path'];
                }
            } elseif (!empty($_POST['current_image_url'])) {
                $imageUrl = trim($_POST['current_image_url']);
            }
        }

        if ($catId > 0) {
            $check = db_fetch_one("SELECT id FROM categories WHERE slug = :s AND id != :id", ['s' => $slug, 'id' => $catId]);
            if ($check) $slug .= '-' . rand(10, 99);

            $updateData = [
                'name'          => $name,
                'slug'          => $slug,
                'description'   => $description,
                'display_order' => $displayOrder
            ];
            if ($hasImageCol && $imageUrl) {
                $updateData['image_url'] = $imageUrl;
            }

            db_update('categories', $updateData, 'id = :id', ['id' => $catId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'update_category', "Updated category: {$name}");
            set_flash('success', "Category '{$name}' updated successfully.");
        } else {
            $check = db_fetch_one("SELECT id FROM categories WHERE slug = :s", ['s' => $slug]);
            if ($check) $slug .= '-' . rand(10, 99);

            $insertData = [
                'name'          => $name,
                'slug'          => $slug,
                'description'   => $description,
                'display_order' => $displayOrder
            ];
            if ($hasImageCol) {
                $insertData['image_url'] = $imageUrl ?: 'assets/images/gallery_1.jpg';
            }

            db_insert('categories', $insertData);

            @seo_sync_all();
            log_activity($adminUser['id'], 'create_category', "Created category: {$name}");
            set_flash('success', "Category '{$name}' created successfully.");
        }

        header('Location: ' . url('admin/categories.php'));
        exit;
    }

    // 2. Delete Category
    if ($action === 'delete_category') {
        $catId = intval($_POST['category_id'] ?? 0);
        $cat = db_fetch_one("SELECT * FROM categories WHERE id = :id", ['id' => $catId]);
        if ($cat) {
            // Unlink products from category before deletion
            db_update('products', ['category_id' => null], 'category_id = :cid', ['cid' => $catId]);
            db_delete('categories', 'id = :id', ['id' => $catId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'delete_category', "Deleted category: {$cat['name']}");
            set_flash('success', "Category '{$cat['name']}' deleted. Associated products have been unassigned.");
        }
        header('Location: ' . url('admin/categories.php'));
        exit;
    }
}

// Fetch categories with associated active product counts
$categories = db_fetch_all("
    SELECT c.*, COUNT(p.id) as product_count
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id
    ORDER BY c.display_order ASC, c.id ASC
");

$editCatId = intval($_GET['edit_id'] ?? 0);
$editCategory = null;
if ($editCatId > 0) {
    $editCategory = db_fetch_one("SELECT * FROM categories WHERE id = :id", ['id' => $editCatId]);
}

$adminTitle = "Manage Product Categories";
require_once __DIR__ . '/header.php';
?>

<!-- Topbar / Page Heading -->
<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="margin:0 0 0.35rem;">Manage Product Categories</h1>
    <p class="admin-page-subtitle" style="margin:0;">
      Organize printing products, apparel, signage, and bespoke transfer collections into catalog categories.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem; align-items:center;">
    <?php if ($editCategory): ?>
      <a href="<?= url('admin/categories.php') ?>" class="btn btn-sm btn-outline">
        + Create New Category
      </a>
    <?php endif; ?>
    <a href="<?= url('admin/products.php') ?>" class="btn btn-sm btn-outline">
      &larr; Products Catalog
    </a>
  </div>
</div>

<?= render_flash() ?>

<!-- ========================================================================= -->
<!-- 1. FULL-WIDTH ADD / EDIT CATEGORY FORM (AT TOP) -->
<!-- ========================================================================= -->
<div class="panel-card" id="category-form" style="width:100%; margin-bottom:2rem;">
  <div class="panel-header" style="border-bottom:1px solid var(--admin-border-card); padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
    <div>
      <h2 class="panel-title" style="margin:0 0 0.2rem; font-size:1.15rem;">
        <?= ($editCategory) ? 'Edit Category: ' . e($editCategory['name']) : 'Add New Product Category' ?>
      </h2>
      <p style="margin:0; font-size:0.8rem; color:var(--admin-text-muted);">
        <?= ($editCategory) ? 'Modify category properties, URL slug identifier, and display sequence.' : 'Create a new catalog category with custom slug and descriptive attributes.' ?>
      </p>
    </div>
    <?php if ($editCategory): ?>
      <span class="status-badge" style="background:rgba(212,175,55,0.15); color:var(--admin-gold); border:1px solid rgba(212,175,55,0.3); font-weight:700;">
        Editing ID #<?= $editCategory['id'] ?>
      </span>
    <?php endif; ?>
  </div>

  <div style="padding:1.75rem 1.5rem;">
    <form method="POST" action="<?= url('admin/categories.php') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_category">
      <input type="hidden" name="category_id" value="<?= $editCategory['id'] ?? 0 ?>">
      <input type="hidden" name="current_image_url" value="<?= e($editCategory['image_url'] ?? '') ?>">

      <!-- Form Grid -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem; margin-bottom:1.5rem;">
        
        <!-- Left / Column 1: Core Identification -->
        <div style="display:flex; flex-direction:column; gap:1.25rem;">
          <div>
            <label class="form-label required" style="display:block; margin-bottom:0.4rem; font-size:0.825rem; font-weight:700; color:var(--admin-text-main); text-transform:uppercase; letter-spacing:0.04em;">
              Category Name <span style="color:#f87171;">*</span>
            </label>
            <input type="text" name="name" class="form-input" required placeholder="e.g. DTF Gang Sheets, UV Decals, Streetwear Tees" value="<?= e($editCategory['name'] ?? '') ?>" autofocus>
            <small style="color:var(--admin-text-muted); font-size:0.75rem; margin-top:0.25rem; display:block;">Primary public display name of the printing category</small>
          </div>

          <div>
            <label class="form-label" style="display:block; margin-bottom:0.4rem; font-size:0.825rem; font-weight:700; color:var(--admin-text-main); text-transform:uppercase; letter-spacing:0.04em;">
              URL Slug (Canonical Link)
            </label>
            <input type="text" name="slug" class="form-input" placeholder="e.g. dtf-gang-sheets" value="<?= e($editCategory['slug'] ?? '') ?>">
            <small style="color:var(--admin-text-muted); font-size:0.75rem; margin-top:0.25rem; display:block;">Leave blank to auto-generate from name (e.g. /product/category/slug/)</small>
          </div>

          <div>
            <label class="form-label" style="display:block; margin-bottom:0.4rem; font-size:0.825rem; font-weight:700; color:var(--admin-text-main); text-transform:uppercase; letter-spacing:0.04em;">
              Display Sort Sequence
            </label>
            <input type="number" name="display_order" class="form-input" value="<?= e($editCategory['display_order'] ?? '0') ?>" min="0" style="max-width:160px;">
            <small style="color:var(--admin-text-muted); font-size:0.75rem; margin-top:0.25rem; display:block;">Lower values appear first in menus and catalog filters</small>
          </div>
        </div>

        <!-- Right / Column 2: Description & Media -->
        <div style="display:flex; flex-direction:column; gap:1.25rem;">
          <div>
            <label class="form-label" style="display:block; margin-bottom:0.4rem; font-size:0.825rem; font-weight:700; color:var(--admin-text-main); text-transform:uppercase; letter-spacing:0.04em;">
              Category Scope &amp; Description
            </label>
            <textarea name="description" class="form-textarea" rows="4" placeholder="Brief technical summary of substrates, materials, printing processes, and commercial applications included in this category..."><?= e($editCategory['description'] ?? '') ?></textarea>
            <small style="color:var(--admin-text-muted); font-size:0.75rem; margin-top:0.25rem; display:block;">Appears in category archive header and SEO meta descriptions</small>
          </div>

          <?php if ($hasImageCol): ?>
            <div>
              <label class="form-label" style="display:block; margin-bottom:0.4rem; font-size:0.825rem; font-weight:700; color:var(--admin-text-main); text-transform:uppercase; letter-spacing:0.04em;">
                Category Banner / Thumbnail Image
              </label>
              <input type="file" name="category_image" class="form-input" accept="image/*" style="padding:0.45rem 0.65rem;">
              <?php if (!empty($editCategory['image_url'])): ?>
                <div style="margin-top:0.65rem; display:flex; align-items:center; gap:0.75rem; background:rgba(212,175,55,0.05); border:1px solid rgba(212,175,55,0.2); padding:0.5rem 0.75rem; border-radius:6px;">
                  <img src="<?= url(e($editCategory['image_url'])) ?>" alt="Preview" style="width:48px; height:48px; object-fit:cover; border-radius:4px; border:1px solid rgba(255,255,255,0.1);">
                  <div>
                    <div style="font-size:0.8rem; font-weight:600; color:var(--admin-text-main);">Current File</div>
                    <code style="font-size:0.72rem; color:var(--admin-gold);"><?= e($editCategory['image_url']) ?></code>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Action Buttons Bar -->
      <div style="display:flex; gap:0.75rem; align-items:center; border-top:1px solid var(--admin-border-card); padding-top:1.25rem; flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary" style="padding:0.65rem 1.6rem; font-weight:700;">
          <?= ($editCategory) ? '✓ Update Category' : '+ Add Category' ?>
        </button>
        <?php if ($editCategory): ?>
          <a href="<?= url('admin/categories.php') ?>" class="btn btn-outline" style="padding:0.65rem 1.25rem;">
            Cancel Editing
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- ========================================================================= -->
<!-- 2. FULL-WIDTH CATEGORIES LISTING TABLE (BELOW FORM) -->
<!-- ========================================================================= -->
<div class="panel-card" style="width:100%;">
  <div class="panel-header" style="border-bottom:1px solid var(--admin-border-card); padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
    <div>
      <h2 class="panel-title" style="margin:0 0 0.2rem; font-size:1.15rem;">Existing Product Categories</h2>
      <p style="margin:0; font-size:0.8rem; color:var(--admin-text-muted);">
        All active product groups configured in the OrderPrintex catalog
      </p>
    </div>
    <span class="status-badge" style="background:rgba(212,175,55,0.1); color:var(--admin-gold); border:1px solid rgba(212,175,55,0.25); font-weight:700;">
      <?= count($categories) ?> Total Categories
    </span>
  </div>

  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.88rem;">
      <thead>
        <tr>
          <th style="width:60px; text-align:center;">Seq</th>
          <?php if ($hasImageCol): ?>
            <th style="width:60px;">Image</th>
          <?php endif; ?>
          <th>Category Name &amp; Overview</th>
          <th>URL Slug</th>
          <th style="text-align:center;">Assigned Products</th>
          <th>Created Date</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($categories)): ?>
          <tr>
            <td colspan="<?= $hasImageCol ? 7 : 6 ?>" style="text-align:center; padding:3rem 1.5rem; color:var(--admin-text-muted);">
              <div style="font-size:1.05rem; color:var(--admin-text-main); font-weight:600; margin-bottom:0.35rem;">No categories created yet</div>
              <p style="margin:0; font-size:0.85rem;">Use the form above to add your first product catalog category.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td style="text-align:center; font-family:var(--font-mono); color:var(--admin-text-muted); font-weight:700;">
                <?= (int)$cat['display_order'] ?>
              </td>
              <?php if ($hasImageCol): ?>
                <td>
                  <img src="<?= url(e($cat['image_url'] ?? 'assets/images/gallery_1.jpg')) ?>" alt="" style="width:40px; height:40px; border-radius:6px; object-fit:cover; border:1px solid rgba(255,255,255,0.1);">
                </td>
              <?php endif; ?>
              <td>
                <div style="font-weight:700; color:var(--admin-text-main); font-size:0.95rem; margin-bottom:2px;">
                  <?= e($cat['name']) ?>
                </div>
                <?php if (!empty($cat['description'])): ?>
                  <div style="font-size:0.78rem; color:var(--admin-text-muted); line-height:1.4; max-width:550px;">
                    <?= e(mb_strimwidth($cat['description'], 0, 110, '...')) ?>
                  </div>
                <?php else: ?>
                  <span style="font-size:0.75rem; color:#6B7280; font-style:italic;">No description provided</span>
                <?php endif; ?>
              </td>
              <td>
                <code style="font-size:0.8rem; background:rgba(0,0,0,0.3); border:1px solid rgba(212,175,55,0.25); color:var(--admin-gold); padding:0.2rem 0.5rem; border-radius:4px;">
                  <?= e($cat['slug']) ?>
                </code>
              </td>
              <td style="text-align:center;">
                <span class="status-badge" style="background:rgba(96,165,250,0.12); color:#60A5FA; border:1px solid rgba(96,165,250,0.3); font-weight:700;">
                  <?= (int)$cat['product_count'] ?> Products
                </span>
              </td>
              <td style="font-size:0.82rem; color:var(--admin-text-muted);">
                <?= !empty($cat['created_at']) ? format_date($cat['created_at'], 'd M Y') : '—' ?>
              </td>
              <td style="text-align:right; white-space:nowrap;">
                <div style="display:inline-flex; gap:0.4rem; align-items:center;">
                  <a href="<?= url('admin/categories.php?edit_id=' . $cat['id']) ?>#category-form" class="btn btn-xs btn-outline" style="padding:0.35rem 0.65rem;">
                    ✎ Edit
                  </a>
                  <form method="POST" action="<?= url('admin/categories.php') ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                    <button type="button" data-confirm="Are you sure you want to delete category &#039;<?= e(addslashes($cat['name'])) ?>&#039;? Associated products will be unassigned but not deleted." data-confirm-title="Delete Category?" data-confirm-item="<?= e($cat['name']) ?>" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-xs" style="background:none; border:1px solid rgba(248,113,113,0.3); color:#f87171; padding:0.35rem 0.65rem;" title="Delete Category">
                      ✕ Delete
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
