<?php
/**
 * OrderPrintex - Admin Product Variations Management
 * Global Overview & Quick Inline Update for All Product SKUs
 */

$adminTitle = "Product Variations & SKU Workbench";
require_once __DIR__ . '/header.php';

// Handle Quick Updates
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/variations.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Quick Update Single Variation
    if ($action === 'quick_update') {
        $varId = intval($_POST['variation_id'] ?? 0);
        $sku = strtoupper(trim($_POST['sku'] ?? ''));
        $regularPrice = floatval($_POST['regular_price'] ?? 0);
        $salePrice = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $stockQty = intval($_POST['stock_quantity'] ?? 0);
        $isInStock = isset($_POST['is_in_stock']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($varId > 0 && !empty($sku)) {
            // Check SKU uniqueness
            $dup = db_fetch_one("SELECT id FROM product_variations WHERE sku = :sku AND id != :id", ['sku' => $sku, 'id' => $varId]);
            if ($dup) {
                set_flash('error', "SKU '{$sku}' is already assigned to another variation! SKUs must be unique.");
            } else {
                db_update('product_variations', [
                    'sku'            => $sku,
                    'regular_price'  => $regularPrice,
                    'sale_price'     => $salePrice,
                    'stock_quantity' => $stockQty,
                    'is_in_stock'    => $isInStock,
                    'is_active'      => $isActive,
                    'updated_at'     => date('Y-m-d H:i:s')
                ], 'id = :id', ['id' => $varId]);

                set_flash('success', "Variation [SKU: {$sku}] updated successfully.");
            }
        }
        header('Location: ' . url('admin/variations.php' . (!empty($_GET['product_id']) ? '?product_id=' . intval($_GET['product_id']) : '')));
        exit;
    }

    // 2. Delete Single Variation
    if ($action === 'delete_variation') {
        $varId = intval($_POST['variation_id'] ?? 0);
        $v = db_fetch_one("SELECT sku FROM product_variations WHERE id = :id", ['id' => $varId]);
        if ($v) {
            db_delete('product_variation_values', 'variation_id = :id', ['id' => $varId]);
            db_delete('product_variations', 'id = :id', ['id' => $varId]);
            set_flash('success', "Variation [SKU: {$v['sku']}] was removed.");
        }
        header('Location: ' . url('admin/variations.php'));
        exit;
    }
}

// Filters & Search
$productIdFilter = intval($_GET['product_id'] ?? 0);
$search = trim($_GET['search'] ?? '');
$stockFilter = trim($_GET['stock_status'] ?? '');

$where = ["1=1"];
$params = [];

if ($productIdFilter > 0) {
    $where[] = "pv.product_id = :pid";
    $params['pid'] = $productIdFilter;
}

if (!empty($search)) {
    $where[] = "(pv.sku LIKE :s OR p.title LIKE :s OR p.sku LIKE :s)";
    $params['s'] = "%{$search}%";
}

if ($stockFilter === 'in_stock') {
    $where[] = "pv.is_in_stock = 1 AND pv.stock_quantity > 10";
} elseif ($stockFilter === 'low_stock') {
    $where[] = "pv.is_in_stock = 1 AND pv.stock_quantity <= 10 AND pv.stock_quantity > 0";
} elseif ($stockFilter === 'out_of_stock') {
    $where[] = "(pv.is_in_stock = 0 OR pv.stock_quantity <= 0)";
}

$whereClause = implode(' AND ', $where);

// Fetch variations with combination strings
$variations = db_fetch_all("
    SELECT pv.*, p.title as product_title, p.slug as product_slug, p.sku as product_base_sku,
           GROUP_CONCAT(a.name || ': ' || av.value, ' | ') as combination_summary
    FROM product_variations pv
    JOIN products p ON pv.product_id = p.id
    LEFT JOIN product_variation_values pvv ON pv.id = pvv.variation_id
    LEFT JOIN attribute_values av ON pvv.attribute_value_id = av.id
    LEFT JOIN attributes a ON av.attribute_id = a.id
    WHERE {$whereClause}
    GROUP BY pv.id
    ORDER BY p.title ASC, pv.id ASC
", $params);

// Fetch all products for filter dropdown
$productsList = db_fetch_all("
    SELECT p.id, p.title, p.sku, COUNT(pv.id) as var_count
    FROM products p
    LEFT JOIN product_variations pv ON p.id = pv.product_id
    GROUP BY p.id
    ORDER BY p.title ASC
");

// Stats
$totalVariationsCount = db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations")['cnt'] ?? 0;
$outOfStockCount = db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations WHERE is_in_stock = 0 OR stock_quantity <= 0")['cnt'] ?? 0;
$lowStockCount = db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations WHERE is_in_stock = 1 AND stock_quantity <= 10 AND stock_quantity > 0")['cnt'] ?? 0;
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Product Variations &amp; SKU Workbench
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Manage all product SKU combinations, variation pricing, stock levels, and warehouse availability.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('admin/attributes.php') ?>" class="btn btn-outline btn-sm">
      ⚙ Manage Attributes
    </a>
    <a href="<?= url('admin/products.php') ?>" class="btn btn-navy btn-sm">
      &larr; Products Catalog
    </a>
  </div>
</div>

<!-- Stat Mini Badges -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:1.75rem;">
  <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:1rem 1.25rem; box-shadow:0 2px 8px rgba(15, 25, 45, 0.06);">
    <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--slate);">Total Active Variations</div>
    <div style="font-size:1.6rem; font-weight:800; color:var(--navy); margin-top:0.25rem;"><?= $totalVariationsCount ?></div>
  </div>
  <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:1rem 1.25rem; box-shadow:0 2px 8px rgba(15, 25, 45, 0.06);">
    <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#D97706;">Low Stock Alert (≤ 10)</div>
    <div style="font-size:1.6rem; font-weight:800; color:#D97706; margin-top:0.25rem;"><?= $lowStockCount ?></div>
  </div>
  <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:1rem 1.25rem; box-shadow:0 2px 8px rgba(15, 25, 45, 0.06);">
    <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#DC2626;">Out of Stock</div>
    <div style="font-size:1.6rem; font-weight:800; color:#DC2626; margin-top:0.25rem;"><?= $outOfStockCount ?></div>
  </div>
</div>

<!-- Filter Bar -->
<div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.06); margin-bottom:1.5rem;">
  <form method="GET" action="<?= url('admin/variations.php') ?>" style="display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end;">
    <div style="flex:1; min-width:200px;">
      <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Search by SKU or Title</label>
      <input type="text" name="search" class="form-control" placeholder="e.g. STR-M-BLK or Streetwear" value="<?= e($search) ?>">
    </div>

    <div style="min-width:220px;">
      <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Filter by Product</label>
      <select name="product_id" class="form-control">
        <option value="">All Products (<?= count($productsList) ?>)</option>
        <?php foreach ($productsList as $pl): ?>
          <option value="<?= $pl['id'] ?>" <?= ($productIdFilter === (int)$pl['id']) ? 'selected' : '' ?>>
            <?= e($pl['title']) ?> (<?= $pl['var_count'] ?> variations)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="min-width:160px;">
      <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Stock Status</label>
      <select name="stock_status" class="form-control">
        <option value="">All Inventory</option>
        <option value="in_stock" <?= ($stockFilter === 'in_stock') ? 'selected' : '' ?>>In Stock</option>
        <option value="low_stock" <?= ($stockFilter === 'low_stock') ? 'selected' : '' ?>>Low Stock (≤10)</option>
        <option value="out_of_stock" <?= ($stockFilter === 'out_of_stock') ? 'selected' : '' ?>>Out of Stock</option>
      </select>
    </div>

    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary btn-sm" style="height:38px;">Filter</button>
      <?php if (!empty($search) || $productIdFilter > 0 || !empty($stockFilter)): ?>
        <a href="<?= url('admin/variations.php') ?>" class="btn btn-outline btn-sm" style="height:38px; display:inline-flex; align-items:center;">Clear</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Variations Table -->
<div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow:0 4px 14px rgba(15, 25, 45, 0.08);">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
    <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin:0;">
      Variations (<?= count($variations) ?> matches)
    </h3>
    <small style="color:var(--text-muted); font-size:0.8rem;">
      Tip: Edit price, stock or SKU directly in each row and click "Save" to commit changes.
    </small>
  </div>

  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.88rem;">
      <thead>
        <tr>
          <th>Product &amp; Attributes</th>
          <th style="min-width:130px;">Unique SKU</th>
          <th style="min-width:100px;">Regular Price</th>
          <th style="min-width:100px;">Sale Price</th>
          <th style="min-width:90px;">Stock Qty</th>
          <th>Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($variations)): ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
              No variations found matching your filter criteria.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($variations as $var): ?>
            <tr>
              <form method="POST" action="<?= url('admin/variations.php') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="quick_update">
                <input type="hidden" name="variation_id" value="<?= $var['id'] ?>">

                <td>
                  <strong>
                    <a href="<?= url('admin/products.php?edit_id=' . $var['product_id']) ?>" style="color:var(--navy); text-decoration:none;">
                      <?= e($var['product_title']) ?>
                    </a>
                  </strong>
                  <div style="font-size:0.78rem; color:var(--royal); font-weight:600; margin-top:2px;">
                    <?= e($var['combination_summary'] ?: $var['combination_key']) ?>
                  </div>
                </td>

                <td>
                  <input type="text" name="sku" value="<?= e($var['sku']) ?>" class="form-control" style="font-weight:700; font-size:0.825rem; height:32px; text-transform:uppercase;" required>
                </td>

                <td>
                  <div style="display:flex; align-items:center; gap:3px;">
                    <span style="font-size:0.8rem; color:var(--text-muted);">₹</span>
                    <input type="number" step="0.01" name="regular_price" value="<?= $var['regular_price'] ?>" class="form-control" style="height:32px; font-size:0.85rem;" required>
                  </div>
                </td>

                <td>
                  <div style="display:flex; align-items:center; gap:3px;">
                    <span style="font-size:0.8rem; color:var(--text-muted);">₹</span>
                    <input type="number" step="0.01" name="sale_price" value="<?= $var['sale_price'] ?>" class="form-control" style="height:32px; font-size:0.85rem;" placeholder="None">
                  </div>
                </td>

                <td>
                  <input type="number" name="stock_quantity" value="<?= $var['stock_quantity'] ?>" class="form-control" style="height:32px; font-size:0.85rem; width:80px;" min="0">
                </td>

                <td>
                  <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.75rem; cursor:pointer;">
                    <input type="checkbox" name="is_in_stock" value="1" <?= $var['is_in_stock'] ? 'checked' : '' ?>>
                    <span>In Stock</span>
                  </label>
                  <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.75rem; cursor:pointer; margin-left:6px;">
                    <input type="checkbox" name="is_active" value="1" <?= $var['is_active'] ? 'checked' : '' ?>>
                    <span>Active</span>
                  </label>
                </td>

                <td style="text-align:right; white-space:nowrap;">
                  <button type="submit" class="btn btn-xs btn-primary" title="Save Row Changes">Save</button>
                  <a href="<?= product_url($var['product_slug']) ?>" target="_blank" class="btn btn-xs btn-outline" title="View on site">👁</a>
                  <button type="button" class="btn btn-xs btn-danger" onclick="if(confirm('Delete variation [<?= addslashes($var['sku']) ?>]?')) { document.getElementById('del_var_<?= $var['id'] ?>').submit(); }">✕</button>
                </td>
              </form>

              <!-- Hidden delete form -->
              <form id="del_var_<?= $var['id'] ?>" method="POST" action="<?= url('admin/variations.php') ?>" style="display:none;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete_variation">
                <input type="hidden" name="variation_id" value="<?= $var['id'] ?>">
              </form>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
