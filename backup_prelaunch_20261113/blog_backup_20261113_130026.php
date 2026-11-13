<?php
/**
 * OrderPrintex - Admin Blog Management CMS
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_permission('blog.view');
$adminUser = auth_user();

// Handle Blog Actions (Processed before header output to avoid "headers already sent" warnings)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/blog.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Save / Update Blog Post
    if ($action === 'save_post') {
        $postId = intval($_POST['post_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $categoryId = !empty($_POST['category_id']) && is_numeric($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $rawCatId = $_POST['category_id'] ?? '';
        $customCat = trim($_POST['custom_category'] ?? '');
        if (strtolower($rawCatId) === 'other' || !empty($customCat)) {
            if (!empty($customCat)) {
                $existingCat = db_fetch_one("SELECT id FROM blog_categories WHERE name = :name LIMIT 1", ['name' => $customCat]);
                if ($existingCat) {
                    $categoryId = (int)$existingCat['id'];
                } else {
                    $catSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $customCat)));
                    if (empty($catSlug)) $catSlug = 'cat-' . time();
                    $categoryId = db_insert('blog_categories', [
                        'name'        => $customCat,
                        'slug'        => $catSlug,
                        'description' => 'Custom category added via Article Editor'
                    ]);
                }
            } else {
                set_flash('error', 'Please enter a name for the custom blog category.');
                header('Location: ' . url('admin/blog.php' . ($postId ? '?edit_id=' . $postId : '?action=new')));
                exit;
            }
        }
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author = trim($_POST['author'] ?? 'OrderPrintex Editorial Team');
        $readTime = trim($_POST['read_time'] ?? '5 min read');
        $status = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
        $isSample = isset($_POST['is_sample']) ? 1 : 0;
        $seoTitle = trim($_POST['seo_title'] ?? '');
        $seoDesc = trim($_POST['seo_description'] ?? '');

        if (empty($title) || empty($content)) {
            set_flash('error', 'Article title and body content are required.');
            header('Location: ' . url('admin/blog.php' . ($postId ? '?edit_id=' . $postId : '?action=new')));
            exit;
        }

        // Slug generation
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
        }

        // Handle Featured Image upload with automatic WebP conversion
        $featuredImage = null;
        if (!empty($_FILES['featured_image_file']['name'])) {
            $uploadResult = handle_blog_image_upload($_FILES['featured_image_file']);
            if ($uploadResult['success']) {
                $featuredImage = $uploadResult['file_path'];
            } else {
                set_flash('error', 'Image upload failed: ' . $uploadResult['error']);
                header('Location: ' . url('admin/blog.php' . ($postId ? '?edit_id=' . $postId : '?action=new')));
                exit;
            }
        } elseif (!empty($_POST['featured_image_path'])) {
            $featuredImage = trim($_POST['featured_image_path']);
        }

        if ($postId > 0) {
            $updateData = [
                'category_id'     => $categoryId,
                'title'           => $title,
                'slug'            => $slug,
                'excerpt'         => $excerpt,
                'content'         => $content,
                'author'          => $author,
                'read_time'       => $readTime,
                'status'          => $status,
                'is_sample'       => $isSample,
                'seo_title'       => $seoTitle,
                'seo_description' => $seoDesc,
                'updated_at'      => date('Y-m-d H:i:s')
            ];
            if ($featuredImage) {
                $updateData['featured_image'] = $featuredImage;
            }

            db_update('blog_posts', $updateData, 'id = :id', ['id' => $postId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'update_blog_post', "Updated article: {$title}");
            set_flash('success', "Article '{$title}' updated successfully.");
        } else {
            // Check slug uniqueness
            $check = db_fetch_one("SELECT id FROM blog_posts WHERE slug = :s", ['s' => $slug]);
            if ($check) {
                $slug .= '-' . rand(100, 999);
            }

            $newId = db_insert('blog_posts', [
                'category_id'     => $categoryId,
                'title'           => $title,
                'slug'            => $slug,
                'excerpt'         => $excerpt,
                'content'         => $content,
                'featured_image'  => $featuredImage ?: 'assets/images/branding_stationery.jpg',
                'author'          => $author,
                'read_time'       => $readTime,
                'status'          => $status,
                'is_sample'       => $isSample,
                'views'           => 0,
                'seo_title'       => $seoTitle,
                'seo_description' => $seoDesc
            ]);

            @seo_sync_all();
            log_activity($adminUser['id'], 'create_blog_post', "Published new article: {$title}");
            set_flash('success', "New article '{$title}' created successfully.");
        }

        header('Location: ' . url('admin/blog.php'));
        exit;
    }

    // 2. Delete Post
    if ($action === 'delete_post') {
        $postId = intval($_POST['post_id'] ?? 0);
        $post = db_fetch_one("SELECT title FROM blog_posts WHERE id = :id", ['id' => $postId]);
        if ($post) {
            db_delete('blog_posts', 'id = :id', ['id' => $postId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'delete_blog_post', "Deleted article: {$post['title']}");
            set_flash('success', "Article '{$post['title']}' deleted.");
        }
        header('Location: ' . url('admin/blog.php'));
        exit;
    }

    // 3. Quick Add Category
    if ($action === 'add_category') {
        $catName = trim($_POST['category_name'] ?? '');
        $catDesc = trim($_POST['category_desc'] ?? '');
        if (!empty($catName)) {
            $catSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $catName)));
            db_insert('blog_categories', [
                'name'        => $catName,
                'slug'        => $catSlug,
                'description' => $catDesc
            ]);
            set_flash('success', "Category '{$catName}' created.");
        }
        header('Location: ' . url('admin/blog.php'));
        exit;
    }
}

$adminTitle = "Blog & Content Management";
require_once __DIR__ . '/header.php';

// Fetch categories
$categories = db_fetch_all("SELECT * FROM blog_categories ORDER BY name ASC");

// Form mode check
$subAction = $_GET['action'] ?? '';
$editId = intval($_GET['edit_id'] ?? 0);
$editPost = null;

if ($editId > 0) {
    $editPost = db_fetch_one("SELECT * FROM blog_posts WHERE id = :id", ['id' => $editId]);
}

// Listing Filters
$categoryFilter = intval($_GET['category'] ?? 0);
$statusFilter = trim($_GET['status'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');

$sql = "SELECT p.*, c.name as category_name
        FROM blog_posts p
        LEFT JOIN blog_categories c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

if ($categoryFilter > 0) {
    $sql .= " AND p.category_id = :cid";
    $params['cid'] = $categoryFilter;
}

if ($statusFilter !== '') {
    $sql .= " AND p.status = :st";
    $params['st'] = $statusFilter;
}

if ($searchTerm !== '') {
    $sql .= " AND (p.title LIKE :s OR p.excerpt LIKE :s OR p.slug LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY p.created_at DESC";
$posts = db_fetch_all($sql, $params);
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Blog CMS &amp; Editorial Hub</h1>
        <p class="admin-page-subtitle">Publish technical printing tutorials, buyer guides, and industry case studies with clean SEO URLs.</p>
    </div>
    <div class="admin-topbar-actions">
        <?php if ($editPost || $subAction === 'new'): ?>
            <a href="<?= url('admin/blog.php') ?>" class="btn btn-outline btn-sm">
                &larr; Back to Articles
            </a>
        <?php else: ?>
            <a href="<?= url('admin/blog.php?action=new') ?>" class="btn btn-primary btn-sm">
                <span>+ Write New Article</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($editPost || $subAction === 'new'): ?>
    <!-- EDIT / CREATE ARTICLE FORM -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $editPost ? 'Edit Article: ' . e($editPost['title']) : 'Create New Blog Article' ?></h2>
        </div>
        <div class="admin-card-body" style="padding: 24px;">
            <form method="POST" action="<?= url('admin/blog.php') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="save_post">
                <input type="hidden" name="post_id" value="<?= $editPost ? $editPost['id'] : 0 ?>">

                <div class="admin-split-grid">
                    <!-- Left: Content & Hierarchy -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label required">Article Title</label>
                            <input type="text" name="title" class="form-input" required 
                                   value="<?= e($editPost['title'] ?? '') ?>" placeholder="e.g. Complete Guide to Business Card Printing: Paper Weights & Finishes">
                        </div>

                        <div class="admin-form-2col">
                            <div class="form-group">
                                <label class="form-label">SEO Clean Slug (URL)</label>
                                <input type="text" name="slug" class="form-input" 
                                       value="<?= e($editPost['slug'] ?? '') ?>" placeholder="e.g. complete-guide-to-business-card-printing">
                                <small style="font-size:0.75rem; color:var(--color-text-muted);">Will appear as: /blog/slug-name/</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select dynamic-other-select"
                                        data-enable-other="true"
                                        data-other-name="custom_category"
                                        data-other-label="Enter New Blog Category Name"
                                        data-other-placeholder="e.g. Sustainable Printing, DTF Masterclass, Prepress Checklist">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($editPost && $editPost['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= e($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="other">Other (Create New Category)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Short Excerpt / Lead Summary</label>
                            <textarea name="excerpt" class="form-input" rows="3" required placeholder="A brief 2-3 sentence teaser shown on blog archive cards and meta descriptions..."><?= e($editPost['excerpt'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Full Article Content (HTML / Markdown supported)</label>
                            <textarea name="content" class="form-input" rows="16" required placeholder="Enter the complete article body with headings (<h2>, <h3>), paragraphs, lists, and tips..."><?= e($editPost['content'] ?? '') ?></textarea>
                        </div>

                        <!-- SEO Metadata Card -->
                        <div class="admin-card" style="border: 1px solid var(--color-border); margin-top: 8px;">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">SEO Meta Configuration</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Meta Title (leave empty for auto-generated)</label>
                                    <input type="text" name="seo_title" class="form-input form-input-sm" value="<?= e($editPost['seo_title'] ?? '') ?>" placeholder="e.g. Business Card Printing Guide | OrderPrintex">
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="seo_description" class="form-input form-input-sm" rows="2" placeholder="Search engine snippet..."><?= e($editPost['seo_description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Publishing Options & Media -->
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        
                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Publishing Status</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 14px;">
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="published" <?= (!isset($editPost) || $editPost['status'] === 'published') ? 'selected' : '' ?>>
                                            ● Published (Live on Storefront)
                                        </option>
                                        <option value="draft" <?= ($editPost && $editPost['status'] === 'draft') ? 'selected' : '' ?>>
                                            ○ Draft (Hidden from Public)
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin:0;">
                                    <label class="form-label">Author Name</label>
                                    <input type="text" name="author" class="form-input form-input-sm" value="<?= e($editPost['author'] ?? 'OrderPrintex Editorial Team') ?>">
                                </div>

                                <div class="form-group" style="margin:0;">
                                    <label class="form-label">Estimated Read Time</label>
                                    <input type="text" name="read_time" class="form-input form-input-sm" value="<?= e($editPost['read_time'] ?? '5 min read') ?>" placeholder="e.g. 6 min read">
                                </div>

                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: #FBBF24; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); padding: 8px 12px; border-radius: 6px;">
                                    <input type="checkbox" name="is_sample" value="1" <?= (!isset($editPost) || $editPost['is_sample']) ? 'checked' : '' ?>>
                                    Mark as Demo / Sample Article
                                </label>
                            </div>
                        </div>

                        <!-- Featured Image Card -->
                        <div class="admin-card" style="border: 1px solid var(--color-border);">
                            <div class="admin-card-header" style="background: var(--color-ivory);">
                                <h3 class="admin-card-title" style="font-size: 0.9rem;">Featured Image</h3>
                            </div>
                            <div class="admin-card-body" style="padding: 16px;">
                                <?php if (!empty($editPost['featured_image'])): ?>
                                    <div style="margin-bottom: 12px; border-radius: 4px; overflow: hidden; border: 1px solid var(--color-border); height: 160px; background: var(--admin-bg-card-secondary, #171C2B);">
                                        <img src="<?= asset_url($editPost['featured_image']) ?>" alt="Article Image" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <input type="hidden" name="featured_image_path" value="<?= e($editPost['featured_image']) ?>">
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-bottom: 8px;">Upload new image to replace:</span>
                                <?php endif; ?>

                                <input type="file" name="featured_image_file" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp">
                                <small style="display: block; margin-top: 4px; color: var(--color-text-muted); font-size: 0.75rem;">
                                    Recommended: 1200x675px landscape image (JPG, PNG, WebP).
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                            <?= $editPost ? '💾 Save Article Changes' : '✨ Publish Article' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- ARTICLES LISTING TABLE -->
    <div class="admin-filter-bar">
        <div class="admin-filter-tabs">
            <a href="<?= url('admin/blog.php') ?>" class="admin-tab-btn <?= (empty($categoryFilter) && empty($statusFilter)) ? 'active' : '' ?>">
                All Articles (<?= count($posts) ?>)
            </a>
            <a href="<?= url('admin/blog.php?status=published') ?>" class="admin-tab-btn <?= ($statusFilter === 'published') ? 'active' : '' ?>">
                Published
            </a>
            <a href="<?= url('admin/blog.php?status=draft') ?>" class="admin-tab-btn <?= ($statusFilter === 'draft') ? 'active' : '' ?>">
                Drafts
            </a>
            <?php foreach ($categories as $c): ?>
                <a href="<?= url('admin/blog.php?category=' . $c['id']) ?>" class="admin-tab-btn <?= $categoryFilter === $c['id'] ? 'active' : '' ?>">
                    <?= e($c['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form method="GET" action="<?= url('admin/blog.php') ?>" class="admin-search-form">
            <?php if ($categoryFilter): ?>
                <input type="hidden" name="category" value="<?= $categoryFilter ?>">
            <?php endif; ?>
            <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search article title..." class="form-input form-input-sm" style="width: 220px;">
            <button type="submit" class="btn btn-secondary btn-sm">Search</button>
            <?php if ($searchTerm || $categoryFilter || $statusFilter): ?>
                <a href="<?= url('admin/blog.php') ?>" class="btn btn-outline btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Media</th>
                        <th>Article Title &amp; Slug</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Read Time</th>
                        <th>Date</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No blog articles found matching your criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $p): ?>
                            <tr>
                                <td>
                                    <div style="width: 50px; height: 50px; border-radius: 4px; overflow: hidden; background: var(--admin-bg-card-secondary, #171C2B); border: 1px solid var(--color-border);">
                                        <img src="<?= asset_url($p['featured_image'] ?: 'assets/images/branding_stationery.jpg') ?>" alt="Thumb" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: var(--color-navy); display: block; font-size: 0.9rem;">
                                        <?= e($p['title']) ?>
                                    </strong>
                                    <span style="font-size: 0.75rem; color: var(--color-royal);">
                                        /blog/<?= e($p['slug']) ?>/
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size: 0.75rem;"><?= e($p['category_name'] ?: 'General') ?></span>
                                </td>
                                <td>
                                    <?php if ($p['status'] === 'published'): ?>
                                        <span class="badge badge-success" style="font-size: 0.75rem;">● Published</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning" style="font-size: 0.75rem;">○ Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($p['is_sample']): ?>
                                        <span class="badge" style="background:rgba(245, 158, 11, 0.15); color:#FBBF24; font-size: 0.7rem; border:1px solid rgba(245, 158, 11, 0.3);">Sample Demo</span>
                                    <?php else: ?>
                                        <span class="badge badge-info" style="font-size: 0.7rem;">Original</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($p['read_time']) ?></td>
                                <td style="font-size: 0.8rem; color: var(--color-text-muted);"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="<?= blog_url($p['slug']) ?>" target="_blank" class="btn btn-outline btn-xs" title="View live article">
                                            View ↗
                                        </a>
                                        <a href="<?= url('admin/blog.php?edit_id=' . $p['id']) ?>" class="btn btn-outline btn-xs">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?= url('admin/blog.php') ?>" style="display: inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete_post">
                                            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                                            <button type="button" data-confirm="Are you sure you want to permanently delete this blog article?" data-confirm-title="Delete Article?" data-confirm-item="<?= e($p['title']) ?>" data-destructive="true" data-confirm-btn="Yes, Delete" class="btn btn-outline btn-xs" style="color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);">
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
