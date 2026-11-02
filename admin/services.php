<?php
/**
 * OrderPrintex - Admin Services Management
 */

$adminTitle = "Manage Printing Services";
require_once __DIR__ . '/header.php';

// Handle Post Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/services.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'save_service') {
        $serviceId = intval($_POST['service_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $heroTagline = trim($_POST['hero_tagline'] ?? '');
        $badge = trim($_POST['badge'] ?? '');
        $shortDesc = trim($_POST['short_desc'] ?? '');
        $fullDescription = trim($_POST['full_description'] ?? '');
        $specifications = trim($_POST['specifications'] ?? '');
        $artworkGuidelines = trim($_POST['artwork_guidelines'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($title)) {
            set_flash('error', 'Service title cannot be empty.');
            header('Location: ' . url('admin/services.php' . ($serviceId ? '?edit_id=' . $serviceId : '')));
            exit;
        }

        // Handle Image Upload if provided
        $imageUrl = null;
        if (!empty($_FILES['service_image']['name'])) {
            $uploadResult = handle_file_upload($_FILES['service_image'], ['jpg', 'jpeg', 'png', 'webp']);
            if ($uploadResult['success']) {
                $imageUrl = 'uploads/' . $uploadResult['stored_name'];
            }
        }

        if ($serviceId > 0) {
            $updateData = [
                'title'              => $title,
                'hero_tagline'       => $heroTagline,
                'badge'              => $badge,
                'short_description'  => $shortDesc,
                'full_description'   => $fullDescription,
                'specifications'     => $specifications,
                'artwork_guidelines' => $artworkGuidelines,
                'is_active'          => $isActive,
                'display_order'      => $displayOrder
            ];
            if ($imageUrl) {
                $updateData['image_url'] = $imageUrl;
            }

            db_update('services', $updateData, 'id = :id', ['id' => $serviceId]);
            log_activity($adminUser['id'], 'update_service', "Updated service: {$title}");
            set_flash('success', "Service '{$title}' updated successfully.");
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            db_insert('services', [
                'title'              => $title,
                'slug'               => $slug,
                'hero_tagline'       => $heroTagline,
                'badge'              => $badge ?: 'Popular',
                'short_description'  => $shortDesc,
                'full_description'   => $fullDescription,
                'specifications'     => $specifications,
                'artwork_guidelines' => $artworkGuidelines,
                'image_url'          => $imageUrl ?: 'assets/images/gallery_1.jpg',
                'is_active'          => $isActive,
                'display_order'      => $displayOrder
            ]);
            log_activity($adminUser['id'], 'create_service', "Created service: {$title}");
            set_flash('success', "New printing service created.");
        }

        header('Location: ' . url('admin/services.php'));
        exit;
    }
}

// Edit Mode or List Mode
$editId = intval($_GET['edit_id'] ?? 0);
$editService = null;
if ($editId) {
    $editService = db_fetch_one("SELECT * FROM services WHERE id = :id", ['id' => $editId]);
}

$services = db_fetch_all("
    SELECT s.*, 
           (SELECT COUNT(*) FROM products p WHERE p.service_id = s.id) as product_count,
           (SELECT COUNT(*) FROM enquiries e WHERE e.service_id = s.id) as enquiry_count
    FROM services s 
    ORDER BY s.display_order ASC, s.id ASC
");
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Printing Services</h1>
        <p class="admin-page-subtitle">Configure studio capabilities, press specs, artwork guidelines, and client-facing service presentations.</p>
    </div>
    <div class="admin-topbar-actions">
        <?php if ($editService): ?>
            <a href="<?= url('admin/services.php') ?>" class="btn btn-outline btn-sm">&larr; Back to Services</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($editService): ?>
    <!-- SERVICE EDIT FORM -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Edit Printing Service: <?= e($editService['title']) ?></h2>
            <span style="font-size: 0.8rem; color: var(--color-royal); font-weight: 600;">Slug: /services/<?= e($editService['slug']) ?>.php</span>
        </div>
        <div class="admin-card-body" style="padding: 24px;">
            <form method="POST" action="<?= url('admin/services.php') ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="save_service">
                <input type="hidden" name="service_id" value="<?= $editService['id'] ?>">

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label required">Service Title</label>
                            <input type="text" name="title" class="form-input" required value="<?= e($editService['title']) ?>">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Hero Tagline / Subtitle</label>
                                <input type="text" name="hero_tagline" class="form-input" value="<?= e($editService['hero_tagline']) ?>" placeholder="e.g. Industrial-Grade Vibrant Film Transfers">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Service Badge / Highlight</label>
                                <input type="text" name="badge" class="form-input" value="<?= e($editService['badge']) ?>" placeholder="e.g. Direct-to-Film, Permanent UV Decal">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Short Description (Cards & Summaries)</label>
                            <textarea name="short_desc" class="form-input" rows="3"><?= e($editService['short_description']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Full Technical Description & Workflow</label>
                            <textarea name="full_description" class="form-input" rows="8"><?= e($editService['full_description']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Printing Specifications & Press Settings</label>
                            <textarea name="specifications" class="form-input" rows="6"><?= e($editService['specifications']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Artwork Guidelines & Prepress Advice</label>
                            <textarea name="artwork_guidelines" class="form-input" rows="5"><?= e($editService['artwork_guidelines']) ?></textarea>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Featured Image</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px;">
                                <?php if (!empty($editService['image_url'])): ?>
                                    <div style="margin-bottom: 12px; border-radius: 4px; overflow: hidden; border: 1px solid var(--color-border); height: 160px; background: #eee;">
                                        <img src="<?= asset_url($editService['image_url']) ?>" alt="Service Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="service_image" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp">
                                <small style="display: block; margin-top: 4px; color: var(--color-text-muted); font-size: 0.75rem;">
                                    High-definition workshop or product photo.
                                </small>
                            </div>
                        </div>

                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Visibility & Order</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 14px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                    <input type="checkbox" name="is_active" value="1" <?= $editService['is_active'] ? 'checked' : '' ?>>
                                    Active / Published on Website
                                </label>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" style="font-size: 0.8rem;">Sort Order</label>
                                    <input type="number" name="display_order" class="form-input form-input-sm" value="<?= $editService['display_order'] ?>" style="width: 100px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                            💾 Save Service Changes
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- SERVICES LIST TABLE -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Service Title</th>
                        <th>Badge / Tagline</th>
                        <th>Linked Catalog</th>
                        <th>Enquiries</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $srv): ?>
                        <tr>
                            <td>
                                <div style="width: 50px; height: 50px; border-radius: 4px; overflow: hidden; background: #eee; border: 1px solid var(--color-border);">
                                    <img src="<?= asset_url($srv['image_url'] ?: 'assets/images/gallery_1.jpg') ?>" alt="<?= e($srv['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--color-navy);"><?= e($srv['title']) ?></strong>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                    <a href="<?= url('services/' . $srv['slug'] . '.php') ?>" target="_blank" style="color: var(--color-royal);">
                                        View Page &rarr;
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info" style="font-size: 0.75rem;"><?= e($srv['badge']) ?></span>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 2px;"><?= e($srv['hero_tagline']) ?></div>
                            </td>
                            <td style="font-weight: 600;">
                                <a href="<?= url('admin/products.php') ?>" style="color: var(--color-navy);">
                                    <?= $srv['product_count'] ?> products
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="font-size: 0.75rem;"><?= $srv['enquiry_count'] ?> enquiries</span>
                            </td>
                            <td>
                                <span class="badge <?= $srv['is_active'] ? 'badge-success' : 'badge-neutral' ?>">
                                    <?= $srv['is_active'] ? 'Active' : 'Hidden' ?>
                                </span>
                            </td>
                            <td style="font-weight: 600;"><?= $srv['display_order'] ?></td>
                            <td>
                                <a href="<?= url('admin/services.php?edit_id=' . $srv['id']) ?>" class="btn btn-outline btn-xs">
                                    Edit Specifications &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
