<?php
/**
 * OrderPrintex - Admin Product Management
 * Complete SKU, Sizes, Pricing Tiers, Images & Specifications Control
 */

$adminTitle = "Manage Products Catalog";
require_once __DIR__ . '/header.php';

// Handle Product and Category Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/products.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save / Update Product
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
        $shortDesc = trim($_POST['short_desc'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $specifications = trim($_POST['specifications'] ?? '');
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

        // Check SKU uniqueness
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

        if ($productId > 0) {
            $updateData = [
                'sku'            => $sku,
                'category_id'    => $categoryId,
                'service_id'     => $serviceId,
                'title'          => $title,
                'slug'           => $slug,
                'badge'          => $badge,
                'starting_price' => $startingPrice,
                'price_unit'     => $priceUnit,
                'min_order'      => $minOrder,
                'short_desc'     => $shortDesc,
                'description'    => $description,
                'specifications' => $specifications,
                'is_featured'    => $isFeatured,
                'is_active'      => $isActive,
                'display_order'  => $displayOrder
            ];
            if ($imageUrl) {
                $updateData['image_url'] = $imageUrl;
            }

            db_update('products', $updateData, 'id = :id', ['id' => $productId]);
            log_activity($adminUser['id'], 'update_product', "Updated product: {$title} (SKU: {$sku})");
            set_flash('success', "Product '{$title}' [SKU: {$sku}] updated successfully.");
            header('Location: ' . url('admin/products.php?edit_id=' . $productId));
            exit;
        } else {
            // Check slug uniqueness
            $check = db_fetch_one("SELECT id FROM products WHERE slug = :s", ['s' => $slug]);
            if ($check) {
                $slug .= '-' . rand(100, 999);
            }

            $newId = db_insert('products', [
                'sku'            => $sku,
                'category_id'    => $categoryId,
                'service_id'     => $serviceId,
                'title'          => $title,
                'slug'           => $slug,
                'badge'          => $badge,
                'starting_price' => $startingPrice,
                'price_unit'     => $priceUnit,
                'min_order'      => $minOrder,
                'short_desc'     => $shortDesc,
                'description'    => $description,
                'specifications' => $specifications,
                'image_url'      => $imageUrl ?: 'assets/images/gallery_1.jpg',
                'is_featured'    => $isFeatured,
                'is_active'      => $isActive,
                'display_order'  => $displayOrder
            ]);

            log_activity($adminUser['id'], 'create_product', "Created new product: {$title} (SKU: {$sku})");
            set_flash('success', "New product '{$title}' [SKU: {$sku}] created successfully.");
            header('Location: ' . url('admin/products.php?edit_id=' . $newId));
            exit;
        }
    }

    // 2. Add Product Size
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

    // 3. Delete Product Size
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

    // 4. Add Pricing Tier
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

    // 5. Delete Pricing Tier
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

    // 6. Delete Product
    if ($action === 'delete_product') {
        $productId = intval($_POST['product_id'] ?? 0);
        $prod = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $productId]);
        if ($prod) {
            db_delete('product_sizes', 'product_id = :id', ['id' => $productId]);
            db_delete('product_pricing_tiers', 'product_id = :id', ['id' => $productId]);
            db_delete('product_reviews', 'product_id = :id', ['id' => $productId]);
            db_delete('products', 'id = :id', ['id' => $productId]);
            log_activity($adminUser['id'], 'delete_product', "Deleted product: {$prod['title']}");
            set_flash('success', "Product '{$prod['title']}' deleted.");
        }
        header('Location: ' . url('admin/products.php'));
        exit;
    }

    // 7. Toggle Active Status
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

// Check if Editing or Creating
$subAction = $_GET['action'] ?? '';
$editId = intval($_GET['edit_id'] ?? 0);
$editProduct = null;
$productSizes = [];
$productTiers = [];

if ($editId > 0) {
    $editProduct = db_fetch_one("SELECT * FROM products WHERE id = :id", ['id' => $editId]);
    if ($editProduct) {
        $productSizes = db_fetch_all("SELECT * FROM product_sizes WHERE product_id = :id ORDER BY display_order ASC, id ASC", ['id' => $editId]);
        $productTiers = db_fetch_all("SELECT * FROM product_pricing_tiers WHERE product_id = :id ORDER BY min_quantity ASC", ['id' => $editId]);
    }
}

// Filters for list
$categoryFilter = intval($_GET['category'] ?? 0);
$searchTerm = trim($_GET['search'] ?? '');

$sql = "SELECT p.*, c.name as category_name, s.title as service_title
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN services s ON p.service_id = s.id
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

$sql .= " ORDER BY p.display_order ASC, p.id DESC";
$products = db_fetch_all($sql, $params);
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Product Catalog</h1>
        <p class="admin-page-subtitle">Configure unique SKUs, containment imagery, size pricing variants, and volume wholesale tiers.</p>
    </div>
    <div class="admin-topbar-actions">
        <?php if ($editProduct || $subAction === 'new'): ?>
            <a href="<?= url('admin/products.php') ?>" class="btn btn-outline btn-sm">
                &larr; Back to Catalog
            </a>
        <?php else: ?>
            <a href="<?= url('admin/products.php?action=new') ?>" class="btn btn-primary btn-sm">
                <span>+ Add New Product</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($editProduct || $subAction === 'new'): ?>
    <!-- PRODUCT EDIT / CREATE FORM -->
    <div class="admin-card" style="margin-bottom: 24px;">
        <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="admin-card-title"><?= $editProduct ? 'Edit Product: ' . e($editProduct['title']) : 'Create New Product' ?></h2>
            <?php if ($editProduct): ?>
                <a href="<?= product_url($editProduct['slug']) ?>" target="_blank" class="btn btn-outline btn-xs" style="color:var(--color-royal);">
                    View Live Storefront Page ↗
                </a>
            <?php endif; ?>
        </div>
        <div class="admin-card-body" style="padding: 24px;">
            <form method="POST" action="<?= url('admin/products.php') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="save_product">
                <input type="hidden" name="product_id" value="<?= $editProduct ? $editProduct['id'] : 0 ?>">

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
                    
                    <!-- Left Column: Details, SKU & Pricing -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        
                        <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label required">Product Title</label>
                                <input type="text" name="title" class="form-input" required 
                                       value="<?= e($editProduct['title'] ?? '') ?>" placeholder="e.g. 24-Inch Industrial DTF Gang Roll">
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Product SKU</label>
                                <input type="text" name="sku" class="form-input" required 
                                       value="<?= e($editProduct['sku'] ?? '') ?>" placeholder="e.g. BKM-001" style="font-weight:700; text-transform:uppercase;">
                                <small style="font-size:0.75rem; color:var(--color-text-muted);">Unique stock identifier</small>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">URL Slug (leave blank to auto-generate)</label>
                                <input type="text" name="slug" class="form-input" 
                                       value="<?= e($editProduct['slug'] ?? '') ?>" placeholder="e.g. bookmark-03">
                                <small style="font-size:0.75rem; color:var(--color-text-muted);">Will appear as: /product/slug-name/</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Product Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= e($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Pricing & Order Info Row -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; background:var(--color-ivory); padding:16px; border-radius:6px; border:1px solid var(--color-border);">
                            <div class="form-group" style="margin:0;">
                                <label class="form-label" style="font-size:0.8rem;">Starting Price</label>
                                <input type="text" name="starting_price" class="form-input form-input-sm" value="<?= e($editProduct['starting_price'] ?? '₹249') ?>" placeholder="₹249">
                            </div>
                            <div class="form-group" style="margin:0;">
                                <label class="form-label" style="font-size:0.8rem;">Price Unit</label>
                                <input type="text" name="price_unit" class="form-input form-input-sm" value="<?= e($editProduct['price_unit'] ?? '/ Unit') ?>" placeholder="/ Unit">
                            </div>
                            <div class="form-group" style="margin:0;">
                                <label class="form-label" style="font-size:0.8rem;">Min Order</label>
                                <input type="text" name="min_order" class="form-input form-input-sm" value="<?= e($editProduct['min_order'] ?? 'Min. 1') ?>" placeholder="Min. 1">
                            </div>
                            <div class="form-group" style="margin:0;">
                                <label class="form-label" style="font-size:0.8rem;">Badge / Tag</label>
                                <input type="text" name="badge" class="form-input form-input-sm" value="<?= e($editProduct['badge'] ?? 'Popular') ?>" placeholder="Bestseller">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Associated Printing Service</label>
                            <select name="service_id" class="form-select">
                                <option value="">-- None / General Merchandise --</option>
                                <?php foreach ($services as $srv): ?>
                                    <option value="<?= $srv['id'] ?>" <?= ($editProduct && $editProduct['service_id'] == $srv['id']) ? 'selected' : '' ?>>
                                        <?= e($srv['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Short Summary (Featured Card Description)</label>
                            <textarea name="short_desc" class="form-input" rows="2" placeholder="Brief 1-2 sentence overview shown in product grids..."><?= e($editProduct['short_desc'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Detailed Product Description</label>
                            <textarea name="description" class="form-input" rows="5" placeholder="In-depth explanation of fabric compatibility, print technology, heat press transfer instructions..."><?= e($editProduct['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Technical Specifications (One per line)</label>
                            <textarea name="specifications" class="form-input" rows="4" placeholder="Resolution: 2400 x 1200 DPI&#10;Width: 24 Inches&#10;Wash Durability: 50+ Cycles"><?= e($editProduct['specifications'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Right Column: Media, Containment & Display -->
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        
                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Product Image (Contain-Fit)</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px;">
                                <?php if (!empty($editProduct['image_url'])): ?>
                                    <div style="margin-bottom: 12px; border-radius: 4px; overflow: hidden; border: 1px solid var(--color-border); height: 180px; background: #ffffff; display:flex; align-items:center; justify-content:center; padding:10px;">
                                        <img src="<?= asset_url($editProduct['image_url']) ?>" alt="Product Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                    <input type="hidden" name="current_image_url" value="<?= e($editProduct['image_url']) ?>">
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-bottom: 8px;">Upload a new image below to replace:</span>
                                <?php endif; ?>

                                <input type="file" name="product_image" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp">
                                <small style="display: block; margin-top: 4px; color: var(--color-text-muted); font-size: 0.75rem;">
                                    Full uncropped product preview will be rendered with neutral background and contain-fit.
                                </small>
                            </div>
                        </div>

                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Display Settings</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 14px;">
                                
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                    <input type="checkbox" name="is_active" value="1" <?= (!isset($editProduct) || $editProduct['is_active']) ? 'checked' : '' ?>>
                                    Active / Visible on Storefront
                                </label>

                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                    <input type="checkbox" name="is_featured" value="1" <?= ($editProduct && $editProduct['is_featured']) ? 'checked' : '' ?>>
                                    Showcase in Homepage Featured Grid
                                </label>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" style="font-size: 0.8rem;">Display Sorting Order</label>
                                    <input type="number" name="display_order" class="form-input form-input-sm" value="<?= $editProduct['display_order'] ?? 0 ?>" style="width: 100px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                            <?= $editProduct ? '💾 Save Product Changes' : '✨ Publish New Product' ?>
                        </button>

                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- SIZES & PRICING TIERS MANAGEMENT (WHEN EDITING) -->
    <?php if ($editProduct): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            
            <!-- Product Sizes Box -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Available Sizes &amp; Base Prices</h3>
                </div>
                <div class="admin-card-body" style="padding: 16px;">
                    <form method="POST" action="<?= url('admin/products.php') ?>" style="display:grid; grid-template-columns: 2fr 2fr 1.5fr auto; gap:8px; margin-bottom:16px;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="add_size">
                        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                        <input type="text" name="size_label" class="form-input form-input-sm" placeholder="Size Name (e.g. Standard 2x6)" required>
                        <input type="text" name="dimensions" class="form-input form-input-sm" placeholder="Dimensions (e.g. 2 x 6 in)">
                        <input type="number" step="0.01" name="base_price" class="form-input form-input-sm" placeholder="Base ₹" required>
                        <button type="submit" class="btn btn-primary btn-sm">+ Add</button>
                    </form>

                    <table class="admin-table" style="font-size:0.85rem;">
                        <thead>
                            <tr>
                                <th>Size Label</th>
                                <th>Dimensions</th>
                                <th>Base Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productSizes)): ?>
                                <tr><td colspan="4" style="text-align:center; color:var(--color-text-muted);">No sizes configured yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($productSizes as $s): ?>
                                    <tr>
                                        <td><strong><?= e($s['size_label']) ?></strong></td>
                                        <td><?= e($s['dimensions']) ?></td>
                                        <td style="color:var(--color-navy); font-weight:700;">₹<?= number_format($s['base_price'], 2) ?></td>
                                        <td>
                                            <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Remove size?');" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_size">
                                                <input type="hidden" name="size_id" value="<?= $s['id'] ?>">
                                                <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                                                <button type="submit" class="btn btn-outline btn-xs" style="color:var(--color-crimson);">Del</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Wholesale Pricing Tiers Box -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Quantity Discount Wholesale Tiers</h3>
                </div>
                <div class="admin-card-body" style="padding: 16px;">
                    <form method="POST" action="<?= url('admin/products.php') ?>" style="display:grid; grid-template-columns: 1fr 1fr 1.5fr auto; gap:8px; margin-bottom:16px;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="add_tier">
                        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                        <input type="number" name="min_quantity" class="form-input form-input-sm" placeholder="Min Qty" required>
                        <input type="number" name="max_quantity" class="form-input form-input-sm" placeholder="Max Qty (opt)">
                        <input type="number" step="0.1" name="discount_percent" class="form-input form-input-sm" placeholder="Discount %" required>
                        <button type="submit" class="btn btn-primary btn-sm">+ Add</button>
                    </form>

                    <table class="admin-table" style="font-size:0.85rem;">
                        <thead>
                            <tr>
                                <th>Quantity Range</th>
                                <th>Discount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productTiers)): ?>
                                <tr><td colspan="3" style="text-align:center; color:var(--color-text-muted);">No quantity tiers configured yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($productTiers as $t): ?>
                                    <tr>
                                        <td><strong><?= $t['min_quantity'] ?><?= $t['max_quantity'] ? ' – ' . $t['max_quantity'] : '+' ?> units</strong></td>
                                        <td><span class="badge badge-success"><?= $t['discount_percent'] ?>% Off</span></td>
                                        <td>
                                            <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Remove tier?');" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_tier">
                                                <input type="hidden" name="tier_id" value="<?= $t['id'] ?>">
                                                <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
                                                <button type="submit" class="btn btn-outline btn-xs" style="color:var(--color-crimson);">Del</button>
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
    <!-- PRODUCT LISTING TABLE -->
    <div class="admin-filter-bar">
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <a href="<?= url('admin/products.php') ?>" class="admin-tab-btn <?= empty($categoryFilter) ? 'active' : '' ?>">
                All Categories (<?= count($products) ?>)
            </a>
            <?php foreach ($categories as $c): ?>
                <a href="<?= url('admin/products.php?category=' . $c['id']) ?>" class="admin-tab-btn <?= $categoryFilter === $c['id'] ? 'active' : '' ?>">
                    <?= e($c['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form method="GET" action="<?= url('admin/products.php') ?>" class="admin-search-form" style="display: flex; gap: 8px;">
            <?php if ($categoryFilter): ?>
                <input type="hidden" name="category" value="<?= $categoryFilter ?>">
            <?php endif; ?>
            <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search product name or SKU..." class="form-input form-input-sm" style="width: 240px;">
            <button type="submit" class="btn btn-secondary btn-sm">Search</button>
            <?php if ($searchTerm || $categoryFilter): ?>
                <a href="<?= url('admin/products.php') ?>" class="btn btn-outline btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Image</th>
                        <th style="width: 100px;">SKU</th>
                        <th>Product Title &amp; Slug</th>
                        <th>Category</th>
                        <th>From Price</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No products found matching your filter criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <div style="width: 48px; height: 48px; border-radius: 4px; overflow: hidden; background: #ffffff; border: 1px solid var(--color-border); display:flex; align-items:center; justify-content:center; padding:3px;">
                                        <img src="<?= asset_url($p['image_url'] ?: 'assets/images/gallery_1.jpg') ?>" alt="<?= e($p['title']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: var(--color-gold); font-size: 0.82rem; background: rgba(185,154,97,0.12); padding: 2px 6px; border-radius: 4px; letter-spacing:0.04em;">
                                        <?= e($p['sku']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <strong style="color: var(--color-navy); display:block; font-size:0.9rem;"><?= e($p['title']) ?></strong>
                                    <span style="font-size: 0.75rem; color: var(--color-royal);">/product/<?= e($p['slug']) ?>/</span>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size: 0.75rem;"><?= e($p['category_name'] ?: 'General') ?></span>
                                </td>
                                <td style="font-weight:700; color:var(--color-navy); font-size:0.85rem;">
                                    <?= e($p['starting_price'] ?: '—') ?>
                                </td>
                                <td>
                                    <?php if ($p['is_featured']): ?>
                                        <span class="badge badge-warning" style="font-size: 0.75rem;">★ Featured</span>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);">Standard</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?= url('admin/products.php') ?>" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-xs <?= $p['is_active'] ? 'btn-outline' : 'btn-secondary' ?>" style="<?= $p['is_active'] ? 'color: #2e7d32; border-color: #2e7d32;' : 'opacity: 0.6;' ?>">
                                            <?= $p['is_active'] ? '● Active' : '○ Hidden' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="<?= product_url($p['slug']) ?>" target="_blank" class="btn btn-outline btn-xs" title="View live product page">
                                            View ↗
                                        </a>
                                        <a href="<?= url('admin/products.php?edit_id=' . $p['id']) ?>" class="btn btn-outline btn-xs">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?= url('admin/products.php') ?>" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display: inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
                                                Del
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
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
