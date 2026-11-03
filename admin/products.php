<?php
/**
 * OrderPrintex - Admin Product Management
 * Complete Dynamic SKU, Variations Builder, 4 Scroll Sections, Images & Inventory Control
 */

$adminTitle = "Manage Products Catalog";
require_once __DIR__ . '/header.php';

// Helper for SKU abbreviation
function generate_val_code(string $val): string {
    if (preg_match('/\(([A-Za-z0-9]+)\)/', $val, $m)) {
        return strtoupper($m[1]);
    }
    $clean = trim(preg_replace('/[^A-Za-z0-9\s]/', '', $val));
    $words = array_values(array_filter(explode(' ', $clean)));
    if (empty($words)) return 'VAR';
    if (count($words) === 1) {
        return strtoupper(substr($words[0], 0, 3));
    }
    $abbr = '';
    foreach ($words as $w) {
        if (!empty($w)) $abbr .= strtoupper($w[0]);
    }
    return substr($abbr, 0, 4);
}

// Cartesian product helper for variation generation
function build_combinations(array $arrays): array {
    $result = [[]];
    foreach ($arrays as $key => $values) {
        $append = [];
        foreach ($result as $product) {
            foreach ($values as $item) {
                $product[$key] = $item;
                $append[] = $product;
            }
        }
        $result = $append;
    }
    return $result;
}

// Handle All Product & Variation POST Actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/products.php'));
        exit;
    }

    $action = $_POST['action'];

    // =========================================================================
    // 1. SAVE / UPDATE PRODUCT
    // =========================================================================
    if ($action === 'save_product') {
        $productId = intval($_POST['product_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $sku = strtoupper(trim($_POST['sku'] ?? ''));
        $categoryId = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $serviceId = !empty($_POST['service_id']) ? intval($_POST['service_id']) : null;
        $badge = trim($_POST['badge'] ?? '');
        $startingPrice = trim($_POST['starting_price'] ?? '₹249');
        $priceUnit = trim($_POST['price_unit'] ?? '/ Unit');
        $minOrder = trim($_POST['min_order'] ?? 'Min. 1');
        $regularPrice = floatval($_POST['regular_price'] ?? 0);
        $salePrice = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $stockQuantity = intval($_POST['stock_quantity'] ?? 50);
        $stockStatus = trim($_POST['stock_status'] ?? 'in_stock');
        $shortDesc = trim($_POST['short_desc'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $specifications = trim($_POST['specifications'] ?? '');
        $artworkNotes = trim($_POST['artwork_notes'] ?? '');
        $applicationGuide = trim($_POST['application_guide'] ?? '');
        $shippingInfo = trim($_POST['shipping_info'] ?? '');
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($title)) {
            set_flash('error', 'Product title is required.');
            header('Location: ' . url('admin/products.php' . ($productId ? '?edit_id=' . $productId : '?action=new')));
            exit;
        }

        // Auto-generate SKU if blank
        if (empty($sku)) {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $title), 0, 3));
            if (strlen($prefix) < 3) $prefix = 'PRD';
            $sku = sprintf('%s-%03d', $prefix, rand(10, 999));
        }

        // Check SKU uniqueness across products
        $skuCheck = db_fetch_one("SELECT id FROM products WHERE sku = :sku AND id != :id", ['sku' => $sku, 'id' => $productId]);
        if ($skuCheck) {
            $sku .= '-' . rand(10, 99);
        }

        // Generate / sanitize slug
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
        }

        // Handle Image Upload if provided
        $imageUrl = null;
        if (!empty($_FILES['product_image']['name'])) {
            $uploadResult = handle_file_upload($_FILES['product_image'], ['jpg', 'jpeg', 'png', 'webp']);
            if ($uploadResult['success']) {
                $imageUrl = 'uploads/' . $uploadResult['stored_name'];
            } else {
                set_flash('error', 'Image upload failed: ' . $uploadResult['error']);
                header('Location: ' . url('admin/products.php' . ($productId ? '?edit_id=' . $productId : '?action=new')));
                exit;
            }
        } elseif (!empty($_POST['current_image_url'])) {
            $imageUrl = trim($_POST['current_image_url']);
        }

        $productData = [
            'sku'               => $sku,
            'category_id'       => $categoryId,
            'service_id'        => $serviceId,
            'title'             => $title,
            'slug'              => $slug,
            'badge'             => $badge,
            'starting_price'    => $startingPrice,
            'price_unit'        => $priceUnit,
            'min_order'         => $minOrder,
            'regular_price'     => $regularPrice,
            'sale_price'        => $salePrice,
            'stock_quantity'    => $stockQuantity,
            'stock_status'      => $stockStatus,
            'short_desc'        => $shortDesc,
            'description'       => $description,
            'specifications'    => $specifications,
            'artwork_notes'     => $artworkNotes,
            'application_guide' => $applicationGuide,
            'shipping_info'     => $shippingInfo,
            'meta_title'        => $metaTitle,
            'meta_description'  => $metaDescription,
            'is_featured'       => $isFeatured,
            'is_active'         => $isActive,
            'display_order'     => $displayOrder
        ];

        if ($imageUrl) {
            $productData['image_url'] = $imageUrl;
        }

        if ($productId > 0) {
            db_update('products', $productData, 'id = :id', ['id' => $productId]);
            log_activity($adminUser['id'], 'update_product', "Updated product: {$title} (SKU: {$sku})");
            set_flash('success', "Product '{$title}' [SKU: {$sku}] updated successfully.");
            header('Location: ' . url('admin/products.php?edit_id=' . $productId));
            exit;
        } else {
            // Check slug uniqueness
            $check = db_fetch_one("SELECT id FROM products WHERE slug = :s", ['s' => $slug]);
            if ($check) {
                $slug .= '-' . rand(100, 999);
                $productData['slug'] = $slug;
            }
            if (!$imageUrl) {
                $productData['image_url'] = 'assets/images/gallery_1.jpg';
            }

            $newId = db_insert('products', $productData);
            log_activity($adminUser['id'], 'create_product', "Created new product: {$title} (SKU: {$sku})");
            set_flash('success', "New product '{$title}' [SKU: {$sku}] created successfully.");
            header('Location: ' . url('admin/products.php?edit_id=' . $newId));
            exit;
        }
    }

    // =========================================================================
    // 2. ASSIGN ATTRIBUTES TO PRODUCT
    // =========================================================================
    if ($action === 'save_product_attributes') {
        $productId = intval($_POST['product_id'] ?? 0);
        $assignedAttrs = $_POST['assigned_attributes'] ?? [];

        if ($productId > 0) {
            db_delete('product_attributes', 'product_id = :pid', ['pid' => $productId]);
            $order = 1;
            foreach ($assignedAttrs as $attrId) {
                db_insert('product_attributes', [
                    'product_id'    => $productId,
                    'attribute_id'  => intval($attrId),
                    'display_order' => $order++
                ]);
            }
            set_flash('success', "Product variation attributes updated. You can now generate or manage variations.");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
        exit;
    }

    // =========================================================================
    // 3. GENERATE VARIATIONS AUTOMATICALLY (Cartesian Product & Unique SKUs)
    // =========================================================================
    if ($action === 'generate_variations') {
        $productId = intval($_POST['product_id'] ?? 0);
        $prod = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $productId]);

        if ($prod) {
            $baseSku = !empty($prod['sku']) ? $prod['sku'] : ('PRD-' . $productId);
            // Prefix for variations: strip trailing -001 or use whole base
            $skuPrefix = preg_replace('/-\d+$/', '', $baseSku);

            // Fetch assigned attributes
            $assigned = db_fetch_all("
                SELECT pa.attribute_id, a.name, a.slug
                FROM product_attributes pa
                JOIN attributes a ON pa.attribute_id = a.id
                WHERE pa.product_id = :pid
                ORDER BY pa.display_order ASC
            ", ['pid' => $productId]);

            if (empty($assigned)) {
                set_flash('error', "Please select and save at least one attribute (e.g. Size or Colour) before generating variations.");
                header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
                exit;
            }

            // Gather values for each attribute
            $attrValuesMap = [];
            foreach ($assigned as $attr) {
                $vals = db_fetch_all("SELECT * FROM attribute_values WHERE attribute_id = :aid ORDER BY display_order ASC, id ASC", ['aid' => $attr['attribute_id']]);
                if (!empty($vals)) {
                    $attrValuesMap[$attr['attribute_id']] = $vals;
                }
            }

            if (empty($attrValuesMap)) {
                set_flash('error', "No attribute values found. Go to 'Product Attributes' to add values like S, M, L or Black, White.");
                header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
                exit;
            }

            // Compute Cartesian product
            $combinations = build_combinations($attrValuesMap);
            $createdCount = 0;

            foreach ($combinations as $combo) {
                // Build combination key: attr_id:val_id-attr_id:val_id
                $comboParts = [];
                $skuParts = [$skuPrefix];

                foreach ($combo as $attrId => $valRow) {
                    $comboParts[] = $attrId . ':' . $valRow['id'];
                    $skuParts[] = generate_val_code($valRow['value']);
                }

                $combinationKey = implode('-', $comboParts);
                $generatedSku = implode('-', $skuParts);

                // Check if this combination already exists for this product
                $existing = db_fetch_one("SELECT id FROM product_variations WHERE product_id = :pid AND combination_key = :k", [
                    'pid' => $productId,
                    'k'   => $combinationKey
                ]);

                if (!$existing) {
                    // Check duplicate SKU across entire store
                    $skuConflict = db_fetch_one("SELECT id FROM product_variations WHERE sku = :s", ['s' => $generatedSku]);
                    if ($skuConflict) {
                        $generatedSku .= '-' . rand(10, 99);
                    }

                    $regPrice = $prod['regular_price'] > 0 ? $prod['regular_price'] : (float)preg_replace('/[^0-9.]/', '', $prod['starting_price'] ?? '249');
                    $salePrice = $prod['sale_price'] > 0 ? $prod['sale_price'] : null;

                    $varId = db_insert('product_variations', [
                        'product_id'      => $productId,
                        'sku'             => $generatedSku,
                        'combination_key' => $combinationKey,
                        'regular_price'   => $regPrice,
                        'sale_price'      => $salePrice,
                        'stock_quantity'  => $prod['stock_quantity'] ?: 50,
                        'is_in_stock'     => 1,
                        'image_url'       => $prod['image_url'],
                        'is_active'       => 1
                    ]);

                    // Insert mapping rows
                    foreach ($combo as $attrId => $valRow) {
                        db_insert('product_variation_values', [
                            'variation_id'       => $varId,
                            'attribute_value_id' => $valRow['id']
                        ]);
                    }

                    $createdCount++;
                }
            }

            log_activity($adminUser['id'], 'generate_variations', "Generated {$createdCount} variations for product {$prod['title']}");
            set_flash('success', "Generated {$createdCount} new variation(s) with unique SKUs and assigned inventory.");
        }

        header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
        exit;
    }

    // =========================================================================
    // 4. REGENERATE ALL SKUs FOR THIS PRODUCT
    // =========================================================================
    if ($action === 'regenerate_skus') {
        $productId = intval($_POST['product_id'] ?? 0);
        $prod = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $productId]);

        if ($prod) {
            $skuPrefix = preg_replace('/-\d+$/', '', $prod['sku']);
            $currentVars = db_fetch_all("SELECT * FROM product_variations WHERE product_id = :pid", ['pid' => $productId]);
            $updated = 0;

            foreach ($currentVars as $var) {
                $valRows = db_fetch_all("
                    SELECT av.value
                    FROM product_variation_values pvv
                    JOIN attribute_values av ON pvv.attribute_value_id = av.id
                    JOIN attributes a ON av.attribute_id = a.id
                    WHERE pvv.variation_id = :vid
                    ORDER BY a.display_order ASC
                ", ['vid' => $var['id']]);

                $skuParts = [$skuPrefix];
                foreach ($valRows as $vr) {
                    $skuParts[] = generate_val_code($vr['value']);
                }

                $newSku = implode('-', $skuParts);
                // Duplicate check
                $dup = db_fetch_one("SELECT id FROM product_variations WHERE sku = :s AND id != :id", ['s' => $newSku, 'id' => $var['id']]);
                if ($dup) {
                    $newSku .= '-' . rand(10, 99);
                }

                db_update('product_variations', ['sku' => $newSku], 'id = :id', ['id' => $var['id']]);
                $updated++;
            }

            set_flash('success', "Regenerated {$updated} unique variation SKUs based on product code and attribute values.");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
        exit;
    }

    // =========================================================================
    // 5. BULK SAVE ALL VARIATIONS
    // =========================================================================
    if ($action === 'save_all_variations') {
        $productId = intval($_POST['product_id'] ?? 0);
        $varSkus = $_POST['var_sku'] ?? [];
        $varRegular = $_POST['var_regular_price'] ?? [];
        $varSale = $_POST['var_sale_price'] ?? [];
        $varStock = $_POST['var_stock_quantity'] ?? [];
        $varInStock = $_POST['var_is_in_stock'] ?? [];
        $varActive = $_POST['var_is_active'] ?? [];
        $varImg = $_POST['var_image_url'] ?? [];

        $saved = 0;
        $duplicates = 0;

        foreach ($varSkus as $vid => $skuRaw) {
            $vid = intval($vid);
            $cleanSku = strtoupper(trim($skuRaw));

            // Check duplicate SKU
            $dup = db_fetch_one("SELECT id FROM product_variations WHERE sku = :s AND id != :id", ['s' => $cleanSku, 'id' => $vid]);
            if ($dup) {
                $duplicates++;
                continue;
            }

            $reg = floatval($varRegular[$vid] ?? 0);
            $sale = !empty($varSale[$vid]) ? floatval($varSale[$vid]) : null;
            $stock = intval($varStock[$vid] ?? 0);
            $inStock = isset($varInStock[$vid]) ? 1 : 0;
            $active = isset($varActive[$vid]) ? 1 : 0;
            $img = trim($varImg[$vid] ?? '');

            db_update('product_variations', [
                'sku'            => $cleanSku,
                'regular_price'  => $reg,
                'sale_price'     => $sale,
                'stock_quantity' => $stock,
                'is_in_stock'    => $inStock,
                'is_active'      => $active,
                'image_url'      => !empty($img) ? $img : null,
                'updated_at'     => date('Y-m-d H:i:s')
            ], 'id = :id', ['id' => $vid]);

            $saved++;
        }

        if ($duplicates > 0) {
            set_flash('warning', "Saved {$saved} variations. {$duplicates} variation(s) were not updated due to duplicate SKUs.");
        } else {
            set_flash('success', "All {$saved} variations successfully updated.");
        }

        header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
        exit;
    }

    // =========================================================================
    // 6. DELETE A VARIATION
    // =========================================================================
    if ($action === 'delete_variation') {
        $varId = intval($_POST['variation_id'] ?? 0);
        $productId = intval($_POST['product_id'] ?? 0);

        if ($varId > 0) {
            db_delete('product_variation_values', 'variation_id = :id', ['id' => $varId]);
            db_delete('product_variations', 'id = :id', ['id' => $varId]);
            set_flash('success', "Variation removed.");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId . '#variations-workbench'));
        exit;
    }

    // =========================================================================
    // 7. TRADITIONAL SIZE & TIER ACTIONS (FOR CALCULATORS / GANG SHEETS)
    // =========================================================================
    if ($action === 'add_size') {
        $productId = intval($_POST['product_id'] ?? 0);
        $sizeLabel = trim($_POST['size_label'] ?? '');
        $dimensions = trim($_POST['dimensions'] ?? '');
        $basePrice = floatval($_POST['base_price'] ?? 0);

        if ($productId > 0 && !empty($sizeLabel)) {
            db_insert('product_sizes', [
                'product_id'    => $productId,
                'size_label'    => $sizeLabel,
                'dimensions'    => $dimensions,
                'base_price'    => $basePrice,
                'display_order' => 0
            ]);
            set_flash('success', "Added size option '{$sizeLabel}' (₹{$basePrice}).");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId));
        exit;
    }

    if ($action === 'delete_size') {
        $sizeId = intval($_POST['size_id'] ?? 0);
        $productId = intval($_POST['product_id'] ?? 0);
        if ($sizeId > 0) {
            db_delete('product_sizes', 'id = :id', ['id' => $sizeId]);
            set_flash('success', "Size option removed.");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId));
        exit;
    }

    if ($action === 'add_tier') {
        $productId = intval($_POST['product_id'] ?? 0);
        $minQty = intval($_POST['min_quantity'] ?? 1);
        $maxQty = !empty($_POST['max_quantity']) ? intval($_POST['max_quantity']) : null;
        $discountPct = floatval($_POST['discount_percent'] ?? 0);

        if ($productId > 0 && $minQty > 0) {
            db_insert('product_pricing_tiers', [
                'product_id'       => $productId,
                'min_quantity'     => $minQty,
                'max_quantity'     => $maxQty,
                'discount_percent' => $discountPct,
                'unit_price'       => 0.00
            ]);
            set_flash('success', "Wholesale pricing tier added ({$discountPct}% off for {$minQty}+ qty).");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId));
        exit;
    }

    if ($action === 'delete_tier') {
        $tierId = intval($_POST['tier_id'] ?? 0);
        $productId = intval($_POST['product_id'] ?? 0);
        if ($tierId > 0) {
            db_delete('product_pricing_tiers', 'id = :id', ['id' => $tierId]);
            set_flash('success', "Pricing tier removed.");
        }
        header('Location: ' . url('admin/products.php?edit_id=' . $productId));
        exit;
    }

    // =========================================================================
    // 8. DELETE PRODUCT & TOGGLE STATUS
    // =========================================================================
    if ($action === 'delete_product') {
        $productId = intval($_POST['product_id'] ?? 0);
        $prod = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $productId]);
        if ($prod) {
            db_delete('product_sizes', 'product_id = :id', ['id' => $productId]);
            db_delete('product_pricing_tiers', 'product_id = :id', ['id' => $productId]);
            db_delete('product_reviews', 'product_id = :id', ['id' => $productId]);
            db_delete('product_attributes', 'product_id = :id', ['id' => $productId]);
            db_delete('product_variations', 'product_id = :id', ['id' => $productId]);
            db_delete('products', 'id = :id', ['id' => $productId]);
            log_activity($adminUser['id'], 'delete_product', "Deleted product: {$prod['title']}");
            set_flash('success', "Product '{$prod['title']}' deleted.");
        }
        header('Location: ' . url('admin/products.php'));
        exit;
    }

    if ($action === 'toggle_status') {
        $productId = intval($_POST['product_id'] ?? 0);
        $prod = db_fetch_one("SELECT id, is_active FROM products WHERE id = :id", ['id' => $productId]);
        if ($prod) {
            $newStatus = $prod['is_active'] ? 0 : 1;
            db_update('products', ['is_active' => $newStatus], 'id = :id', ['id' => $productId]);
            set_flash('success', "Product visibility updated.");
        }
        header('Location: ' . url('admin/products.php'));
        exit;
    }
}

// Fetch categories and services
$categories = db_fetch_all("SELECT * FROM categories ORDER BY name ASC");
$services = db_fetch_all("SELECT id, title FROM services ORDER BY title ASC");
$allAttributes = db_fetch_all("SELECT a.*, COUNT(av.id) as val_count FROM attributes a LEFT JOIN attribute_values av ON a.id = av.attribute_id GROUP BY a.id ORDER BY a.name ASC");

// Check if Editing or Creating
$subAction = $_GET['action'] ?? '';
$editId = intval($_GET['edit_id'] ?? 0);
$editProduct = null;
$productSizes = [];
$productTiers = [];
$assignedAttrIds = [];
$productVariations = [];

if ($editId > 0) {
    $editProduct = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $editId]);
    if ($editProduct) {
        $productSizes = db_fetch_all("SELECT * FROM product_sizes WHERE product_id = :id ORDER BY display_order ASC, id ASC", ['id' => $editId]);
        $productTiers = db_fetch_all("SELECT * FROM product_pricing_tiers WHERE product_id = :id ORDER BY min_quantity ASC", ['id' => $editId]);
        
        $assignedRows = db_fetch_all("SELECT attribute_id FROM product_attributes WHERE product_id = :id ORDER BY display_order ASC", ['id' => $editId]);
        $assignedAttrIds = array_column($assignedRows, 'attribute_id');

        $productVariations = db_fetch_all("
            SELECT pv.*,
                   GROUP_CONCAT(a.name || ': ' || av.value, ' | ') as combination_summary
            FROM product_variations pv
            LEFT JOIN product_variation_values pvv ON pv.id = pvv.variation_id
            LEFT JOIN attribute_values av ON pvv.attribute_value_id = av.id
            LEFT JOIN attributes a ON av.attribute_id = a.id
            WHERE pv.product_id = :id
            GROUP BY pv.id
            ORDER BY pv.id ASC
        ", ['id' => $editId]);
    }
}

// Filters for catalog table
$categoryFilter = intval($_GET['category'] ?? 0);
$searchTerm = trim($_GET['search'] ?? '');

$sql = "SELECT p.*, c.name as category_name, s.title as service_title, COUNT(DISTINCT pv.id) as variation_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN services s ON p.service_id = s.id
        LEFT JOIN product_variations pv ON p.id = pv.product_id
        WHERE 1=1";
$params = [];

if ($categoryFilter) {
    $sql .= " AND p.category_id = :cid";
    $params['cid'] = $categoryFilter;
}

if ($searchTerm) {
    $sql .= " AND (p.title LIKE :s OR p.sku LIKE :s OR p.short_desc LIKE :s OR p.slug LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " GROUP BY p.id ORDER BY p.display_order ASC, p.id DESC";
$products = db_fetch_all($sql, $params);
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Manage Product Catalog
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Configure dynamic product SKUs, variations builder, inventory status, and 6-section content.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <?php if ($editProduct || $subAction === 'new'): ?>
      <a href="<?= url('admin/products.php') ?>" class="btn btn-outline btn-sm">
        &larr; Back to Catalog
      </a>
    <?php else: ?>
      <a href="<?= url('admin/attributes.php') ?>" class="btn btn-outline btn-sm">
        ⚙ Attributes
      </a>
      <a href="<?= url('admin/variations.php') ?>" class="btn btn-outline btn-sm">
        ⇄ Variations
      </a>
      <a href="<?= url('admin/categories.php') ?>" class="btn btn-outline btn-sm">
        📁 Categories
      </a>
      <a href="<?= url('admin/products.php?action=new') ?>" class="btn btn-primary btn-sm">
        + Add New Product
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($editProduct || $subAction === 'new'): ?>
  <!-- ===================================================================== -->
  <!-- PRODUCT EDIT / CREATE FORM -->
  <!-- ===================================================================== -->
  <form method="POST" action="<?= url('admin/products.php') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save_product">
    <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?? 0 ?>">
    <input type="hidden" name="current_image_url" value="<?= e($editProduct['image_url'] ?? '') ?>">

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:1.75rem; align-items:flex-start; margin-bottom:2rem;">

      <!-- Left Column: Details, Pricing, & The 4 Scroll Sections -->
      <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- Card 1: Core Details -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem;">
            <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin:0;">
              1. Basic Information &amp; Primary SKU
            </h3>
            <?php if ($editProduct): ?>
              <a href="<?= product_url($editProduct['slug']) ?>" target="_blank" class="btn btn-xs btn-outline">
                View Live Page ↗
              </a>
            <?php endif; ?>
          </div>

          <div style="display:grid; grid-template-columns:3fr 1.2fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Product Title *</label>
              <input type="text" name="title" class="form-control" required placeholder="e.g. Streetwear Oversized Heavyweight Tee" value="<?= e($editProduct['title'] ?? '') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Base SKU *</label>
              <input type="text" name="sku" class="form-control" required placeholder="e.g. STR-001" style="font-weight:700; text-transform:uppercase;" value="<?= e($editProduct['sku'] ?? '') ?>">
              <small style="color:var(--text-muted); font-size:0.75rem;">Unique base identifier</small>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">URL Slug</label>
              <input type="text" name="slug" class="form-control" placeholder="e.g. streetwear-oversized-tee" value="<?= e($editProduct['slug'] ?? '') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Category</label>
              <select name="category_id" class="form-control">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Associated Service</label>
              <select name="service_id" class="form-control">
                <option value="">-- General Merchandise / None --</option>
                <?php foreach ($services as $srv): ?>
                  <option value="<?= $srv['id'] ?>" <?= ($editProduct && $editProduct['service_id'] == $srv['id']) ? 'selected' : '' ?>>
                    <?= e($srv['title']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Badge / Promotion Tag</label>
              <input type="text" name="badge" class="form-control" placeholder="e.g. Bestseller, 240 GSM, Express" value="<?= e($editProduct['badge'] ?? '') ?>">
            </div>
          </div>

          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Short Overview (Grid Summary)</label>
            <textarea name="short_desc" class="form-control" rows="2"><?= e($editProduct['short_desc'] ?? '') ?></textarea>
          </div>
        </div>

        <!-- Card 2: Pricing, Stock & Inventory -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem;">
            2. Base Pricing &amp; Stock Inventory
          </h3>

          <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Regular Price (₹)</label>
              <input type="number" step="0.01" name="regular_price" class="form-control" value="<?= e($editProduct['regular_price'] ?? '699.00') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Sale Price (₹)</label>
              <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="Optional" value="<?= e($editProduct['sale_price'] ?? '') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Starting Display</label>
              <input type="text" name="starting_price" class="form-control" value="<?= e($editProduct['starting_price'] ?? '₹249') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Price Unit</label>
              <input type="text" name="price_unit" class="form-control" value="<?= e($editProduct['price_unit'] ?? '/ Unit') ?>">
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Warehouse Stock Quantity</label>
              <input type="number" name="stock_quantity" class="form-control" value="<?= e($editProduct['stock_quantity'] ?? '50') ?>">
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Stock Status</label>
              <select name="stock_status" class="form-control">
                <option value="in_stock" <?= (!isset($editProduct) || ($editProduct['stock_status'] ?? '') === 'in_stock') ? 'selected' : '' ?>>In Stock</option>
                <option value="out_of_stock" <?= (($editProduct['stock_status'] ?? '') === 'out_of_stock') ? 'selected' : '' ?>>Out of Stock</option>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Min. Order Quantity</label>
              <input type="text" name="min_order" class="form-control" value="<?= e($editProduct['min_order'] ?? 'Min. 1') ?>">
            </div>
          </div>
        </div>

        <!-- Card 3: The 4 Dedicated Product Sections (Directly Controlled in Admin) -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
          <div style="margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem;">
            <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin:0;">
              3. The 4 Product Scroll Content Sections
            </h3>
            <small style="color:var(--text-muted); font-size:0.8rem;">
              These correspond directly to the 6 sequential scroll sections on the storefront product page.
            </small>
          </div>

          <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Detailed Production Description
              </label>
              <textarea name="description" class="form-control" rows="3"><?= e($editProduct['description'] ?? '') ?></textarea>
            </div>

            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Section 2: Technical Specifications &amp; Calibrated Parameters
              </label>
              <textarea name="specifications" class="form-control" rows="3" placeholder="Resolution: 2400 x 1200 DPI&#10;Substrate: 100% Combed Cotton&#10;Longevity: 50+ Wash Cycles"><?= e($editProduct['specifications'] ?? '') ?></textarea>
            </div>

            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Section 3: Artwork &amp; File Guidelines
              </label>
              <textarea name="artwork_notes" class="form-control" rows="3" placeholder="Provide transparent PNG at 300 DPI. CMYK or RGB color profile..."><?= e($editProduct['artwork_notes'] ?? '') ?></textarea>
            </div>

            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Section 4: Application &amp; Heat Press Guide
              </label>
              <textarea name="application_guide" class="form-control" rows="3" placeholder="Press at 160°C for 15 seconds under medium-high pressure. Cold peel..."><?= e($editProduct['application_guide'] ?? '') ?></textarea>
            </div>

            <div>
              <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
                Section 6: Turnaround &amp; PAN-India Shipping Info
              </label>
              <textarea name="shipping_info" class="form-control" rows="3" placeholder="Digital prepress proof confirmed within 2–4 hours. 24–48H fast dispatch..."><?= e($editProduct['shipping_info'] ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <!-- Card 4: SEO Metadata -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem;">
            4. Search Engine Optimization (SEO)
          </h3>

          <div style="margin-bottom:1rem;">
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Custom Meta Title</label>
            <input type="text" name="meta_title" class="form-control" placeholder="Defaults to: Product Name (SKU: ...) | OrderPrintex" value="<?= e($editProduct['meta_title'] ?? '') ?>">
          </div>

          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2" placeholder="Search result snippet..."><?= e($editProduct['meta_description'] ?? '') ?></textarea>
          </div>
        </div>

      </div>

      <!-- Right Column: Image, Visibility & Actions -->
      <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- Product Image -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">Product Main Image</h3>
          
          <?php if (!empty($editProduct['image_url'])): ?>
            <div style="height:200px; background:#F8FAFC; border:1px solid var(--border-light); border-radius:6px; display:flex; align-items:center; justify-content:center; padding:10px; margin-bottom:1rem;">
              <img src="<?= url(e($editProduct['image_url'])) ?>" alt="Preview" style="max-height:100%; max-width:100%; object-fit:contain;">
            </div>
            <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:0.5rem; overflow:hidden; text-overflow:ellipsis;">
              Current: <code><?= e($editProduct['image_url']) ?></code>
            </div>
          <?php endif; ?>

          <input type="file" name="product_image" class="form-control" accept="image/*">
          <small style="color:var(--text-muted); font-size:0.75rem; display:block; margin-top:0.35rem;">
            Or paste image path below:
          </small>
          <input type="text" name="current_image_url" class="form-control" style="margin-top:0.35rem; font-size:0.8rem;" placeholder="e.g. assets/images/streetwear_tee.jpg" value="<?= e($editProduct['image_url'] ?? '') ?>">
        </div>

        <!-- Visibility & Status -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">Status &amp; Sorting</h3>

          <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1rem;">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.85rem; color:var(--navy);">
              <input type="checkbox" name="is_active" value="1" <?= (!isset($editProduct) || !empty($editProduct['is_active'])) ? 'checked' : '' ?>>
              <span>Active on Live Storefront</span>
            </label>

            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.85rem; color:var(--navy);">
              <input type="checkbox" name="is_featured" value="1" <?= !empty($editProduct['is_featured']) ? 'checked' : '' ?>>
              <span>Show in Homepage Featured Grid</span>
            </label>
          </div>

          <div>
            <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Display Sorting Order</label>
            <input type="number" name="display_order" class="form-control" value="<?= e($editProduct['display_order'] ?? '0') ?>">
          </div>
        </div>

        <!-- Save Button Card -->
        <div style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow: var(--shadow-card); display:flex; flex-direction:column; gap:0.75rem;">
          <button type="submit" class="btn btn-primary btn-md" style="width:100%; justify-content:center;">
            <span><?= $editProduct ? '💾 Save Product Changes' : '✨ Publish New Product' ?></span>
          </button>
          <a href="<?= url('admin/products.php') ?>" class="btn btn-outline btn-sm" style="width:100%; text-align:center;">
            Cancel
          </a>
        </div>

      </div>

    </div>
  </form>

  <!-- ===================================================================== -->
  <!-- DYNAMIC VARIATIONS BUILDER & WORKBENCH (WHEN EDITING A PRODUCT) -->
  <!-- ===================================================================== -->
  <?php if ($editProduct): ?>
    <div id="variations-workbench" style="margin-top:2.5rem; border-top:2px solid var(--border-gold); padding-top:2rem;">

      <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:2rem; box-shadow: 0 6px 18px rgba(15, 25, 45, 0.09); margin-bottom:2rem;">
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:1rem; flex-wrap:wrap; gap:1rem;">
          <div>
            <div style="font-size:0.8rem; font-weight:800; text-transform:uppercase; color:var(--royal); letter-spacing:0.04em;">SKU &amp; Inventory Engine</div>
            <h2 style="font-size:1.4rem; color:var(--navy); font-weight:800; margin:0;">
              Dynamic Product Variations Builder
            </h2>
          </div>

          <!-- Actions: Generate & Regenerate -->
          <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <form method="POST" action="<?= url('admin/products.php') ?>" style="display:inline;" onsubmit="return confirm('Generate variations automatically for all assigned attributes? Existing matching variations will be preserved.');">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="generate_variations">
              <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
              <button type="submit" class="btn btn-primary btn-sm">
                ⚡ Generate Variations Automatically
              </button>
            </form>

            <form method="POST" action="<?= url('admin/products.php') ?>" style="display:inline;" onsubmit="return confirm('Regenerate all variation SKUs based on base product SKU + attribute abbreviations?');">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="regenerate_skus">
              <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
              <button type="submit" class="btn btn-outline btn-sm">
                ↻ Regenerate All SKUs
              </button>
            </form>
          </div>
        </div>

        <!-- Step 1: Assign Attributes -->
        <div style="background:var(--ivory); border:1px solid var(--border-light); border-radius:8px; padding:1.25rem; margin-bottom:1.75rem;">
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--navy); margin-bottom:0.5rem;">
            Step 1: Choose Attributes Applicable to <?= e($editProduct['title']) ?>
          </h4>
          <p style="font-size:0.825rem; color:var(--slate); margin-bottom:1rem;">
            Select which attributes this product uses (e.g. Size, Colour, Material). Then click "Save Attributes", followed by "Generate Variations Automatically".
          </p>

          <form method="POST" action="<?= url('admin/products.php') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_product_attributes">
            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">

            <div style="display:flex; flex-wrap:wrap; gap:1.25rem; margin-bottom:1rem;">
              <?php foreach ($allAttributes as $attr): ?>
                <label style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--bg-surface); padding:0.5rem 0.85rem; border:1px solid var(--border-light); border-radius:6px; cursor:pointer; font-weight:700; font-size:0.85rem; color:var(--navy);">
                  <input type="checkbox" name="assigned_attributes[]" value="<?= $attr['id'] ?>" <?= in_array($attr['id'], $assignedAttrIds) ? 'checked' : '' ?>>
                  <span><?= e($attr['name']) ?></span>
                  <span style="font-size:0.75rem; color:var(--royal); font-weight:600;">(<?= $attr['val_count'] ?> values)</span>
                </label>
              <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-navy btn-sm">
              Save Assigned Attributes
            </button>
          </form>
        </div>

        <!-- Step 2: Variations Table with Bulk Edit -->
        <div>
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h4 style="font-size:1.05rem; font-weight:700; color:var(--navy); margin:0;">
              Active Variations for this Product (<?= count($productVariations) ?>)
            </h4>
            <span style="font-size:0.8rem; color:var(--text-muted);">
              All changes to SKUs, pricing and stock will be validated against storewide duplicates upon saving.
            </span>
          </div>

          <?php if (empty($productVariations)): ?>
            <div style="text-align:center; padding:2.5rem; background:var(--ivory); border-radius:8px; border:1px dashed var(--border-light); color:var(--text-muted);">
              <p style="margin:0 0 0.5rem; font-weight:700; color:var(--navy);">No variations generated yet.</p>
              <p style="margin:0; font-size:0.85rem;">Check the attributes above and click "⚡ Generate Variations Automatically" to auto-create all combinations with unique SKUs.</p>
            </div>
          <?php else: ?>
            <form method="POST" action="<?= url('admin/products.php') ?>">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="save_all_variations">
              <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">

              <div class="table-responsive" style="margin-bottom:1.25rem;">
                <table class="data-table" style="width:100%; font-size:0.85rem;">
                  <thead>
                    <tr>
                      <th>Attributes Combination</th>
                      <th style="min-width:140px;">Unique SKU *</th>
                      <th style="min-width:100px;">Regular ₹</th>
                      <th style="min-width:100px;">Sale ₹</th>
                      <th style="min-width:85px;">Stock</th>
                      <th>In Stock</th>
                      <th>Active</th>
                      <th style="min-width:140px;">Custom Image URL</th>
                      <th style="text-align:right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($productVariations as $var): ?>
                      <tr>
                        <td>
                          <strong style="color:var(--navy); font-size:0.88rem;">
                            <?= e($var['combination_summary'] ?: $var['combination_key']) ?>
                          </strong>
                        </td>

                        <td>
                          <input type="text" name="var_sku[<?= $var['id'] ?>]" value="<?= e($var['sku']) ?>" class="form-control" style="font-weight:700; height:32px; font-size:0.8rem; text-transform:uppercase;" required>
                        </td>

                        <td>
                          <input type="number" step="0.01" name="var_regular_price[<?= $var['id'] ?>]" value="<?= $var['regular_price'] ?>" class="form-control" style="height:32px; font-size:0.8rem;" required>
                        </td>

                        <td>
                          <input type="number" step="0.01" name="var_sale_price[<?= $var['id'] ?>]" value="<?= $var['sale_price'] ?>" class="form-control" style="height:32px; font-size:0.8rem;" placeholder="None">
                        </td>

                        <td>
                          <input type="number" name="var_stock_quantity[<?= $var['id'] ?>]" value="<?= $var['stock_quantity'] ?>" class="form-control" style="height:32px; font-size:0.8rem; width:75px;" min="0">
                        </td>

                        <td style="text-align:center;">
                          <input type="checkbox" name="var_is_in_stock[<?= $var['id'] ?>]" value="1" <?= $var['is_in_stock'] ? 'checked' : '' ?>>
                        </td>

                        <td style="text-align:center;">
                          <input type="checkbox" name="var_is_active[<?= $var['id'] ?>]" value="1" <?= $var['is_active'] ? 'checked' : '' ?>>
                        </td>

                        <td>
                          <input type="text" name="var_image_url[<?= $var['id'] ?>]" value="<?= e($var['image_url'] ?? '') ?>" class="form-control" style="height:32px; font-size:0.75rem;" placeholder="assets/images/...">
                        </td>

                        <td style="text-align:right;">
                          <button type="button" class="btn btn-xs btn-danger" onclick="if(confirm('Delete variation [<?= addslashes($var['sku']) ?>]?')) { document.getElementById('del_var_single_<?= $var['id'] ?>').submit(); }">
                            ✕
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border-light); padding-top:1rem;">
                <span style="font-size:0.85rem; color:var(--slate);">
                  Click "Save All Variations" to write all row changes to database with duplicate prevention.
                </span>
                <button type="submit" class="btn btn-primary btn-md">
                  <span>💾 Save All Variations</span>
                </button>
              </div>
            </form>

            <!-- Hidden delete forms for each variation -->
            <?php foreach ($productVariations as $var): ?>
              <form id="del_var_single_<?= $var['id'] ?>" method="POST" action="<?= url('admin/products.php') ?>" style="display:none;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete_variation">
                <input type="hidden" name="variation_id" value="<?= $var['id'] ?>">
                <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
              </form>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>

      <!-- SIZES & WHOLESALE PRICING TIERS (FOR GANG SHEETS / CALCULATORS) -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.75rem; margin-bottom:2rem;">
        
        <!-- Product Sizes Box -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1.05rem; color:var(--navy); font-weight:700; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.5rem;">
            Gang Sheet Sizes &amp; Dimensions (Calculator Fallback)
          </h3>

          <form method="POST" action="<?= url('admin/products.php') ?>" style="display:grid; grid-template-columns:2fr 2fr 1.5fr auto; gap:8px; margin-bottom:1rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add_size">
            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
            <input type="text" name="size_label" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="Size Name (e.g. 24x60)" required>
            <input type="text" name="dimensions" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="Dimensions (e.g. 24 x 60 in)">
            <input type="number" step="0.01" name="base_price" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="₹ Base" required>
            <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">+ Add</button>
          </form>

          <table class="data-table" style="width:100%; font-size:0.825rem;">
            <thead>
              <tr>
                <th>Size Label</th>
                <th>Dimensions</th>
                <th>Base ₹</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($productSizes)): ?>
                <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:1rem;">No sizes configured.</td></tr>
              <?php else: ?>
                <?php foreach ($productSizes as $s): ?>
                  <tr>
                    <td><strong><?= e($s['size_label']) ?></strong></td>
                    <td><?= e($s['dimensions']) ?></td>
                    <td style="color:var(--navy); font-weight:700;">₹<?= number_format($s['base_price'], 2) ?></td>
                    <td style="text-align:right;">
                      <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Remove size?');" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="delete_size">
                        <input type="hidden" name="size_id" value="<?= $s['id'] ?>">
                        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                        <button type="submit" class="btn btn-xs btn-danger">✕</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Wholesale Tiers Box -->
        <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow: var(--shadow-card);">
          <h3 style="font-size:1.05rem; color:var(--navy); font-weight:700; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.5rem;">
            Wholesale Quantity Discount Tiers
          </h3>

          <form method="POST" action="<?= url('admin/products.php') ?>" style="display:grid; grid-template-columns:1fr 1fr 1.5fr auto; gap:8px; margin-bottom:1rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add_tier">
            <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
            <input type="number" name="min_quantity" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="Min Qty" required>
            <input type="number" name="max_quantity" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="Max Qty">
            <input type="number" step="0.1" name="discount_percent" class="form-control" style="font-size:0.8rem; height:34px;" placeholder="Discount %" required>
            <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">+ Add</button>
          </form>

          <table class="data-table" style="width:100%; font-size:0.825rem;">
            <thead>
              <tr>
                <th>Quantity Range</th>
                <th>Discount</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($productTiers)): ?>
                <tr><td colspan="3" style="text-align:center; color:var(--text-muted); padding:1rem;">No tiers configured.</td></tr>
              <?php else: ?>
                <?php foreach ($productTiers as $t): ?>
                  <tr>
                    <td><strong><?= $t['min_quantity'] ?><?= $t['max_quantity'] ? ' – ' . $t['max_quantity'] : '+' ?> units</strong></td>
                    <td><span class="badge" style="background:#ECFDF5; color:#065F46; font-weight:700;"><?= $t['discount_percent'] ?>% Off</span></td>
                    <td style="text-align:right;">
                      <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Remove tier?');" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="delete_tier">
                        <input type="hidden" name="tier_id" value="<?= $t['id'] ?>">
                        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                        <button type="submit" class="btn btn-xs btn-danger">✕</button>
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
  <?php endif; ?>

<?php else: ?>
  <!-- ===================================================================== -->
  <!-- PRODUCT LISTING TABLE -->
  <!-- ===================================================================== -->
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; box-shadow:0 4px 12px rgba(15, 25, 45, 0.06); margin-bottom:1.5rem;">
    <form method="GET" action="<?= url('admin/products.php') ?>" style="display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end; justify-content:space-between;">
      <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="<?= url('admin/products.php') ?>" class="btn btn-xs <?= empty($categoryFilter) ? 'btn-primary' : 'btn-outline' ?>">
          All Categories (<?= count($products) ?>)
        </a>
        <?php foreach ($categories as $c): ?>
          <a href="<?= url('admin/products.php?category=' . $c['id']) ?>" class="btn btn-xs <?= $categoryFilter === (int)$c['id'] ? 'btn-primary' : 'btn-outline' ?>">
            <?= e($c['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div style="display:flex; gap:0.5rem;">
        <?php if ($categoryFilter): ?>
          <input type="hidden" name="category" value="<?= $categoryFilter ?>">
        <?php endif; ?>
        <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search title or SKU..." class="form-control" style="width:220px; height:34px; font-size:0.85rem;">
        <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
        <?php if ($searchTerm || $categoryFilter): ?>
          <a href="<?= url('admin/products.php') ?>" class="btn btn-outline btn-sm" style="height:34px; display:inline-flex; align-items:center;">Reset</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.5rem; box-shadow:0 4px 14px rgba(15, 25, 45, 0.08);">
    <div class="table-responsive">
      <table class="data-table" style="width:100%; font-size:0.88rem;">
        <thead>
          <tr>
            <th style="width:60px;">Image</th>
            <th style="width:100px;">Base SKU</th>
            <th>Product Title &amp; Slug</th>
            <th>Category</th>
            <th>From Price</th>
            <th>Variations</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">
                No products found matching your filter criteria.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($products as $p): ?>
              <tr>
                <td>
                  <div style="width:48px; height:48px; border-radius:4px; overflow:hidden; background:var(--bg-surface); border:1px solid var(--border-light); display:flex; align-items:center; justify-content:center; padding:3px;">
                    <img src="<?= url(e($p['image_url'] ?: 'assets/images/gallery_1.jpg')) ?>" alt="" style="max-width:100%; max-height:100%; object-fit:contain;">
                  </div>
                </td>

                <td>
                  <span style="font-weight:700; color:var(--navy); font-size:0.8rem; background:rgba(20,35,60,0.06); padding:3px 7px; border-radius:4px; letter-spacing:0.04em;">
                    <?= e($p['sku']) ?>
                  </span>
                </td>

                <td>
                  <strong style="color:var(--navy); display:block; font-size:0.9rem;">
                    <a href="<?= url('admin/products.php?edit_id=' . $p['id']) ?>" style="color:var(--navy); text-decoration:none;">
                      <?= e($p['title']) ?>
                    </a>
                  </strong>
                  <span style="font-size:0.75rem; color:var(--royal);">/product/<?= e($p['slug']) ?>/</span>
                </td>

                <td>
                  <span class="badge" style="background:rgba(29,78,216,0.08); color:var(--royal); font-weight:600; font-size:0.75rem;">
                    <?= e($p['category_name'] ?: 'General') ?>
                  </span>
                </td>

                <td style="font-weight:700; color:var(--navy); font-size:0.85rem;">
                  <?= e($p['starting_price'] ?: '—') ?>
                </td>

                <td>
                  <a href="<?= url('admin/variations.php?product_id=' . $p['id']) ?>" class="badge" style="background:rgba(185,154,97,0.15); color:var(--navy); font-weight:700; text-decoration:none;">
                    <?= $p['variation_count'] ?> variations &rarr;
                  </a>
                </td>

                <td>
                  <form method="POST" action="<?= url('admin/products.php') ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="toggle_status">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn btn-xs" style="background:<?= $p['is_active'] ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)' ?>; color:<?= $p['is_active'] ? '#059669' : '#DC2626' ?>; border:none; font-weight:700; padding:2px 8px; border-radius:4px;">
                      <?= $p['is_active'] ? '● Active' : '○ Hidden' ?>
                    </button>
                  </form>
                </td>

                <td style="text-align:right; white-space:nowrap;">
                  <a href="<?= product_url($p['slug']) ?>" target="_blank" class="btn btn-xs btn-outline" title="View live page">
                    👁
                  </a>
                  <a href="<?= url('admin/products.php?edit_id=' . $p['id']) ?>" class="btn btn-xs btn-outline" title="Edit Product">
                    ✎ Edit
                  </a>
                  <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Are you sure you want to delete this product? All variations and sizes will be removed.');" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn btn-xs btn-danger" title="Delete">✕</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
