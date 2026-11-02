<?php
/**
 * OrderPrintex - Admin Announcement Bar & Workshop Equipment Manager
 */

$adminTitle = "Promotions & Workshop Equipment";
require_once __DIR__ . '/header.php';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/announcement.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save Announcement Message
    if ($action === 'save_announcement') {
        $annId = intval($_POST['announcement_id'] ?? 0);
        $messageText = trim($_POST['message_text'] ?? '');
        $linkUrl = trim($_POST['link_url'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (empty($messageText)) {
            set_flash('error', 'Message text cannot be empty.');
        } else {
            if ($annId > 0) {
                db_update('announcement_bar', [
                    'message_text'  => $messageText,
                    'link_url'      => $linkUrl,
                    'is_active'     => $isActive,
                    'display_order' => $displayOrder
                ], 'id = :id', ['id' => $annId]);
                set_flash('success', "Announcement message updated.");
            } else {
                db_insert('announcement_bar', [
                    'message_text'  => $messageText,
                    'link_url'      => $linkUrl,
                    'is_active'     => $isActive,
                    'display_order' => $displayOrder
                ]);
                set_flash('success', "New announcement message created.");
            }
        }
        header('Location: ' . url('admin/announcement.php'));
        exit;
    }

    // 2. Delete Announcement Message
    if ($action === 'delete_announcement') {
        $annId = intval($_POST['announcement_id'] ?? 0);
        if ($annId > 0) {
            db_delete('announcement_bar', 'id = :id', ['id' => $annId]);
            set_flash('success', "Announcement message removed.");
        }
        header('Location: ' . url('admin/announcement.php'));
        exit;
    }

    // 3. Save Equipment Tool
    if ($action === 'save_equipment') {
        $toolId = intval($_POST['tool_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? 'Printing Press');
        $purpose = trim($_POST['purpose'] ?? '');
        $impact = trim($_POST['impact'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);

        if (!empty($name)) {
            if ($toolId > 0) {
                db_update('equipment_tools', [
                    'name'          => $name,
                    'category'      => $category,
                    'purpose'       => $purpose,
                    'impact'        => $impact,
                    'display_order' => $displayOrder
                ], 'id = :id', ['id' => $toolId]);
                set_flash('success', "Equipment tool '{$name}' updated.");
            } else {
                db_insert('equipment_tools', [
                    'name'          => $name,
                    'category'      => $category,
                    'purpose'       => $purpose,
                    'impact'        => $impact,
                    'display_order' => $displayOrder
                ]);
                set_flash('success', "New equipment '{$name}' registered.");
            }
        }
        header('Location: ' . url('admin/announcement.php'));
        exit;
    }

    // 4. Delete Equipment Tool
    if ($action === 'delete_equipment') {
        $toolId = intval($_POST['tool_id'] ?? 0);
        if ($toolId > 0) {
            db_delete('equipment_tools', 'id = :id', ['id' => $toolId]);
            set_flash('success', "Equipment tool removed.");
        }
        header('Location: ' . url('admin/announcement.php'));
        exit;
    }
}

// Fetch Announcements & Equipment
$announcements = db_fetch_all("SELECT * FROM announcement_bar ORDER BY display_order ASC, id ASC");
$equipmentList = db_fetch_all("SELECT * FROM equipment_tools ORDER BY display_order ASC, id ASC");

// Edit state for announcement if requested
$editAnnId = intval($_GET['edit_ann'] ?? 0);
$editAnnouncement = null;
if ($editAnnId > 0) {
    $editAnnouncement = db_fetch_one("SELECT * FROM announcement_bar WHERE id = :id", ['id' => $editAnnId]);
}
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Top Announcement Bar &amp; Workshop Equipment</h1>
        <p class="admin-page-subtitle">Configure rotating promotional notices and verified machinery displayed on the About Us page.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    
    <!-- LEFT: ANNOUNCEMENT BAR MESSAGES -->
    <div>
        <div class="admin-card" style="margin-bottom: 24px;">
            <div class="admin-card-header">
                <h2 class="admin-card-title"><?= $editAnnouncement ? 'Edit Announcement Message' : 'Add Announcement Message' ?></h2>
            </div>
            <div class="admin-card-body" style="padding: 20px;">
                <form method="POST" action="<?= url('admin/announcement.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="save_announcement">
                    <input type="hidden" name="announcement_id" value="<?= $editAnnouncement ? $editAnnouncement['id'] : 0 ?>">

                    <div class="form-group">
                        <label class="form-label required">Announcement Text</label>
                        <input type="text" name="message_text" class="form-input" required 
                               value="<?= e($editAnnouncement['message_text'] ?? '') ?>" 
                               placeholder="e.g. Free Prepress Artwork Audit on all Bulk DTF Orders">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link Destination URL (optional)</label>
                        <input type="text" name="link_url" class="form-input" 
                               value="<?= e($editAnnouncement['link_url'] ?? '') ?>" 
                               placeholder="e.g. /products/ or /services/dtf-printing/">
                    </div>

                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                            <input type="checkbox" name="is_active" value="1" <?= (!isset($editAnnouncement) || $editAnnouncement['is_active']) ? 'checked' : '' ?>>
                            Active / Rotating
                        </label>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label class="form-label" style="margin: 0; font-size: 0.85rem;">Display Order:</label>
                            <input type="number" name="display_order" class="form-input form-input-sm" style="width: 80px;" value="<?= $editAnnouncement['display_order'] ?? 0 ?>">
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <?= $editAnnouncement ? 'Update Announcement' : '+ Add Announcement' ?>
                        </button>
                        <?php if ($editAnnouncement): ?>
                            <a href="<?= url('admin/announcement.php') ?>" class="btn btn-outline btn-sm">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Active Announcement Ticker Messages</h3>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($announcements)): ?>
                            <tr><td colspan="4" style="text-align:center; padding:20px; color:var(--color-text-muted);">No messages configured.</td></tr>
                        <?php else: ?>
                            <?php foreach ($announcements as $ann): ?>
                                <tr>
                                    <td>
                                        <strong style="color:var(--color-navy);"><?= e($ann['message_text']) ?></strong>
                                        <?php if (!empty($ann['link_url'])): ?>
                                            <div style="font-size:0.75rem; color:var(--color-royal);"><?= e($ann['link_url']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $ann['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Paused</span>' ?>
                                    </td>
                                    <td><?= $ann['display_order'] ?></td>
                                    <td>
                                        <div style="display:flex; gap:6px;">
                                            <a href="<?= url('admin/announcement.php?edit_ann=' . $ann['id']) ?>" class="btn btn-outline btn-xs">Edit</a>
                                            <form method="POST" action="<?= url('admin/announcement.php') ?>" onsubmit="return confirm('Delete message?');" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_announcement">
                                                <input type="hidden" name="announcement_id" value="<?= $ann['id'] ?>">
                                                <button type="submit" class="btn btn-outline btn-xs" style="color:var(--color-crimson);">Del</button>
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
    </div>

    <!-- RIGHT: WORKSHOP MACHINERY & TOOLS -->
    <div>
        <div class="admin-card" style="margin-bottom: 24px;">
            <div class="admin-card-header">
                <h2 class="admin-card-title">Add Workshop Machine / Tool</h2>
            </div>
            <div class="admin-card-body" style="padding: 20px;">
                <form method="POST" action="<?= url('admin/announcement.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="save_equipment">
                    <input type="hidden" name="tool_id" value="0">

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label required">Machine / Software Name</label>
                            <input type="text" name="name" class="form-input" required placeholder="e.g. Dual-Head Micro-Piezo DTF Production Press">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-input" value="Printing Press" placeholder="e.g. Printing Press / Curing">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Primary Purpose</label>
                        <textarea name="purpose" class="form-input" rows="2" required placeholder="High-speed continuous transfer printing onto PET film..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Quality &amp; Production Contribution</label>
                        <textarea name="impact" class="form-input" rows="2" required placeholder="Ensures vibrant CMYK saturation and opaque white underbase..."></textarea>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label class="form-label" style="margin: 0; font-size: 0.85rem;">Display Order:</label>
                            <input type="number" name="display_order" class="form-input form-input-sm" style="width: 80px;" value="0">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">+ Register Machine</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Active Machinery &amp; Tools (About Us Page)</h3>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tool Name</th>
                            <th>Category</th>
                            <th>Purpose</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($equipmentList)): ?>
                            <tr><td colspan="4" style="text-align:center; padding:20px; color:var(--color-text-muted);">No equipment listed.</td></tr>
                        <?php else: ?>
                            <?php foreach ($equipmentList as $eq): ?>
                                <tr>
                                    <td>
                                        <strong style="color:var(--color-navy);"><?= e($eq['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info" style="font-size:0.75rem;"><?= e($eq['category']) ?></span>
                                    </td>
                                    <td style="font-size:0.8rem; color:var(--color-text-body); max-width:240px;">
                                        <?= e($eq['purpose']) ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?= url('admin/announcement.php') ?>" onsubmit="return confirm('Remove machine?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete_equipment">
                                            <input type="hidden" name="tool_id" value="<?= $eq['id'] ?>">
                                            <button type="submit" class="btn btn-outline btn-xs" style="color:var(--color-crimson);">Remove</button>
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

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
