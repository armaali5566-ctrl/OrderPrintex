<?php
/**
 * OrderPrintex - Admin Category Management
 * Create, Read, Update, Delete Product Categories
 */

$adminTitle = "Manage Product Categories";
require_once __DIR__ . '/header.php';

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/categories.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save Category
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

        // Image upload or text url
        $imageUrl = null;
        if (!empty($_FILES['category_image']['name'])) {
            $uploadResult = handle_file_upload($_FILES['category_image'], ['jpg', 'jpeg', 'png', 'webp']);
            if ($uploadResult['success']) {
                $imageUrl = 'uploads/' . $uploadResult['stored_name'];
            }
        } elseif (!empty($_POST['current_image_url'])) {
            $imageUrl = trim($_POST['current_image_url']);
        }

        if ($catId > 0) {
            $check = db_fetch_one("SELECT id FROM categories WHERE slug = :s AND id != :id", ['s' => $slug, 'id' => $catId]);
            if ($check) $slug .= '-' . rand(10, 99);

            $updateData = [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'display_order' => $displayOrder
            ];
            if ($imageUrl) {
                $updateData['image_url'] = $imageUrl;
            }

            db_update('categories', $updateData, 'id = :id', ['id' => $catId]);
            log_activity($adminUser['id'], 'update_category', "Updated category: {$name}");
            set_flash('success', "Category '{$name}' updated successfully.");
        } else {
            $check = db_fetch_one("SELECT id FROM categories WHERE slug = :s", ['s' => $slug]);
            if ($check) $slug .= '-' . rand(10, 99);

            db_insert('categories', [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'image_url' => $imageUrl ?: 'assets/images/gallery_1.jpg',
                'display_order' => $displayOrder
            ]);

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
            // Unlink products
            db_update('products', ['category_id' => null], 'category_id = :cid', ['cid' => $catId]);
            db_delete('categories', 'id = :id', ['id' => $catId]);
            log_activity($adminUser['id'], 'delete_category', "Deleted category: {$cat['name']}");
            set_flash('success', "Category '{$cat['name']}' deleted.");
        }
        header('Location: ' . url('admin/categories.php'));
        exit;
    }
}

// Fetch categories with product counts
$categories = db_fetch_all("
    SELECT c.*, COUNT(p.id) as product_count
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id
    ORDER BY c.display_order ASC, c.name ASC
");

$editCatId = intval($_GET['edit_id'] ?? 0);
$editCategory = null;
if ($editCatId > 0) {
    $editCategory = db_fetch_one("SELECT * FROM categories WHERE id = :id", ['id' => $editCatId]);
}
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Manage Product Categories
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Organize products into DTF, UV DTF, Apparel, Corporate Merchandise, and Signage collections.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('admin/products.php') ?>" class="btn btn-navy btn-sm">
      &larr; Products Catalog
    </a>
  </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1.6fr; gap:1.75rem; align-items:flex-start;">

  <!-- Form Column -->
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
    <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.5rem;">
      <?= ($editCategory) ? 'Edit Category: ' . e($editCategory['name']) : 'Create New Category' ?>
    </h3>

    <form method="POST" action="<?= url('admin/categories.php') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_category">
      <input type="hidden" name="category_id" value="<?= $editCategory['id'] ?? 0 ?>">
      <input type="hidden" name="current_image_url" value="<?= e($editCategory['image_url'] ?? '') ?>">

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Category Name *</label>
        <input type="text" name="name" class="form-control" required placeholder="e.g. DTF Gang Sheets" value="<?= e($editCategory['name'] ?? '') ?>">
      </div>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Slug</label>
        <input type="text" name="slug" class="form-control" placeholder="e.g. dtf-gang-sheets" value="<?= e($editCategory['slug'] ?? '') ?>">
      </div>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Short description of this category..."><?= e($editCategory['description'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Category Thumbnail Image</label>
        <input type="file" name="category_image" class="form-control" accept="image/*">
        <?php if (!empty($editCategory['image_url'])): ?>
          <div style="margin-top:0.5rem; display:flex; align-items:center; gap:0.5rem;">
            <img src="<?= url(e($editCategory['image_url'])) ?>" alt="Preview" style="width:40px; height:40px; object-fit:cover; border-radius:4px; border:1px solid var(--border-light);">
            <small style="color:var(--text-muted); font-size:0.75rem;"><?= e($editCategory['image_url']) ?></small>
          </div>
        <?php endif; ?>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Display Order</label>
        <input type="number" name="display_order" class="form-control" value="<?= e($editCategory['display_order'] ?? '0') ?>">
      </div>

      <div style="display:flex; gap:0.75rem;">
        <button type="submit" class="btn btn-primary btn-sm">
          <?= ($editCategory) ? 'Update Category' : 'Save Category' ?>
        </button>
        <?php if ($editCategory): ?>
          <a href="<?= url('admin/categories.php') ?>" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Table Column -->
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
    <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.5rem;">
      Categories (<?= count($categories) ?>)
    </h3>

    <div class="table-responsive">
      <table class="data-table" style="width:100%; font-size:0.88rem;">
        <thead>
          <tr>
            <th>Image</th>
            <th>Category Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td style="width:50px;">
                <img src="<?= url(e($cat['image_url'] ?? 'assets/images/gallery_1.jpg')) ?>" alt="" style="width:38px; height:38px; border-radius:4px; object-fit:cover; border:1px solid var(--border-light);">
              </td>
              <td>
                <strong><?= e($cat['name']) ?></strong>
                <?php if (!empty($cat['description'])): ?>
                  <div style="font-size:0.75rem; color:var(--text-muted);"><?= e(substr($cat['description'], 0, 50)) ?>...</div>
                <?php endif; ?>
              </td>
              <td><code><?= e($cat['slug']) ?></code></td>
              <td>
                <span class="badge" style="background:rgba(29,78,216,0.08); color:var(--royal); font-weight:700;">
                  <?= $cat['product_count'] ?> products
                </span>
              </td>
              <td style="text-align:right; white-space:nowrap;">
                <a href="<?= url('admin/categories.php?edit_id=' . $cat['id']) ?>" class="btn btn-xs btn-outline">✎ Edit</a>
                <form method="POST" action="<?= url('admin/categories.php') ?>" style="display:inline;" onsubmit="return confirm('Delete category \'<?= addslashes($cat['name']) ?>\'? Associated products will be unassigned.');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete_category">
                  <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                  <button type="submit" class="btn btn-xs btn-danger">✕</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
