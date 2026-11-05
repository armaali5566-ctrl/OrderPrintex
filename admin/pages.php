<?php
/**
 * OrderPrintex - Admin Website Pages CMS
 * Create, Edit & Manage Static & Custom Pages
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_admin();
$adminUser = auth_user();

// Pages CMS module decommissioned in simplified workflow.
header('Location: ' . url('admin/dashboard.php'));
exit;

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/pages.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save Page
    if ($action === 'save_page') {
        $pageId = intval($_POST['page_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if (empty($title)) {
            set_flash('error', 'Page title is required.');
            header('Location: ' . url('admin/pages.php' . ($pageId ? '?edit_id=' . $pageId : '?action=new')));
            exit;
        }

        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }

        if ($pageId > 0) {
            $check = db_fetch_one("SELECT id FROM pages WHERE slug = :s AND id != :id", ['s' => $slug, 'id' => $pageId]);
            if ($check) $slug .= '-' . rand(10, 99);

            db_update('pages', [
                'title'            => $title,
                'slug'             => $slug,
                'content'          => $content,
                'meta_title'       => $metaTitle,
                'meta_description' => $metaDesc,
                'is_published'     => $isPublished,
                'updated_at'       => date('Y-m-d H:i:s')
            ], 'id = :id', ['id' => $pageId]);

            @seo_sync_all();
            log_activity($adminUser['id'], 'update_page', "Updated CMS page: {$title}");
            set_flash('success', "Page '{$title}' updated successfully.");
            header('Location: ' . url('admin/pages.php?edit_id=' . $pageId));
            exit;
        } else {
            $check = db_fetch_one("SELECT id FROM pages WHERE slug = :s", ['s' => $slug]);
            if ($check) $slug .= '-' . rand(10, 99);

            $newId = db_insert('pages', [
                'title'            => $title,
                'slug'             => $slug,
                'content'          => $content,
                'meta_title'       => $metaTitle,
                'meta_description' => $metaDesc,
                'is_published'     => $isPublished
            ]);

            @seo_sync_all();
            log_activity($adminUser['id'], 'create_page', "Created CMS page: {$title}");
            set_flash('success', "Page '{$title}' created successfully.");
            header('Location: ' . url('admin/pages.php?edit_id=' . $newId));
            exit;
        }
    }

    // 2. Delete Page
    if ($action === 'delete_page') {
        $pageId = intval($_POST['page_id'] ?? 0);
        $pg = db_fetch_one("SELECT * FROM pages WHERE id = :id", ['id' => $pageId]);
        if ($pg) {
            db_delete('pages', 'id = :id', ['id' => $pageId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'delete_page', "Deleted CMS page: {$pg['title']}");
            set_flash('success', "Page '{$pg['title']}' deleted.");
        }
        header('Location: ' . url('admin/pages.php'));
        exit;
    }
}

// Fetch all pages
$pages = db_fetch_all("SELECT * FROM pages ORDER BY title ASC");

$editId = intval($_GET['edit_id'] ?? 0);
$isNew = (isset($_GET['action']) && $_GET['action'] === 'new');
$editPage = null;

if ($editId > 0) {
    $editPage = db_fetch_one("SELECT * FROM pages WHERE id = :id", ['id' => $editId]);
}

$adminTitle = "Website Pages CMS";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Website Pages CMS
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Manage standalone content pages, policy documents, and company disclosures.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <?php if ($editPage || $isNew): ?>
      <a href="<?= url('admin/pages.php') ?>" class="btn btn-outline btn-sm">
        &larr; All Pages
      </a>
    <?php else: ?>
      <a href="<?= url('admin/homepage.php') ?>" class="btn btn-outline btn-sm">
        🏠 Homepage CMS
      </a>
      <a href="<?= url('admin/pages.php?action=new') ?>" class="btn btn-primary btn-sm">
        + Create New Page
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($editPage || $isNew): ?>
  <!-- Edit / Create Page Form -->
  <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:2rem; box-shadow: var(--shadow-card); max-width:900px; margin:0 auto;">
    <h3 style="font-size:1.25rem; color:var(--navy); font-weight:700; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
      <?= ($editPage) ? 'Edit Page: ' . e($editPage['title']) : 'Create New Page' ?>
    </h3>

    <form method="POST" action="<?= url('admin/pages.php') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save_page">
      <input type="hidden" name="page_id" value="<?= $editPage['id'] ?? 0 ?>">

      <div class="admin-form-2col" style="margin-bottom:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Page Title *</label>
          <input type="text" name="title" required class="form-control" placeholder="e.g. Terms & Conditions" value="<?= e($editPage['title'] ?? '') ?>">
        </div>
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">URL Slug</label>
          <input type="text" name="slug" class="form-control" placeholder="e.g. terms-and-conditions" value="<?= e($editPage['slug'] ?? '') ?>">
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Page Content (HTML / Text) *</label>
        <textarea name="content" rows="12" class="form-control" style="font-family:monospace; font-size:0.9rem;" required><?= e($editPage['content'] ?? '') ?></textarea>
      </div>

      <div style="background:var(--ivory); border:1px solid var(--border-light); border-radius:8px; padding:1.25rem; margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; color:var(--navy); font-weight:700; margin-bottom:0.75rem;">Search Engine Optimization (SEO)</h4>
        
        <div style="margin-bottom:0.85rem;">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" placeholder="e.g. Terms of Service & Prepress Warranties | OrderPrintex" value="<?= e($editPage['meta_title'] ?? '') ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Meta Description</label>
          <textarea name="meta_description" rows="2" class="form-control" placeholder="Summary for Google search results..."><?= e($editPage['meta_description'] ?? '') ?></textarea>
        </div>
      </div>

      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; border-top:1px solid var(--border-light); padding-top:1.25rem;">
        <label style="display:inline-flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.9rem; color:var(--navy);">
          <input type="checkbox" name="is_published" value="1" <?= (!isset($editPage) || !empty($editPage['is_published'])) ? 'checked' : '' ?>>
          <span>Publish Page (Live on Storefront)</span>
        </label>

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
          <a href="<?= url('admin/pages.php') ?>" class="btn btn-outline btn-sm">Cancel</a>
          <button type="submit" class="btn btn-primary btn-sm">
            <?= ($editPage) ? 'Update Page' : 'Publish Page' ?>
          </button>
        </div>
      </div>
    </form>
  </div>
<?php else: ?>
  <!-- Pages List Table -->
  <div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
      <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin:0;">
        All Content Pages (<?= count($pages) ?>)
      </h3>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Page Title</th>
            <th>URL Slug</th>
            <th>Status</th>
            <th>Last Updated</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pages as $p): ?>
            <tr>
              <td>
                <strong><?= e($p['title']) ?></strong>
                <?php if (!empty($p['meta_title'])): ?>
                  <div style="font-size:0.75rem; color:var(--text-muted);"><?= e($p['meta_title']) ?></div>
                <?php endif; ?>
              </td>
              <td><code>/<?= e($p['slug']) ?>/</code></td>
              <td>
                <span class="badge" style="background:<?= $p['is_published'] ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)' ?>; color:<?= $p['is_published'] ? '#059669' : '#DC2626' ?>; font-weight:700;">
                  <?= $p['is_published'] ? '● Published' : '○ Draft' ?>
                </span>
              </td>
              <td style="color:var(--text-muted); font-size:0.8rem;">
                <?= date('M d, Y', strtotime($p['updated_at'] ?? $p['created_at'])) ?>
              </td>
              <td style="text-align:right; white-space:nowrap;">
                <a href="<?= url('admin/pages.php?edit_id=' . $p['id']) ?>" class="btn btn-xs btn-outline">✎ Edit</a>
                <form method="POST" action="<?= url('admin/pages.php') ?>" style="display:inline;" onsubmit="return confirm('Delete page \'<?= addslashes($p['title']) ?>\'?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete_page">
                  <input type="hidden" name="page_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn btn-xs btn-danger">✕</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
