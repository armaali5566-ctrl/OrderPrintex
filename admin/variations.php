<?php
/**
 * OrderPrintex - Admin Product Variations Management & SKU Workbench
 * High-Performance Global Overview, Inline Batch Editor & Variation Generator
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

// Unified Product Management: Variations are now managed directly inside admin/products.php
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
    $pId = intval($_GET['product_id'] ?? 0);
    if ($pId > 0) {
        header('Location: ' . url('admin/products.php?action=edit&id=' . $pId . '#tab-variations'));
    } else {
        header('Location: ' . url('admin/products.php'));
    }
    exit;
}

// =========================================================================
// 1. POST PROCESSING (BEFORE ANY HTML OUTPUT TO PREVENT HEADER WARNINGS)
// =========================================================================
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security session expired. Please refresh and try again.');
        header('Location: ' . url('admin/variations.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1A. Quick Inline Update Single Variation
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
                set_flash('error', "SKU '{$sku}' is already assigned to another variation! SKUs must be unique across all products.");
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
        } else {
            set_flash('error', 'Invalid variation ID or SKU cannot be blank.');
        }

        $redirectQuery = !empty($_GET['product_id']) ? '?product_id=' . intval($_GET['product_id']) : '';
        header('Location: ' . url('admin/variations.php' . $redirectQuery));
        exit;
    }

    // 1B. Create New Variation
    if ($action === 'create_variation') {
        $productId = intval($_POST['product_id'] ?? 0);
        $sku = strtoupper(trim($_POST['sku'] ?? ''));
        $regularPrice = floatval($_POST['regular_price'] ?? 0);
        $salePrice = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $stockQty = intval($_POST['stock_quantity'] ?? 100);
        $attributeValueIds = $_POST['attribute_value_ids'] ?? [];

        if ($productId <= 0) {
            set_flash('error', 'Please select a valid parent product for the variation.');
        } elseif (empty($sku)) {
            set_flash('error', 'Please enter a unique SKU for this variation.');
        } else {
            // Check SKU uniqueness
            $dup = db_fetch_one("SELECT id FROM product_variations WHERE sku = :sku", ['sku' => $sku]);
            if ($dup) {
                set_flash('error', "SKU '{$sku}' already exists in inventory! Please assign a unique SKU code.");
            } else {
                db_begin_transaction();
                try {
                    $newVarId = db_insert('product_variations', [
                        'product_id'     => $productId,
                        'sku'            => $sku,
                        'regular_price'  => $regularPrice,
                        'sale_price'     => $salePrice,
                        'stock_quantity' => $stockQty,
                        'is_in_stock'    => 1,
                        'is_active'      => 1,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ]);

                    // Insert attribute mappings
                    if (!empty($attributeValueIds) && is_array($attributeValueIds)) {
                        foreach ($attributeValueIds as $attrValId) {
                            $valId = intval($attrValId);
                            if ($valId > 0) {
                                db_insert('product_variation_values', [
                                    'variation_id'        => $newVarId,
                                    'attribute_value_id' => $valId
                                ]);
                            }
                        }
                    }

                    db_commit();
                    set_flash('success', "New variation [SKU: {$sku}] created successfully.");
                } catch (Exception $e) {
                    db_rollback();
                    set_flash('error', 'Failed to create variation: ' . $e->getMessage());
                }
            }
        }

        header('Location: ' . url('admin/variations.php?product_id=' . $productId));
        exit;
    }

    // 1C. Delete Single Variation
    if ($action === 'delete_variation') {
        $varId = intval($_POST['variation_id'] ?? 0);
        $v = db_fetch_one("SELECT sku FROM product_variations WHERE id = :id", ['id' => $varId]);
        if ($v) {
            db_begin_transaction();
            try {
                db_delete('product_variation_values', 'variation_id = :id', ['id' => $varId]);
                db_delete('product_variations', 'id = :id', ['id' => $varId]);
                db_commit();
                set_flash('success', "Variation [SKU: {$v['sku']}] was permanently removed.");
            } catch (Exception $e) {
                db_rollback();
                set_flash('error', 'Failed to delete variation: ' . $e->getMessage());
            }
        }
        header('Location: ' . url('admin/variations.php'));
        exit;
    }
}

// =========================================================================
// 2. DATA QUERY & FILTER PREPARATION
// =========================================================================
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

// Dialect-aware group concat for combinations
$concatField = Database::getActiveDriver() === 'mysql'
    ? "GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ' | ')"
    : "GROUP_CONCAT(a.name || ': ' || av.value, ' | ')";

$variations = db_fetch_all("
    SELECT pv.*, p.title as product_title, p.slug as product_slug, p.sku as product_base_sku,
           {$concatField} as combination_summary
    FROM product_variations pv
    JOIN products p ON pv.product_id = p.id
    LEFT JOIN product_variation_values pvv ON pv.id = pvv.variation_id
    LEFT JOIN attribute_values av ON pvv.attribute_value_id = av.id
    LEFT JOIN attributes a ON av.attribute_id = a.id
    WHERE {$whereClause}
    GROUP BY pv.id
    ORDER BY p.title ASC, pv.id ASC
", $params);

// Fetch products list for dropdown
$productsList = db_fetch_all("
    SELECT p.id, p.title, p.sku, COUNT(pv.id) as var_count
    FROM products p
    LEFT JOIN product_variations pv ON p.id = pv.product_id
    GROUP BY p.id
    ORDER BY p.title ASC
");

// Fetch available attributes and their values for creating variations
$allAttributes = db_fetch_all("SELECT * FROM attributes ORDER BY id ASC");
$allAttrValues = db_fetch_all("SELECT * FROM attribute_values ORDER BY attribute_id ASC, display_order ASC");
$groupedValues = [];
foreach ($allAttrValues as $av) {
    $groupedValues[$av['attribute_id']][] = $av;
}

// Stats
$totalVariationsCount = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations")['cnt'] ?? 0);
$outOfStockCount      = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations WHERE is_in_stock = 0 OR stock_quantity <= 0")['cnt'] ?? 0);
$lowStockCount        = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM product_variations WHERE is_in_stock = 1 AND stock_quantity <= 10 AND stock_quantity > 0")['cnt'] ?? 0);

// =========================================================================
// 3. RENDER HTML (HEADER INCLUDED AFTER LOGIC)
// =========================================================================
$adminTitle = "Product Variations & SKU Workbench";
require_once __DIR__ . '/header.php';
?>

<!-- Action Topbar -->
<div class="panel-header" style="margin-bottom: 1.5rem; background: var(--admin-bg-card); padding: 1.25rem 1.75rem; border-radius: var(--radius-md); border: 1px solid var(--admin-border-card);">
  <div>
    <h1 style="font-size: 1.4rem; font-weight: 700; color: var(--admin-text-main); margin: 0;">
      Product Variations &amp; SKU Workbench
    </h1>
    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0.25rem 0 0 0;">
      Centralized SKU management across size combinations, material finishes, and dynamic inventory pricing.
    </p>
  </div>
  <div class="panel-actions">
    <button type="button" class="btn btn-primary" data-modal-open="createVariationModal">
      + New SKU Variation
    </button>
    <a href="<?= url('admin/attributes.php') ?>" class="btn btn-outline">
      ⚙ Manage Attributes
    </a>
    <a href="<?= url('admin/products.php') ?>" class="btn btn-secondary">
      &larr; Products Catalog
    </a>
  </div>
</div>

<!-- KPI Mini Stat Cards -->
<div class="metrics-grid">
  <div class="metric-card">
    <div>
      <div class="metric-label">Active Variations</div>
      <div class="metric-value"><?= $totalVariationsCount ?></div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem;">Total tracked SKUs</div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">Low Stock (≤ 10)</div>
      <div class="metric-value" style="color: #D97706;"><?= $lowStockCount ?></div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem;">Reorder recommended</div>
    </div>
    <div class="metric-icon-box" style="background:rgba(245, 158, 11, 0.12); color:#D97706;">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">Out of Stock</div>
      <div class="metric-value" style="color: #DC2626;"><?= $outOfStockCount ?></div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem;">Unavailable for checkout</div>
    </div>
    <div class="metric-icon-box" style="background:rgba(239, 68, 68, 0.12); color:#DC2626;">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
      </svg>
    </div>
  </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="panel-card" style="margin-bottom: 1.5rem;">
  <div style="padding: 1.25rem 1.5rem;">
    <form method="GET" action="<?= url('admin/variations.php') ?>" class="toolbar-form" style="gap: 1rem;">
      <div class="search-input-wrap" style="flex: 1; min-width: 220px;">
        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
        </svg>
        <input type="text" name="search" class="form-control" placeholder="Search by SKU, title or code..." value="<?= e($search) ?>">
      </div>

      <div style="min-width: 240px;">
        <select name="product_id" class="form-control">
          <option value="">All Products (<?= count($productsList) ?>)</option>
          <?php foreach ($productsList as $pl): ?>
            <option value="<?= $pl['id'] ?>" <?= ($productIdFilter === (int)$pl['id']) ? 'selected' : '' ?>>
              <?= e($pl['title']) ?> (<?= $pl['var_count'] ?> variations)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="min-width: 170px;">
        <select name="stock_status" class="form-control">
          <option value="">All Inventory Status</option>
          <option value="in_stock" <?= ($stockFilter === 'in_stock') ? 'selected' : '' ?>>In Stock (>10)</option>
          <option value="low_stock" <?= ($stockFilter === 'low_stock') ? 'selected' : '' ?>>Low Stock (≤10)</option>
          <option value="out_of_stock" <?= ($stockFilter === 'out_of_stock') ? 'selected' : '' ?>>Out of Stock</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-sm">Filter SKUs</button>
      <?php if (!empty($search) || $productIdFilter > 0 || !empty($stockFilter)): ?>
        <a href="<?= url('admin/variations.php') ?>" class="btn btn-outline btn-sm">Reset</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Variations Data Table -->
<div class="panel-card">
  <div class="panel-header">
    <div class="panel-title">
      SKU Inventory Directory (<?= count($variations) ?> records)
    </div>
    <span style="font-size:0.8rem; color:var(--admin-text-muted);">
      Edit regular/sale prices, stock quantities and SKU identifiers inline and click <strong>Save</strong>.
    </span>
  </div>

  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th>Parent Product &amp; Specification</th>
          <th style="min-width: 140px;">Unique SKU</th>
          <th style="min-width: 110px;">Regular Price</th>
          <th style="min-width: 110px;">Sale Price</th>
          <th style="min-width: 90px;">Stock</th>
          <th>Status</th>
          <th style="text-align: right; min-width: 140px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($variations)): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--admin-text-muted);">
              No variations found matching the specified criteria. Click <strong>+ New SKU Variation</strong> to create one.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($variations as $var): ?>
            <tr>
              <td>
                <strong>
                  <a href="<?= url('admin/products.php?action=edit&id=' . $var['product_id']) ?>" style="color:var(--admin-text-main); text-decoration:none;">
                    <?= e($var['product_title']) ?>
                  </a>
                </strong>
                <div style="font-size:0.78rem; color:var(--admin-gold); font-weight:600; margin-top:2px;">
                  <?= e($var['combination_summary'] ?: 'Standard Default Variation') ?>
                </div>
              </td>

              <!-- Separate Form per Row using HTML5 standard attribute -->
              <form id="row_form_<?= $var['id'] ?>" method="POST" action="<?= url('admin/variations.php' . ($productIdFilter ? '?product_id=' . $productIdFilter : '')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="quick_update">
                <input type="hidden" name="variation_id" value="<?= $var['id'] ?>">
              </form>

              <td>
                <input form="row_form_<?= $var['id'] ?>" type="text" name="sku" value="<?= e($var['sku']) ?>" class="form-control" style="font-weight:700; font-size:0.825rem; height:34px; text-transform:uppercase;" required>
              </td>

              <td>
                <div style="display:flex; align-items:center; gap:4px;">
                  <span style="font-size:0.8rem; color:var(--admin-text-muted);">₹</span>
                  <input form="row_form_<?= $var['id'] ?>" type="number" step="0.01" name="regular_price" value="<?= $var['regular_price'] ?>" class="form-control" style="height:34px; font-size:0.85rem;" required>
                </div>
              </td>

              <td>
                <div style="display:flex; align-items:center; gap:4px;">
                  <span style="font-size:0.8rem; color:var(--admin-text-muted);">₹</span>
                  <input form="row_form_<?= $var['id'] ?>" type="number" step="0.01" name="sale_price" value="<?= $var['sale_price'] ?>" class="form-control" style="height:34px; font-size:0.85rem;" placeholder="None">
                </div>
              </td>

              <td>
                <input form="row_form_<?= $var['id'] ?>" type="number" name="stock_quantity" value="<?= $var['stock_quantity'] ?>" class="form-control" style="height:34px; font-size:0.85rem; width:80px;" min="0">
              </td>

              <td>
                <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.75rem; cursor:pointer;">
                  <input form="row_form_<?= $var['id'] ?>" type="checkbox" name="is_in_stock" value="1" <?= $var['is_in_stock'] ? 'checked' : '' ?>>
                  <span>In Stock</span>
                </label>
                <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.75rem; cursor:pointer; margin-left:6px;">
                  <input form="row_form_<?= $var['id'] ?>" type="checkbox" name="is_active" value="1" <?= $var['is_active'] ? 'checked' : '' ?>>
                  <span>Active</span>
                </label>
              </td>

              <td style="text-align: right; white-space: nowrap;">
                <button form="row_form_<?= $var['id'] ?>" type="submit" class="btn btn-xs btn-primary" title="Save Row Changes">Save</button>
                <a href="<?= product_url($var['product_slug']) ?>" target="_blank" class="btn btn-xs btn-outline" title="View product on public site">👁</a>
                <button type="button" class="btn btn-xs btn-danger" data-confirm="Are you sure you want to permanently delete SKU [<?= e($var['sku']) ?>]?" onclick="document.getElementById('del_var_<?= $var['id'] ?>').submit();">✕</button>

                <form id="del_var_<?= $var['id'] ?>" method="POST" action="<?= url('admin/variations.php') ?>" style="display:none;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete_variation">
                  <input type="hidden" name="variation_id" value="<?= $var['id'] ?>">
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Create New Variation -->
<div class="modal-backdrop" id="createVariationModal">
  <div class="modal-dialog">
    <div class="modal-header">
      <div class="modal-title">Create Product SKU Variation</div>
      <button type="button" class="modal-close" data-modal-close aria-label="Close">&times;</button>
    </div>
    <form method="POST" action="<?= url('admin/variations.php') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="create_variation">
      
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Parent Product *</label>
          <select name="product_id" class="form-control" required>
            <option value="">Select Target Product...</option>
            <?php foreach ($productsList as $pl): ?>
              <option value="<?= $pl['id'] ?>" <?= ($productIdFilter === (int)$pl['id']) ? 'selected' : '' ?>>
                <?= e($pl['title']) ?> (Base SKU: <?= e($pl['sku'] ?: 'None') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Unique Variation SKU Code *</label>
          <input type="text" name="sku" class="form-control" placeholder="e.g. DTF-22X40-ROLL" style="text-transform:uppercase;" required>
          <div class="form-hint">Must be globally unique across all catalog products.</div>
        </div>

        <div class="admin-form-2col">
          <div class="form-group">
            <label class="form-label">Regular Price (₹) *</label>
            <input type="number" step="0.01" name="regular_price" class="form-control" placeholder="0.00" required>
          </div>
          <div class="form-group">
            <label class="form-label">Sale Price (₹)</label>
            <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="Optional discount">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Initial Stock Quantity</label>
          <input type="number" name="stock_quantity" class="form-control" value="100" min="0">
        </div>

        <?php if (!empty($allAttributes)): ?>
          <div style="margin-top: 1.25rem; border-top: 1px solid var(--admin-border-subtle); padding-top: 1rem;">
            <div class="form-label" style="margin-bottom: 0.75rem;">Associate Attributes (Optional)</div>
            <?php foreach ($allAttributes as $attr): ?>
              <?php if (!empty($groupedValues[$attr['id']])): ?>
                <div class="form-group" style="margin-bottom: 0.75rem;">
                  <label style="font-size:0.8rem; font-weight:600; color:var(--admin-text-main); display:block; margin-bottom:0.25rem;">
                    <?= e($attr['name']) ?>:
                  </label>
                  <select name="attribute_value_ids[]" class="form-control">
                    <option value="">-- None / Default --</option>
                    <?php foreach ($groupedValues[$attr['id']] as $av): ?>
                      <option value="<?= $av['id'] ?>"><?= e($av['value']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Create Variation</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
