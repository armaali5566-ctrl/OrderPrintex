<?php
/**
 * OrderPrintex - Admin Product Attributes Management
 * Create, Edit & Delete Attributes (Size, Colour, Material) and their Values
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();
$adminUser = auth_user();

// Unified Product Management: Attributes are now managed directly inside admin/products.php
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
    header('Location: ' . url('admin/products.php'));
    exit;
}

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/attributes.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save Attribute (Add or Edit)
    if ($action === 'save_attribute') {
        $attrId = intval($_POST['attribute_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');

        if (empty($name)) {
            set_flash('error', 'Attribute name is required.');
            header('Location: ' . url('admin/attributes.php'));
            exit;
        }

        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }

        if ($attrId > 0) {
            // Check slug uniqueness
            $check = db_fetch_one("SELECT id FROM attributes WHERE slug = :s AND id != :id", ['s' => $slug, 'id' => $attrId]);
            if ($check) $slug .= '-' . rand(10, 99);

            db_update('attributes', [
                'name' => $name,
                'slug' => $slug
            ], 'id = :id', ['id' => $attrId]);

            log_activity($adminUser['id'], 'update_attribute', "Updated attribute: {$name}");
            set_flash('success', "Attribute '{$name}' updated successfully.");
        } else {
            $check = db_fetch_one("SELECT id FROM attributes WHERE slug = :s", ['s' => $slug]);
            if ($check) $slug .= '-' . rand(10, 99);

            $newId = db_insert('attributes', [
                'name' => $name,
                'slug' => $slug
            ]);

            log_activity($adminUser['id'], 'create_attribute', "Created attribute: {$name}");
            set_flash('success', "Attribute '{$name}' created successfully.");
        }

        header('Location: ' . url('admin/attributes.php'));
        exit;
    }

    // 2. Delete Attribute
    if ($action === 'delete_attribute') {
        $attrId = intval($_POST['attribute_id'] ?? 0);
        $attr = db_fetch_one("SELECT * FROM attributes WHERE id = :id", ['id' => $attrId]);
        if ($attr) {
            db_delete('attribute_values', 'attribute_id = :id', ['id' => $attrId]);
            db_delete('product_attributes', 'attribute_id = :id', ['id' => $attrId]);
            db_delete('attributes', 'id = :id', ['id' => $attrId]);

            log_activity($adminUser['id'], 'delete_attribute', "Deleted attribute: {$attr['name']}");
            set_flash('success', "Attribute '{$attr['name']}' and its values were deleted.");
        }
        header('Location: ' . url('admin/attributes.php'));
        exit;
    }

    // 3. Add Attribute Value
    if ($action === 'add_value') {
        $attrId = intval($_POST['attribute_id'] ?? 0);
        $value = trim($_POST['value'] ?? '');
        $colorCode = trim($_POST['color_code'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if ($attrId > 0 && !empty($value)) {
            db_insert('attribute_values', [
                'attribute_id' => $attrId,
                'value'        => $value,
                'color_code'   => !empty($colorCode) ? $colorCode : null,
                'display_order'=> $displayOrder
            ]);
            set_flash('success', "Added value '{$value}' to attribute.");
        }
        header('Location: ' . url('admin/attributes.php?manage_id=' . $attrId));
        exit;
    }

    // 4. Delete Attribute Value
    if ($action === 'delete_value') {
        $valId = intval($_POST['value_id'] ?? 0);
        $attrId = intval($_POST['attribute_id'] ?? 0);
        if ($valId > 0) {
            db_delete('product_variation_values', 'attribute_value_id = :vid', ['vid' => $valId]);
            db_delete('attribute_values', 'id = :id', ['id' => $valId]);
            set_flash('success', "Attribute value removed.");
        }
        header('Location: ' . url('admin/attributes.php?manage_id=' . $attrId));
        exit;
    }
}

// Fetch all attributes
$attributes = db_fetch_all("
    SELECT a.*, COUNT(DISTINCT av.id) as value_count, COUNT(DISTINCT pa.product_id) as product_count
    FROM attributes a
    LEFT JOIN attribute_values av ON a.id = av.attribute_id
    LEFT JOIN product_attributes pa ON a.id = pa.attribute_id
    GROUP BY a.id
    ORDER BY a.id ASC
");

$manageId = intval($_GET['manage_id'] ?? 0);
$editAttrId = intval($_GET['edit_id'] ?? 0);
$activeAttr = null;
$attrValues = [];

if ($manageId > 0) {
    $activeAttr = db_fetch_one("SELECT * FROM attributes WHERE id = :id", ['id' => $manageId]);
    if ($activeAttr) {
        $attrValues = db_fetch_all("SELECT * FROM attribute_values WHERE attribute_id = :id ORDER BY display_order ASC, id ASC", ['id' => $manageId]);
    }
} elseif ($editAttrId > 0) {
    $activeAttr = db_fetch_one("SELECT * FROM attributes WHERE id = :id", ['id' => $editAttrId]);
}

$adminTitle = "Product Attributes & Options";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Product Attributes &amp; Options
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Configure global variation attributes like Size, Colour, and Material to power product SKU combinations.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('admin/variations.php') ?>" class="btn btn-outline btn-sm">
      ⇄ View All Variations
    </a>
    <a href="<?= url('admin/products.php') ?>" class="btn btn-navy btn-sm">
      &larr; Products Catalog
    </a>
  </div>
</div>

<div class="admin-split-grid" style="align-items:flex-start;">

  <!-- Column 1: Attributes List & Add/Edit Form -->
  <div style="display:flex; flex-direction:column; gap:1.5rem;">
    
    <!-- Add / Edit Attribute Box -->
    <div class="admin-card" style="background:var(--bg-surface, #181818); border:1px solid var(--border-color, #40351E); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.1rem; color:var(--text-primary); font-weight:700; margin-bottom:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">
        <?= ($editAttrId > 0 && $activeAttr) ? 'Edit Attribute: ' . e($activeAttr['name']) : 'Create New Attribute' ?>
      </h3>

      <form method="POST" action="<?= url('admin/attributes.php') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_attribute">
        <input type="hidden" name="attribute_id" value="<?= $activeAttr['id'] ?? 0 ?>">

        <div style="margin-bottom:1rem;">
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:0.35rem;">
            Attribute Name <span style="color:var(--primary-gold);">*</span>
          </label>
          <input type="text" name="name" class="form-control" required placeholder="e.g. Size, Colour, Material, Finish" value="<?= e($activeAttr['name'] ?? '') ?>">
          <small style="color:var(--text-muted); font-size:0.78rem;">Public label shown to customers on the product page.</small>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:0.35rem;">
            Slug (Optional)
          </label>
          <input type="text" name="slug" class="form-control" placeholder="e.g. size, colour, material" value="<?= e($activeAttr['slug'] ?? '') ?>">
          <small style="color:var(--text-muted); font-size:0.78rem;">URL identifier (auto-generated from name if left empty).</small>
        </div>

        <div style="display:flex; gap:0.75rem;">
          <button type="submit" class="btn btn-primary btn-sm">
            <span><?= ($editAttrId > 0) ? 'Update Attribute' : 'Save Attribute' ?></span>
          </button>
          <?php if ($editAttrId > 0): ?>
            <a href="<?= url('admin/attributes.php') ?>" class="btn btn-outline btn-sm">Cancel</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Existing Attributes Table -->
    <div class="admin-card" style="background:var(--bg-surface, #181818); border:1px solid var(--border-color, #40351E); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.1rem; color:var(--text-primary); font-weight:700; margin-bottom:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">
        Existing Attributes (<?= count($attributes) ?>)
      </h3>

      <div class="table-responsive">
        <table class="data-table" style="width:100%; font-size:0.88rem;">
          <thead>
            <tr>
              <th>Attribute</th>
              <th>Values</th>
              <th>Assigned Products</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($attributes)): ?>
              <tr>
                <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">No attributes created yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($attributes as $a): ?>
                <tr style="<?= ($manageId == $a['id']) ? 'background:rgba(29,78,216,0.04);' : '' ?>">
                  <td>
                    <strong><?= e($a['name']) ?></strong>
                    <div style="font-size:0.75rem; color:var(--text-muted);"><?= e($a['slug']) ?></div>
                  </td>
                  <td>
                    <span class="badge" style="background:rgba(29,78,216,0.08); color:var(--royal); font-weight:700;">
                      <?= $a['value_count'] ?> values
                    </span>
                  </td>
                  <td>
                    <span style="font-size:0.8rem; color:var(--slate);">
                      <?= $a['product_count'] ?> products
                    </span>
                  </td>
                  <td style="text-align:right; white-space:nowrap;">
                    <a href="<?= url('admin/attributes.php?manage_id=' . $a['id']) ?>" class="btn btn-xs <?= ($manageId == $a['id']) ? 'btn-primary' : 'btn-outline' ?>" title="Manage Values">
                      Manage Values &rarr;
                    </a>
                    <a href="<?= url('admin/attributes.php?edit_id=' . $a['id']) ?>" class="btn btn-xs btn-outline" title="Edit">
                      ✎
                    </a>
                    <form method="POST" action="<?= url('admin/attributes.php') ?>" style="display:inline;">
                      <?= csrf_field() ?>
                      <input type="hidden" name="action" value="delete_attribute">
                      <input type="hidden" name="attribute_id" value="<?= $a['id'] ?>">
                      <button type="button" data-confirm="Are you sure you want to delete attribute &#039;<?= e($a['name']) ?>&#039;? This will remove all its values and unlink from products." data-confirm-title="Delete Attribute?" data-confirm-item="<?= e($a['name']) ?>" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-xs btn-danger" title="Delete">✕</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- Column 2: Manage Values for Selected Attribute -->
  <div>
    <?php if ($manageId > 0 && $activeAttr): ?>
      <div class="admin-card" style="background:var(--bg-surface, #181818); border:1px solid var(--border-color, #40351E); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">
          <div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--primary-gold); letter-spacing:0.04em;">Managing Values For</div>
            <h2 style="font-size:1.3rem; color:var(--text-primary); font-weight:800; margin:0;"><?= e($activeAttr['name']) ?></h2>
          </div>
          <a href="<?= url('admin/attributes.php') ?>" class="btn btn-xs btn-outline">Close</a>
        </div>

        <!-- Add Value Form -->
        <div style="background:var(--bg-surface-elevated, #202020); border:1px solid var(--border-color); border-radius:8px; padding:1.25rem; margin-bottom:1.5rem;">
          <h4 style="font-size:0.95rem; color:var(--text-primary); font-weight:700; margin-bottom:0.85rem;">Add New Value to <?= e($activeAttr['name']) ?></h4>
          <form method="POST" action="<?= url('admin/attributes.php') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add_value">
            <input type="hidden" name="attribute_id" value="<?= $activeAttr['id'] ?>">

            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:flex-end;">
              <div style="flex: 2 1 180px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem;">Value Label *</label>
                <input type="text" name="value" required class="form-control" placeholder="e.g. Small (S), Bright White, Matte, 240 GSM">
              </div>

              <?php if (stripos($activeAttr['name'], 'colour') !== false || stripos($activeAttr['name'], 'color') !== false): ?>
                <div style="flex: 1 1 90px;">
                  <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem;">Color Hex</label>
                  <input type="color" name="color_code" value="#14233C" class="form-control" style="height:38px; padding:2px; cursor:pointer;">
                </div>
              <?php endif; ?>

              <div style="flex: 1 1 80px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem;">Order</label>
                <input type="number" name="display_order" value="0" class="form-control">
              </div>

              <div style="flex: 0 0 auto;">
                <button type="submit" class="btn btn-primary btn-sm" style="height:38px;">+ Add Value</button>
              </div>
            </div>
          </form>
        </div>

        <!-- Values List -->
        <h4 style="font-size:1rem; color:var(--text-primary); font-weight:700; margin-bottom:0.75rem;">Current Values (<?= count($attrValues) ?>)</h4>
        
        <?php if (empty($attrValues)): ?>
          <div style="text-align:center; padding:2rem; background:var(--bg-surface-elevated); border-radius:6px; color:var(--text-muted); font-size:0.88rem;">
            No values defined yet for this attribute. Add values above.
          </div>
        <?php else: ?>
          <div style="display:flex; flex-direction:column; gap:0.5rem;">
            <?php foreach ($attrValues as $val): ?>
              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 0.85rem; border:1px solid var(--border-color); border-radius:6px; background:var(--bg-surface-elevated, #202020);">
                <div style="display:flex; align-items:center; gap:0.65rem;">
                  <?php if (!empty($val['color_code'])): ?>
                    <span style="width:20px; height:20px; border-radius:50%; background:<?= e($val['color_code']) ?>; border:1px solid rgba(0,0,0,0.2); box-shadow:0 1px 3px rgba(0,0,0,0.1); display:inline-block;"></span>
                  <?php endif; ?>
                  <span style="font-weight:700; color:var(--text-primary); font-size:0.9rem;"><?= e($val['value']) ?></span>
                  <?php if (!empty($val['color_code'])): ?>
                    <code style="font-size:0.75rem; background:rgba(0,0,0,0.3); padding:2px 5px; border-radius:3px; color:var(--primary-gold);"><?= e($val['color_code']) ?></code>
                  <?php endif; ?>
                </div>

                <div style="display:flex; align-items:center; gap:0.75rem;">
                  <span style="font-size:0.75rem; color:var(--text-muted);">Order: <?= $val['display_order'] ?></span>
                  <form method="POST" action="<?= url('admin/attributes.php') ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_value">
                    <input type="hidden" name="value_id" value="<?= $val['id'] ?>">
                    <input type="hidden" name="attribute_id" value="<?= $activeAttr['id'] ?>">
                    <button type="button" data-confirm="Are you sure you want to remove value &#039;<?= e($val['value']) ?>&#039;?" data-confirm-title="Remove Value?" data-confirm-item="<?= e($val['value']) ?>" data-destructive="true" data-confirm-btn="Remove" class="btn btn-xs btn-danger" style="padding:2px 7px;">✕</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    <?php else: ?>
      <!-- Welcome instructions if no attribute selected -->
      <div class="admin-card" style="background:var(--bg-surface, #181818); border:1px dashed var(--border-color); border-radius:var(--radius-md); padding:2.5rem 2rem; text-align:center; box-shadow: var(--shadow-sm);">
        <div style="font-size:2.5rem; margin-bottom:0.75rem; color:var(--primary-gold);">⚙</div>
        <h3 style="font-size:1.15rem; color:var(--text-primary); font-weight:700; margin-bottom:0.5rem;">Select an Attribute to Manage Values</h3>
        <p style="color:var(--text-muted); font-size:0.88rem; max-width:400px; margin:0 auto 1.5rem;">
          Click "Manage Values &rarr;" on any attribute to configure specific sizing tiers (S, M, L, XL), color swatches, or garment substrates.
        </p>
        <?php if (!empty($attributes)): ?>
          <a href="<?= url('admin/attributes.php?manage_id=' . $attributes[0]['id']) ?>" class="btn btn-primary btn-sm">
            Manage <?= e($attributes[0]['name']) ?> Values &rarr;
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
