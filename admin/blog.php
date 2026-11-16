<?php
/**
 * OrderPrintex - Admin Blog Management CMS
 * Professional Publishing Suite with Rich-Text Editor, Interactive FAQ Builder & Technical SEO Controls
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_permission('blog.view');
$adminUser = auth_user();

// Handle Blog Actions
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

        // Dynamic category creation
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
        $rawContent = $_POST['content'] ?? '';
        $content = sanitize_blog_content($rawContent);
        $author = trim($_POST['author'] ?? 'OrderPrintex Editorial Team');
        if (empty($author)) {
            $author = 'OrderPrintex Editorial Team';
        }

        // Status: button override support (Save Draft vs Publish)
        $status = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'published';
        if (isset($_POST['submit_action']) && $_POST['submit_action'] === 'draft') {
            $status = 'draft';
        } elseif (isset($_POST['submit_action']) && $_POST['submit_action'] === 'publish') {
            $status = 'published';
        }

        // Calculate or validate read time
        $readTime = trim($_POST['read_time'] ?? '');
        if (empty($readTime)) {
            $wordCount = str_word_count(strip_tags($content));
            $calcMins = max(1, (int)ceil($wordCount / 200));
            $readTime = $calcMins . ' min read';
        }

        $isSample = isset($_POST['is_sample']) ? 1 : 0;
        $publishedAt = !empty($_POST['published_at']) ? date('Y-m-d H:i:s', strtotime($_POST['published_at'])) : ($status === 'published' ? date('Y-m-d H:i:s') : null);

        // Metadata & SEO fields
        $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');
        $tags = trim($_POST['tags'] ?? '');
        $seoTitle = trim($_POST['seo_title'] ?? '');
        $seoDesc = trim($_POST['seo_description'] ?? '');
        $seoKeywords = trim($_POST['seo_keywords'] ?? '');
        $canonicalUrl = trim($_POST['canonical_url'] ?? '');
        $ogTitle = trim($_POST['og_title'] ?? '');
        $ogDescription = trim($_POST['og_description'] ?? '');
        $ogImage = trim($_POST['og_image'] ?? '');

        if (empty($title) || empty($content)) {
            set_flash('error', 'Article title and body content are required.');
            header('Location: ' . url('admin/blog.php' . ($postId ? '?edit_id=' . $postId : '?action=new')));
            exit;
        }

        // Slug generation and uniqueness
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
        }
        $slug = trim($slug, '-');
        if (empty($slug)) {
            $slug = 'article-' . time();
        }

        // Handle Featured Image upload
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

        $targetPostId = $postId;

        if ($postId > 0) {
            // Slug collision check for other posts
            $check = db_fetch_one("SELECT id FROM blog_posts WHERE slug = :s AND id != :id", ['s' => $slug, 'id' => $postId]);
            if ($check) {
                $slug .= '-' . rand(100, 999);
            }

            $updateData = [
                'category_id'        => $categoryId,
                'title'              => $title,
                'slug'               => $slug,
                'excerpt'            => $excerpt,
                'content'            => $content,
                'author'             => $author,
                'read_time'          => $readTime,
                'status'             => $status,
                'is_sample'          => $isSample,
                'published_at'       => $publishedAt,
                'featured_image_alt' => $featuredImageAlt,
                'tags'               => $tags,
                'seo_title'          => $seoTitle,
                'seo_description'    => $seoDesc,
                'seo_keywords'       => $seoKeywords,
                'canonical_url'      => $canonicalUrl,
                'og_title'           => $ogTitle,
                'og_description'     => $ogDescription,
                'og_image'           => $ogImage,
                'updated_at'         => date('Y-m-d H:i:s')
            ];
            if ($featuredImage) {
                $updateData['featured_image'] = $featuredImage;
            }

            db_update('blog_posts', $updateData, 'id = :id', ['id' => $postId]);
            log_activity($adminUser['id'], 'update_blog_post', "Updated article: {$title}");
            set_flash('success', "Article '{$title}' updated successfully.");
        } else {
            // Check slug uniqueness for new post
            $check = db_fetch_one("SELECT id FROM blog_posts WHERE slug = :s", ['s' => $slug]);
            if ($check) {
                $slug .= '-' . rand(100, 999);
            }

            $targetPostId = db_insert('blog_posts', [
                'category_id'        => $categoryId,
                'title'              => $title,
                'slug'               => $slug,
                'excerpt'            => $excerpt,
                'content'            => $content,
                'featured_image'     => $featuredImage ?: 'assets/images/branding_stationery.jpg',
                'featured_image_alt' => $featuredImageAlt ?: $title,
                'tags'               => $tags,
                'author'             => $author,
                'read_time'          => $readTime,
                'status'             => $status,
                'is_sample'          => $isSample,
                'published_at'       => $publishedAt,
                'views'              => 0,
                'seo_title'          => $seoTitle,
                'seo_description'    => $seoDesc,
                'seo_keywords'       => $seoKeywords,
                'canonical_url'      => $canonicalUrl,
                'og_title'           => $ogTitle,
                'og_description'     => $ogDescription,
                'og_image'           => $ogImage
            ]);

            log_activity($adminUser['id'], 'create_blog_post', "Created new article: {$title}");
            set_flash('success', "New article '{$title}' created successfully.");
        }

        // Synchronize FAQs in blog_post_faqs
        if ($targetPostId > 0) {
            db_delete('blog_post_faqs', 'post_id = :pid', ['pid' => $targetPostId]);

            if (!empty($_POST['faq_questions']) && is_array($_POST['faq_questions'])) {
                $questions = $_POST['faq_questions'];
                $answers = $_POST['faq_answers'] ?? [];
                $orderNum = 1;
                foreach ($questions as $idx => $q) {
                    $qText = trim($q);
                    $aText = trim($answers[$idx] ?? '');
                    if (!empty($qText) && !empty($aText)) {
                        db_insert('blog_post_faqs', [
                            'post_id'       => $targetPostId,
                            'question'      => $qText,
                            'answer'        => $aText,
                            'display_order' => $orderNum++
                        ]);
                    }
                }
            }
        }

        @seo_sync_all();

        // Redirect back to edit or listing
        if (isset($_POST['save_and_continue']) && $targetPostId > 0) {
            header('Location: ' . url('admin/blog.php?edit_id=' . $targetPostId));
        } else {
            header('Location: ' . url('admin/blog.php'));
        }
        exit;
    }

    // 2. Delete Post
    if ($action === 'delete_post') {
        $postId = intval($_POST['post_id'] ?? 0);
        $post = db_fetch_one("SELECT title FROM blog_posts WHERE id = :id", ['id' => $postId]);
        if ($post) {
            db_delete('blog_post_faqs', 'post_id = :pid', ['pid' => $postId]);
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

    // 4. Quick Toggle Status
    if ($action === 'toggle_status') {
        $postId = intval($_POST['post_id'] ?? 0);
        $post = db_fetch_one("SELECT id, title, status FROM blog_posts WHERE id = :id", ['id' => $postId]);
        if ($post) {
            $newStatus = ($post['status'] === 'published') ? 'draft' : 'published';
            db_update('blog_posts', [
                'status'       => $newStatus,
                'published_at' => ($newStatus === 'published' ? date('Y-m-d H:i:s') : null),
                'updated_at'   => date('Y-m-d H:i:s')
            ], 'id = :id', ['id' => $postId]);
            @seo_sync_all();
            log_activity($adminUser['id'], 'toggle_blog_status', "Changed status of '{$post['title']}' to {$newStatus}");
            set_flash('success', "Article '{$post['title']}' status updated to {$newStatus}.");
        }
        header('Location: ' . url('admin/blog.php'));
        exit;
    }
}

$adminTitle = "Blog CMS & Article Editor";
require_once __DIR__ . '/header.php';

// Fetch categories
$categories = db_fetch_all("SELECT * FROM blog_categories ORDER BY name ASC");

// Form mode check
$subAction = $_GET['action'] ?? '';
$editId = intval($_GET['edit_id'] ?? 0);
$editPost = null;
$editFaqs = [];

if ($editId > 0) {
    $editPost = db_fetch_one("SELECT * FROM blog_posts WHERE id = :id", ['id' => $editId]);
    if ($editPost) {
        $editFaqs = db_fetch_all("SELECT * FROM blog_post_faqs WHERE post_id = :pid ORDER BY display_order ASC, id ASC", ['pid' => $editId]);
    }
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
    $sql .= " AND (p.title LIKE :s OR p.excerpt LIKE :s OR p.slug LIKE :s OR p.tags LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY p.created_at DESC";
$posts = db_fetch_all($sql, $params);
?>

<style>
/* Modern Article Composer Styles */
.composer-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 340px;
  gap: 22px;
  align-items: flex-start;
}
@media (max-width: 1024px) {
  .composer-layout {
    grid-template-columns: 1fr;
  }
}
.composer-card {
  background: linear-gradient(145deg, rgba(25,29,38,0.98), rgba(16,19,25,0.98));
  border: 1px solid rgba(255,255,255,0.09);
  border-radius: 12px;
  box-shadow: 0 10px 28px rgba(0,0,0,0.24);
  margin-bottom: 20px;
  overflow: hidden;
}
.composer-card-header {
  padding: 15px 20px;
  background: linear-gradient(90deg, rgba(212,175,55,0.08), rgba(255,255,255,0.015) 46%);
  border-bottom: 1px solid rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.composer-card-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #F5F1E8;
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
}
.composer-card-body {
  padding: 20px;
}
.blog-composer {
  max-width: 1480px;
}
.composer-main,
.composer-sidebar {
  min-width: 0;
}
.composer-sidebar {
  position: sticky;
  top: 18px;
}
.blog-composer .form-label {
  margin-bottom: 0.45rem;
  color: #E7E3DA;
  font-size: 0.78rem;
  font-weight: 750;
  letter-spacing: 0.015em;
}
.blog-composer .form-input,
.blog-composer .form-select {
  min-height: 42px;
  background: rgba(5,7,11,0.42);
  border-color: rgba(255,255,255,0.12);
  color: #F5F1E8;
  border-radius: 7px;
  transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}
.blog-composer textarea.form-input {
  min-height: 96px;
  resize: vertical;
}
.blog-composer .form-input:focus,
.blog-composer .form-select:focus {
  background: rgba(5,7,11,0.66);
  border-color: #D4AF37;
  box-shadow: 0 0 0 3px rgba(212,175,55,0.13);
  outline: none;
}
.blog-composer .composer-card:first-child .composer-card-body {
  padding-bottom: 22px;
}
.blog-composer .blog-editor-shell {
  background: #0C0E13;
}
.blog-composer .blog-editor-shell .tox-tinymce {
  min-height: 500px !important;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
}
.blog-composer .composer-sidebar .composer-card {
  box-shadow: 0 12px 30px rgba(0,0,0,0.32);
}
.blog-composer .composer-sidebar .composer-card-header {
  padding: 13px 16px;
}
.blog-composer .composer-sidebar .composer-card-body {
  padding: 16px;
}
.blog-composer #featuredImgPreviewBox {
  border-color: rgba(212,175,55,0.32) !important;
  background: linear-gradient(135deg, #17140D, #151922) !important;
}
.blog-composer .google-preview-box {
  border-left: 3px solid #D4AF37;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.035);
}
.blog-composer .char-counter {
  padding-top: 0.15rem;
}
.char-counter {
  font-size: 0.75rem;
  color: var(--color-text-muted, #94A3B8);
  margin-top: 4px;
  display: flex;
  justify-content: space-between;
}
.char-counter.is-warning {
  color: #F59E0B;
}
.char-counter.is-good {
  color: #10B981;
}
.google-preview-box {
  background: #202124;
  border: 1px solid #3c4043;
  border-radius: 8px;
  padding: 14px 16px;
  font-family: Roboto, Arial, sans-serif;
  margin-bottom: 16px;
}
.google-preview-url {
  font-size: 0.78rem;
  color: #bdc1c6;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.google-preview-title {
  font-size: 1.05rem;
  color: #8ab4f8;
  font-weight: 400;
  line-height: 1.35;
  margin-bottom: 4px;
  cursor: pointer;
  text-decoration: none;
  display: block;
}
.google-preview-title:hover {
  text-decoration: underline;
}
.google-preview-desc {
  font-size: 0.85rem;
  color: #bdc1c6;
  line-height: 1.45;
}
/* FAQ Builder Item */
.faq-builder-item {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid var(--color-border, #232834);
  border-radius: 8px;
  padding: 14px 16px;
  margin-bottom: 12px;
  position: relative;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.faq-builder-item:hover {
  border-color: rgba(212, 175, 55, 0.4);
}
.faq-builder-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
  gap: 10px;
}
.faq-handle {
  font-size: 0.825rem;
  font-weight: 700;
  color: var(--color-primary, #D4AF37);
  display: flex;
  align-items: center;
  gap: 6px;
}
.faq-controls {
  display: flex;
  align-items: center;
  gap: 4px;
}
.faq-btn {
  background: transparent;
  border: 1px solid var(--color-border, #232834);
  color: #CBD5E1;
  padding: 3px 8px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.75rem;
  line-height: 1.2;
}
.faq-btn:hover {
  background: rgba(255,255,255,0.06);
  color: #F5F1E8;
}
.faq-btn-del {
  color: #EF4444;
  border-color: rgba(239, 68, 68, 0.3);
}
.faq-btn-del:hover {
  background: rgba(239, 68, 68, 0.15);
  color: #FCA5A5;
}
/* TinyMCE owns every editor control. Do not apply broad admin form rules to .tox. */
.blog-editor-shell .tox-tinymce {
  border: 1px solid var(--color-border, #232834);
  border-radius: 8px;
  overflow: hidden;
}
.blog-editor-shell .tox .tox-statusbar { border-top-color: #2B334B; }
.blog-editor-shell .tox .tox-toolbar-overlord,
.blog-editor-shell .tox .tox-toolbar__primary { background: #171C2B; }
@media (max-width: 480px) {
  .composer-card-body { padding: 14px; }
  .composer-sidebar { position: static; }
  .composer-card-header { padding: 13px 14px; }
  .blog-editor-shell .tox .tox-toolbar__group { padding: 0 2px; }
}
/* Legacy fallback styles retained for the plain textarea only; no second toolbar is rendered. */
.rt-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  background: #171C2B;
  border: 1px solid var(--color-border, #232834);
  border-bottom: none;
  border-radius: 8px 8px 0 0;
  padding: 8px 10px;
  align-items: center;
}
.rt-btn {
  background: #1E2436;
  border: 1px solid #2B334B;
  color: #CBD5E1;
  padding: 5px 9px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.82rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 28px;
  height: 28px;
  transition: all 0.15s ease;
}
.rt-btn:hover {
  background: #283049;
  color: #D4AF37;
  border-color: #D4AF37;
}
.rt-btn.is-active {
  background: rgba(212, 175, 55, 0.18);
  border-color: #D4AF37;
  color: #FFD76A;
}
.rt-select {
  background: #1E2436;
  border: 1px solid #2B334B;
  color: #CBD5E1;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.82rem;
  height: 28px;
}
.rt-separator {
  width: 1px;
  height: 20px;
  background: #2B334B;
  margin: 0 4px;
}
.rt-editor-content {
  min-height: 380px;
  padding: 16px;
  background: #0B0E14;
  border: 1px solid var(--color-border, #232834);
  border-radius: 0 0 8px 8px;
  color: #F5F1E8;
  font-family: inherit;
  font-size: 0.98rem;
  line-height: 1.7;
  outline: none;
  overflow-y: auto;
}
.rt-editor-content:focus {
  border-color: var(--color-primary, #D4AF37);
  box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
}
.rt-editor-content h2 { font-size: 1.5rem; color: #F5F1E8; margin: 1.25rem 0 0.5rem; font-weight: 700; }
.rt-editor-content h3 { font-size: 1.25rem; color: #D4AF37; margin: 1rem 0 0.5rem; font-weight: 700; }
.rt-editor-content h4 { font-size: 1.1rem; color: #CBD5E1; margin: 0.85rem 0 0.4rem; font-weight: 600; }
.rt-editor-content blockquote { border-left: 3px solid #D4AF37; background: rgba(212,175,55,0.08); padding: 8px 14px; margin: 12px 0; font-style: italic; }
.rt-editor-content table { width: 100%; border-collapse: collapse; margin: 12px 0; }
.rt-editor-content th, .rt-editor-content td { border: 1px solid #2B334B; padding: 8px 12px; text-align: left; }
.rt-editor-content th { background: #171C2B; color: #D4AF37; font-weight: 700; }
.tag-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: rgba(212, 175, 55, 0.12);
  border: 1px solid rgba(212, 175, 55, 0.35);
  color: #F5F1E8;
  font-size: 0.78rem;
  padding: 3px 8px;
  border-radius: 12px;
  margin: 3px 3px 0 0;
}
.tag-pill-remove {
  cursor: pointer;
  color: #D4AF37;
  font-weight: bold;
}
</style>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Blog CMS &amp; Editorial Suite</h1>
        <p class="admin-page-subtitle">Publish high-ranking technical guides, buyer guides, and case studies with schema, rich typography &amp; FAQs.</p>
    </div>
    <div class="admin-topbar-actions">
        <?php if ($editPost || $subAction === 'new'): ?>
            <a href="<?= url('admin/blog.php') ?>" class="btn btn-outline btn-sm">
                &larr; Back to Articles
            </a>
            <?php if ($editPost): ?>
                <a href="<?= blog_url($editPost['slug']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="border-color:rgba(212,175,55,0.4); color:#D4AF37;">
                    View Live ↗
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?= url('admin/blog.php?action=new') ?>" class="btn btn-primary btn-sm">
                <span>+ Write New Article</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($editPost || $subAction === 'new'): ?>
    <!-- EDIT / CREATE ARTICLE WORKBENCH -->
    <form id="blogComposerForm" class="blog-composer" method="POST" action="<?= url('admin/blog.php') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_post">
        <input type="hidden" name="post_id" value="<?= $editPost ? (int)$editPost['id'] : 0 ?>">
        <input type="hidden" name="submit_action" id="submitActionInput" value="publish">

        <div class="composer-layout">
            <!-- MAIN EDITORIAL COLUMN (Left) -->
            <div class="composer-main">
                <!-- CARD 1: Article Essentials -->
                <div class="composer-card composer-essentials-card">
                    <div class="composer-card-header">
                        <h2 class="composer-card-title">
                            <span>📄</span> <?= $editPost ? 'Edit Article: ' . e($editPost['title']) : 'Article Information &amp; Essentials' ?>
                        </h2>
                        <?php if ($editPost && $editPost['status'] === 'published'): ?>
                            <span class="badge badge-success">● Published</span>
                        <?php else: ?>
                            <span class="badge badge-warning">○ Draft</span>
                        <?php endif; ?>
                    </div>
                    <div class="composer-card-body">
                        <!-- Title -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label required" style="font-weight: 700;">Article Title</label>
                            <input type="text" id="articleTitleInput" name="title" class="form-input" required 
                                   value="<?= e($editPost['title'] ?? '') ?>" 
                                   placeholder="e.g. Complete Guide to DTF Printing: Fabrics, Heat Press Settings &amp; Longevity"
                                   style="font-size: 1.05rem; font-weight: 600; padding: 10px 14px;">
                        </div>

                        <!-- Slug URL Row -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                                <span>SEO Friendly Slug (Canonical URL Path)</span>
                                <button type="button" id="toggleSlugEditBtn" class="btn btn-outline btn-xs" style="padding: 2px 8px;">Edit Slug</button>
                            </label>
                            <div style="display: flex; align-items: center; background: rgba(0,0,0,0.25); border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden;">
                                <span style="padding: 8px 12px; background: rgba(255,255,255,0.04); color: var(--color-text-muted); font-size: 0.85rem; border-right: 1px solid var(--color-border); white-space: nowrap;">
                                    /blog/
                                </span>
                                <input type="text" id="articleSlugInput" name="slug" class="form-input" 
                                       value="<?= e($editPost['slug'] ?? '') ?>" 
                                       placeholder="complete-guide-to-dtf-printing"
                                       readonly
                                       style="border: none; background: transparent; padding: 8px 12px; font-family: monospace; font-size: 0.9rem;">
                                <span style="padding: 8px 12px; background: rgba(255,255,255,0.04); color: var(--color-text-muted); font-size: 0.85rem; border-left: 1px solid var(--color-border); white-space: nowrap;">
                                    /
                                </span>
                            </div>
                            <small style="color: var(--color-text-muted); font-size: 0.75rem; margin-top: 4px; display: block;">
                                Automatically generated from title. Click "Edit Slug" to customize manually.
                            </small>
                        </div>

                        <!-- Category & Tags 2-Col -->
                        <div class="admin-form-2col" style="margin-bottom: 16px;">
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label required">Category</label>
                                <select name="category_id" class="form-select dynamic-other-select"
                                        data-enable-other="true"
                                        data-other-name="custom_category"
                                        data-other-label="Enter New Category Name"
                                        data-other-placeholder="e.g. Prepress Protocols, Sublimation vs DTF">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($editPost && $editPost['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= e($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="other">+ Create New Category</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Tags (comma-separated)</label>
                                <input type="text" id="articleTagsInput" name="tags" class="form-input" 
                                       value="<?= e($editPost['tags'] ?? '') ?>" 
                                       placeholder="e.g. DTF Printing, Apparel, Heat Press, Delhi Studio">
                                <div id="tagsPreviewContainer" style="display:flex; flex-wrap:wrap; margin-top:6px;"></div>
                            </div>
                        </div>

                        <!-- Short Excerpt / Summary -->
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label required">Short Excerpt / Lead Summary</label>
                            <textarea id="articleExcerptInput" name="excerpt" class="form-input" rows="3" required 
                                      placeholder="A concise, high-impact 2-3 sentence teaser shown on archive cards and search snippets..."><?= e($editPost['excerpt'] ?? '') ?></textarea>
                            <div class="char-counter">
                                <span>Shown on blog cards, search snippet and article header lead text</span>
                                <span id="excerptCharCount">0 / 180</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Rich Article Composer -->
                <div class="composer-card composer-editor-card">
                    <div class="composer-card-header">
                        <h2 class="composer-card-title">
                            <span>✍</span> Article Content Body
                        </h2>
                        <div style="font-size: 0.8rem; color: var(--color-text-muted);" id="contentWordCounter">
                            Words: 0 | Chars: 0
                        </div>
                    </div>
                    <div class="composer-card-body blog-editor-shell" style="padding: 16px;">
                        <!-- Standalone Rich Toolbar (Always visible & interactive) -->
                        <div class="rt-toolbar" id="rtToolbar" hidden aria-hidden="true">
                            <select class="rt-select" id="rtBlockType" onchange="applyBlockFormat(this.value)">
                                <option value="p">Paragraph</option>
                                <option value="h2">Heading 2 (H2)</option>
                                <option value="h3">Heading 3 (H3)</option>
                                <option value="h4">Heading 4 (H4)</option>
                                <option value="blockquote">Quote (Blockquote)</option>
                            </select>

                            <div class="rt-separator"></div>

                            <button type="button" class="rt-btn" onclick="execCmd('bold')" title="Bold (Ctrl+B)"><b>B</b></button>
                            <button type="button" class="rt-btn" onclick="execCmd('italic')" title="Italic (Ctrl+I)"><i>I</i></button>
                            <button type="button" class="rt-btn" onclick="execCmd('underline')" title="Underline (Ctrl+U)"><u>U</u></button>
                            <button type="button" class="rt-btn" onclick="execCmd('strikeThrough')" title="Strikethrough"><s>S</s></button>

                            <div class="rt-separator"></div>

                            <button type="button" class="rt-btn" onclick="execCmd('insertUnorderedList')" title="Bullet List">• List</button>
                            <button type="button" class="rt-btn" onclick="execCmd('insertOrderedList')" title="Numbered List">1. List</button>

                            <div class="rt-separator"></div>

                            <button type="button" class="rt-btn" onclick="insertLinkPrompt()" title="Insert Link">🔗 Link</button>
                            <button type="button" class="rt-btn" onclick="insertTablePrompt()" title="Insert Table">▦ Table</button>
                            <button type="button" class="rt-btn" onclick="execCmd('insertHorizontalRule')" title="Divider Line">— HR</button>

                            <div class="rt-separator"></div>

                            <button type="button" class="rt-btn" onclick="execCmd('justifyLeft')" title="Align Left">⇤</button>
                            <button type="button" class="rt-btn" onclick="execCmd('justifyCenter')" title="Align Center">≡</button>
                            <button type="button" class="rt-btn" onclick="execCmd('justifyRight')" title="Align Right">⇥</button>

                            <div class="rt-separator"></div>

                            <button type="button" class="rt-btn" id="toggleHtmlModeBtn" onclick="toggleHtmlSourceMode()" title="Toggle HTML Source Code View">&lt;/&gt; Code</button>
                        </div>

                        <!-- Editable Rich Container -->
                        <div id="rtEditorVisual" class="rt-editor-content" hidden aria-hidden="true" contenteditable="true"><?= $editPost ? $editPost['content'] : '<p>Write your article introduction here...</p>' ?></div>

                        <!-- Hidden / Underlying Textarea that syncs with POST -->
                        <textarea id="articleContentTextarea" name="content"><?= e($editPost['content'] ?? '<p>Write your article introduction here...</p>') ?></textarea>
                    </div>
                </div>

                <!-- CARD 3: Interactive FAQ Builder (For Accordion & FAQPage Schema) -->
                <div class="composer-card composer-faq-card">
                    <div class="composer-card-header">
                        <h2 class="composer-card-title">
                            <span>❓</span> Frequently Asked Questions (FAQ Builder)
                        </h2>
                        <button type="button" class="btn btn-primary btn-xs" onclick="addFaqRow()">
                            + Add Question
                        </button>
                    </div>
                    <div class="composer-card-body">
                        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 16px;">
                            Questions added here will render as interactive accordion cards below your article and will automatically generate a dedicated <strong>Schema.org FAQPage JSON-LD</strong> block for Google Rich Search Results.
                        </p>

                        <div id="faqBuilderContainer">
                            <?php if (!empty($editFaqs)): ?>
                                <?php foreach ($editFaqs as $fIdx => $faq): ?>
                                    <div class="faq-builder-item" data-idx="<?= $fIdx ?>">
                                        <div class="faq-builder-top">
                                            <div class="faq-handle">
                                                <span>☰</span> FAQ #<span class="faq-num"><?= $fIdx + 1 ?></span>
                                            </div>
                                            <div class="faq-controls">
                                                <button type="button" class="faq-btn" onclick="moveFaq(this, -1)" title="Move Up">▲</button>
                                                <button type="button" class="faq-btn" onclick="moveFaq(this, 1)" title="Move Down">▼</button>
                                                <button type="button" class="faq-btn faq-btn-del" onclick="deleteFaq(this)" title="Delete FAQ">✕ Remove</button>
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 10px;">
                                            <input type="text" name="faq_questions[]" class="form-input form-input-sm" 
                                                   value="<?= e($faq['question']) ?>" placeholder="Question: e.g. What is the wash durability of DTF transfers?" required>
                                        </div>
                                        <div>
                                            <textarea name="faq_answers[]" class="form-input form-input-sm" rows="3" 
                                                      placeholder="Answer: Provide a clear, detailed, factual answer..." required><?= e($faq['answer']) ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div id="faqEmptyPlaceholder" style="text-align: center; padding: 24px; border: 1px dashed var(--color-border); border-radius: 8px; color: var(--color-text-muted); font-size: 0.85rem;">
                                    No FAQs added yet. Click <strong>"+ Add Question"</strong> to create structured FAQs for this article.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Dedicated SEO & Social Metadata Configuration -->
                <div class="composer-card composer-seo-card">
                    <div class="composer-card-header">
                        <h2 class="composer-card-title">
                            <span>🔍</span> Search Engine Optimization (SEO) &amp; Social Graph
                        </h2>
                    </div>
                    <div class="composer-card-body">
                        <!-- Google SERP Snippet Preview -->
                        <div style="font-size: 0.8rem; font-weight: 700; color: #D4AF37; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Google Search Result Snippet Preview
                        </div>
                        <div class="google-preview-box">
                            <div class="google-preview-url" id="serpPreviewUrl">
                                https://orderprintex.com/blog/<?= e($editPost['slug'] ?? 'article-slug') ?>
                            </div>
                            <a class="google-preview-title" id="serpPreviewTitle">
                                <?= e(!empty($editPost['seo_title']) ? $editPost['seo_title'] : ($editPost['title'] ?? 'Article Title | OrderPrintex Blog')) ?>
                            </a>
                            <div class="google-preview-desc" id="serpPreviewDesc">
                                <?= e(!empty($editPost['seo_description']) ? $editPost['seo_description'] : ($editPost['excerpt'] ?? 'Article summary and search snippet description will appear here...')) ?>
                            </div>
                        </div>

                        <!-- SEO Title -->
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label class="form-label">SEO Meta Title (recommended 50-60 characters)</label>
                            <input type="text" id="seoTitleInput" name="seo_title" class="form-input form-input-sm" 
                                   value="<?= e($editPost['seo_title'] ?? '') ?>" 
                                   placeholder="Leave empty to use article title + OrderPrintex">
                            <div class="char-counter">
                                <span>Optimal: 50–60 characters</span>
                                <span id="seoTitleCount">0 / 60</span>
                            </div>
                        </div>

                        <!-- SEO Meta Description -->
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label class="form-label">SEO Meta Description (recommended 150-160 characters)</label>
                            <textarea id="seoDescInput" name="seo_description" class="form-input form-input-sm" rows="3" 
                                      placeholder="Concise summary answering search query with high-intent keywords..."><?= e($editPost['seo_description'] ?? '') ?></textarea>
                            <div class="char-counter">
                                <span>Optimal: 150–160 characters</span>
                                <span id="seoDescCount">0 / 160</span>
                            </div>
                        </div>

                        <div class="admin-form-2col" style="margin-bottom: 14px;">
                            <!-- Meta Keywords -->
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Target Keywords (comma-separated)</label>
                                <input type="text" name="seo_keywords" class="form-input form-input-sm" 
                                       value="<?= e($editPost['seo_keywords'] ?? '') ?>" 
                                       placeholder="dtf printing delhi, garment transfer film, gang sheet pricing">
                            </div>

                            <!-- Canonical URL Override -->
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Canonical URL Override (optional)</label>
                                <input type="url" name="canonical_url" class="form-input form-input-sm" 
                                       value="<?= e($editPost['canonical_url'] ?? '') ?>" 
                                       placeholder="https://orderprintex.com/blog/custom-slug">
                            </div>
                        </div>

                        <!-- Open Graph Social Strip -->
                        <div style="border-top: 1px solid var(--color-border); padding-top: 14px; margin-top: 14px;">
                            <div style="font-size: 0.85rem; font-weight: 700; color: #F5F1E8; margin-bottom: 10px;">
                                Social Sharing (Open Graph Meta)
                            </div>
                            <div class="admin-form-2col">
                                <div class="form-group" style="margin:0;">
                                    <label class="form-label">OG Title (optional override)</label>
                                    <input type="text" name="og_title" class="form-input form-input-sm" 
                                           value="<?= e($editPost['og_title'] ?? '') ?>" placeholder="Defaults to SEO Title">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label class="form-label">OG Image URL (optional override)</label>
                                    <input type="text" name="og_image" class="form-input form-input-sm" 
                                           value="<?= e($editPost['og_image'] ?? '') ?>" placeholder="Defaults to Featured Image">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 10px; margin-bottom: 0;">
                                <label class="form-label">OG Description (optional override)</label>
                                <textarea name="og_description" class="form-input form-input-sm" rows="2" 
                                          placeholder="Defaults to Meta Description"><?= e($editPost['og_description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR PUBLISHING WORKBENCH (Right) -->
            <aside class="composer-sidebar" aria-label="Publishing and featured media settings">
                <!-- CARD: Publishing Controls -->
                <div class="composer-card">
                    <div class="composer-card-header">
                        <h3 class="composer-card-title">
                            <span>🚀</span> Publishing Status
                        </h3>
                    </div>
                    <div class="composer-card-body" style="display: flex; flex-direction: column; gap: 14px;">
                        <div>
                            <label class="form-label">Status</label>
                            <select id="statusSelect" name="status" class="form-select">
                                <option value="published" <?= (!isset($editPost) || $editPost['status'] === 'published') ? 'selected' : '' ?>>
                                    ● Published (Live Storefront)
                                </option>
                                <option value="draft" <?= ($editPost && $editPost['status'] === 'draft') ? 'selected' : '' ?>>
                                    ○ Draft (Hidden from Public)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Publish Date &amp; Time</label>
                            <input type="datetime-local" name="published_at" class="form-input form-input-sm"
                                   value="<?= !empty($editPost['published_at']) ? date('Y-m-d\TH:i', strtotime($editPost['published_at'])) : date('Y-m-d\TH:i') ?>">
                        </div>

                        <div>
                            <label class="form-label">Author Name</label>
                            <input type="text" name="author" class="form-input form-input-sm" 
                                   value="<?= e($editPost['author'] ?? 'OrderPrintex Editorial Team') ?>">
                        </div>

                        <div>
                            <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                                <span>Estimated Read Time</span>
                                <button type="button" class="btn btn-outline btn-xs" onclick="calcReadTime()" style="padding:1px 6px;">Recalc</button>
                            </label>
                            <input type="text" id="readTimeInput" name="read_time" class="form-input form-input-sm" 
                                   value="<?= e($editPost['read_time'] ?? '5 min read') ?>" placeholder="e.g. 6 min read">
                        </div>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: #FBBF24; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); padding: 8px 12px; border-radius: 6px;">
                            <input type="checkbox" name="is_sample" value="1" <?= (!isset($editPost) || $editPost['is_sample']) ? 'checked' : '' ?>>
                            Mark as Demo / Sample Article
                        </label>

                        <!-- Action Buttons -->
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 6px;">
                            <button type="submit" onclick="document.getElementById('submitActionInput').value='publish';" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center;">
                                <?= $editPost ? '💾 Save &amp; Update Article' : '✨ Publish Article Now' ?>
                            </button>

                            <button type="submit" onclick="document.getElementById('submitActionInput').value='draft'; document.getElementById('statusSelect').value='draft';" class="btn btn-outline btn-block btn-sm" style="justify-content: center;">
                                Save as Draft
                            </button>

                            <?php if ($editPost): ?>
                                <a href="<?= blog_url($editPost['slug']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-block btn-sm" style="border-color: rgba(212,175,55,0.4); color: #D4AF37; justify-content: center;">
                                    Preview Article on Site ↗
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- CARD: Featured Image & Media -->
                <div class="composer-card">
                    <div class="composer-card-header">
                        <h3 class="composer-card-title">
                            <span>🖼</span> Featured Media &amp; Alt
                        </h3>
                    </div>
                    <div class="composer-card-body" style="display: flex; flex-direction: column; gap: 14px;">
                        <div id="featuredImgPreviewBox" style="width: 100%; height: 160px; border-radius: 6px; overflow: hidden; border: 1px solid var(--color-border); background: #171C2B; display: flex; align-items: center; justify-content: center;">
                            <?php if (!empty($editPost['featured_image'])): ?>
                                <img id="featuredImgEl" src="<?= asset_url($editPost['featured_image']) ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span id="featuredImgPlaceholder" style="color: var(--color-text-muted); font-size: 0.85rem;">No Image Selected</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($editPost['featured_image'])): ?>
                            <input type="hidden" name="featured_image_path" value="<?= e($editPost['featured_image']) ?>">
                        <?php endif; ?>

                        <div>
                            <label class="form-label">Upload New Image</label>
                            <input type="file" id="featuredImgFileInput" name="featured_image_file" class="form-input form-input-sm" accept=".jpg,.jpeg,.png,.webp" onchange="previewFeaturedImage(this)">
                            <small style="color: var(--color-text-muted); font-size: 0.75rem; margin-top: 4px; display: block;">
                                Recommended: 1200x675px landscape image. Automatically converts to WebP.
                            </small>
                        </div>

                        <div>
                            <label class="form-label required">Featured Image Alt Text</label>
                            <input type="text" name="featured_image_alt" class="form-input form-input-sm" 
                                   value="<?= e($editPost['featured_image_alt'] ?? '') ?>" 
                                   placeholder="Descriptive text for SEO &amp; screen readers..." required>
                            <small style="color: var(--color-text-muted); font-size: 0.75rem; margin-top: 2px; display: block;">
                                Important for technical SEO image indexing and accessibility.
                            </small>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>

    <!-- Modal for Rich-Text Insert Link -->
    <div id="rtLinkModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
        <div style="background:#171C2B; border:1px solid var(--color-border); border-radius:10px; width:90%; max-width:420px; padding:20px; box-shadow:0 8px 32px rgba(0,0,0,0.8);">
            <h3 style="margin-top:0; color:#F5F1E8; font-size:1.1rem; font-weight:700;">Insert Link</h3>
            <div class="form-group">
                <label class="form-label">URL (https://...)</label>
                <input type="url" id="modalLinkUrl" class="form-input" placeholder="https://example.com/guide">
            </div>
            <div class="form-group">
                <label class="form-label">Link Text</label>
                <input type="text" id="modalLinkText" class="form-input" placeholder="e.g. DTF Printing Services">
            </div>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#CBD5E1; margin-bottom:16px; cursor:pointer;">
                <input type="checkbox" id="modalLinkNewTab" checked>
                Open in new tab (target="_blank" rel="noopener")
            </label>
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeLinkModal()">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmInsertLink()">Insert Link</button>
            </div>
        </div>
    </div>

    <!-- Modal for Rich-Text Insert Table -->
    <div id="rtTableModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
        <div style="background:#171C2B; border:1px solid var(--color-border); border-radius:10px; width:90%; max-width:380px; padding:20px; box-shadow:0 8px 32px rgba(0,0,0,0.8);">
            <h3 style="margin-top:0; color:#F5F1E8; font-size:1.1rem; font-weight:700;">Insert Table</h3>
            <div class="admin-form-2col" style="margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Rows</label>
                    <input type="number" id="modalTableRows" class="form-input" min="1" max="20" value="3">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Columns</label>
                    <input type="number" id="modalTableCols" class="form-input" min="1" max="10" value="3">
                </div>
            </div>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#CBD5E1; margin-bottom:16px; cursor:pointer;">
                <input type="checkbox" id="modalTableHeader" checked>
                Include header row (&lt;thead&gt;)
            </label>
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeTableModal()">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmInsertTable()">Insert Table</button>
            </div>
        </div>
    </div>

    <!-- CDN TinyMCE Loader (if online) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
    let isHtmlSourceMode = false;
    let hasUnsavedChanges = false;

    // Track unsaved changes
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('blogComposerForm');
        if (form) {
            form.querySelectorAll('input, textarea, select').forEach(el => {
                el.addEventListener('change', () => { hasUnsavedChanges = true; });
                el.addEventListener('input', () => { hasUnsavedChanges = true; });
            });
            form.addEventListener('submit', function() {
                hasUnsavedChanges = false;
                syncEditorContent();
            });
        }
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes in this article. Are you sure you want to leave?';
            }
        });

        // Initialize Character Counters & Tag Pills
        initCharCounters();
        renderTagPills();
        updateWordCount();

        // Title to Slug automatic generation (only if new or slug is empty)
        const titleInput = document.getElementById('articleTitleInput');
        const slugInput = document.getElementById('articleSlugInput');
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (slugInput.hasAttribute('readonly') && (<?= $editPost ? 'false' : 'true' ?> || slugInput.value.trim() === '')) {
                    const slug = titleInput.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                    slugInput.value = slug;
                    updateSerpPreview();
                }
                updateSerpPreview();
            });
        }

        // Toggle slug editing
        const toggleSlugBtn = document.getElementById('toggleSlugEditBtn');
        if (toggleSlugBtn && slugInput) {
            toggleSlugBtn.addEventListener('click', function() {
                if (slugInput.hasAttribute('readonly')) {
                    slugInput.removeAttribute('readonly');
                    slugInput.focus();
                    toggleSlugBtn.textContent = 'Lock Slug';
                    toggleSlugBtn.classList.add('btn-primary');
                    toggleSlugBtn.classList.remove('btn-outline');
                } else {
                    slugInput.setAttribute('readonly', 'true');
                    toggleSlugBtn.textContent = 'Edit Slug';
                    toggleSlugBtn.classList.remove('btn-primary');
                    toggleSlugBtn.classList.add('btn-outline');
                }
            });
        }

        // Live editor content sync
        const visualEditor = document.getElementById('rtEditorVisual');
        if (visualEditor) {
            visualEditor.addEventListener('input', function() {
                syncEditorContent();
                updateWordCount();
            });
        }

        // One non-inline TinyMCE instance owns the editor and its toolbar.
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#articleContentTextarea',
                skin: 'oxide-dark',
                content_css: 'dark',
                height: 480,
                menubar: false,
                resize: true,
                promotion: false,
                plugins: 'lists link image table code fullscreen wordcount advlist autoresize',
                toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link unlink image | table tableinsertrowbefore tableinsertrowafter tabledeletecol | blockquote hr | code fullscreen',
                block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
                table_toolbar: 'tableprops tableinsertrowbefore tableinsertrowafter tabledeleterow tableinsertcolbefore tableinsertcolafter tabledeletecol tabledelete',
                image_title: true,
                automatic_uploads: false,
                image_description: true,
                setup: function(editor) {
                    editor.on('init change input undo redo setcontent', function(event) {
                        updateWordCount();
                        if (event.type !== 'init') hasUnsavedChanges = true;
                    });
                }
            });
        }
    });

    // Formatting Toolbar Functions
    function execCmd(command, value = null) {
        document.getElementById('rtEditorVisual').focus();
        document.execCommand(command, false, value);
        syncEditorContent();
        updateWordCount();
    }

    function applyBlockFormat(tag) {
        if (!tag) return;
        document.getElementById('rtEditorVisual').focus();
        if (tag === 'blockquote') {
            document.execCommand('formatBlock', false, '<blockquote>');
        } else {
            document.execCommand('formatBlock', false, '<' + tag + '>');
        }
        syncEditorContent();
    }

    function syncEditorContent() {
        const hidden = document.getElementById('articleContentTextarea');
        const editor = (typeof tinymce !== 'undefined') ? tinymce.get('articleContentTextarea') : null;
        if (editor && hidden) {
            hidden.value = editor.getContent();
        }
    }

    function updateWordCount() {
        const editor = (typeof tinymce !== 'undefined') ? tinymce.get('articleContentTextarea') : null;
        const visual = document.getElementById('articleContentTextarea');
        const counter = document.getElementById('contentWordCounter');
        if (!visual || !counter) return;

        const text = editor ? editor.getContent({format: 'text'}) : (visual.value || '');
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const chars = text.length;
        counter.textContent = `Words: ${words} | Chars: ${chars}`;
    }

    function calcReadTime() {
        const editor = (typeof tinymce !== 'undefined') ? tinymce.get('articleContentTextarea') : null;
        const visual = document.getElementById('articleContentTextarea');
        const text = editor ? editor.getContent({format: 'text'}) : (visual ? visual.value : '');
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const mins = Math.max(1, Math.ceil(words / 200));
        const readTimeInput = document.getElementById('readTimeInput');
        if (readTimeInput) {
            readTimeInput.value = `${mins} min read`;
        }
    }

    function toggleHtmlSourceMode() {
        const visual = document.getElementById('rtEditorVisual');
        const btn = document.getElementById('toggleHtmlModeBtn');
        if (!visual || !btn) return;

        if (!isHtmlSourceMode) {
            visual.innerText = visual.innerHTML;
            btn.classList.add('is-active');
            isHtmlSourceMode = true;
        } else {
            visual.innerHTML = visual.innerText;
            btn.classList.remove('is-active');
            isHtmlSourceMode = false;
        }
        syncEditorContent();
    }

    // Link Modal
    function insertLinkPrompt() {
        const selectedText = window.getSelection().toString();
        document.getElementById('modalLinkText').value = selectedText || '';
        document.getElementById('modalLinkUrl').value = '';
        document.getElementById('rtLinkModal').style.display = 'flex';
    }
    function closeLinkModal() {
        document.getElementById('rtLinkModal').style.display = 'none';
    }
    function confirmInsertLink() {
        const url = document.getElementById('modalLinkUrl').value.trim();
        const text = document.getElementById('modalLinkText').value.trim() || url;
        const newTab = document.getElementById('modalLinkNewTab').checked;
        if (url) {
            const targetAttr = newTab ? ' target="_blank" rel="noopener"' : '';
            const html = `<a href="${url}"${targetAttr}>${text}</a>`;
            document.getElementById('rtEditorVisual').focus();
            document.execCommand('insertHTML', false, html);
            syncEditorContent();
        }
        closeLinkModal();
    }

    // Table Modal
    function insertTablePrompt() {
        document.getElementById('rtTableModal').style.display = 'flex';
    }
    function closeTableModal() {
        document.getElementById('rtTableModal').style.display = 'none';
    }
    function confirmInsertTable() {
        const rows = parseInt(document.getElementById('modalTableRows').value, 10) || 3;
        const cols = parseInt(document.getElementById('modalTableCols').value, 10) || 3;
        const hasHeader = document.getElementById('modalTableHeader').checked;

        let tableHtml = '<table class="table" style="width:100%; border-collapse:collapse; margin:1rem 0;">';
        if (hasHeader) {
            tableHtml += '<thead><tr>';
            for (let c = 1; c <= cols; c++) {
                tableHtml += `<th style="border:1px solid #2B334B; padding:8px 12px; background:#171C2B; color:#D4AF37;">Header ${c}</th>`;
            }
            tableHtml += '</tr></thead>';
        }
        tableHtml += '<tbody>';
        for (let r = 1; r <= rows; r++) {
            tableHtml += '<tr>';
            for (let c = 1; c <= cols; c++) {
                tableHtml += `<td style="border:1px solid #2B334B; padding:8px 12px; color:#CBD5E1;">Data ${r},${c}</td>`;
            }
            tableHtml += '</tr>';
        }
        tableHtml += '</tbody></table><p></p>';

        document.getElementById('rtEditorVisual').focus();
        document.execCommand('insertHTML', false, tableHtml);
        syncEditorContent();
        closeTableModal();
    }

    // Interactive FAQ Builder Logic
    function addFaqRow() {
        const placeholder = document.getElementById('faqEmptyPlaceholder');
        if (placeholder) placeholder.style.display = 'none';

        const container = document.getElementById('faqBuilderContainer');
        const currentItems = container.querySelectorAll('.faq-builder-item');
        const nextNum = currentItems.length + 1;

        const row = document.createElement('div');
        row.className = 'faq-builder-item';
        row.innerHTML = `
            <div class="faq-builder-top">
                <div class="faq-handle">
                    <span>☰</span> FAQ #<span class="faq-num">${nextNum}</span>
                </div>
                <div class="faq-controls">
                    <button type="button" class="faq-btn" onclick="moveFaq(this, -1)" title="Move Up">▲</button>
                    <button type="button" class="faq-btn" onclick="moveFaq(this, 1)" title="Move Down">▼</button>
                    <button type="button" class="faq-btn faq-btn-del" onclick="deleteFaq(this)" title="Delete FAQ">✕ Remove</button>
                </div>
            </div>
            <div style="margin-bottom: 10px;">
                <input type="text" name="faq_questions[]" class="form-input form-input-sm" 
                       placeholder="Question: e.g. What is the turnaround time for corporate merchandise?" required>
            </div>
            <div>
                <textarea name="faq_answers[]" class="form-input form-input-sm" rows="3" 
                          placeholder="Answer: Provide a clear, detailed, factual answer..." required></textarea>
            </div>
        `;
        container.appendChild(row);
        renumberFaqs();
        hasUnsavedChanges = true;
    }

    function deleteFaq(btn) {
        const item = btn.closest('.faq-builder-item');
        if (item) {
            item.remove();
            renumberFaqs();
            hasUnsavedChanges = true;
        }
        const container = document.getElementById('faqBuilderContainer');
        if (container.querySelectorAll('.faq-builder-item').length === 0) {
            const placeholder = document.getElementById('faqEmptyPlaceholder');
            if (placeholder) placeholder.style.display = 'block';
        }
    }

    function moveFaq(btn, direction) {
        const item = btn.closest('.faq-builder-item');
        if (!item) return;
        if (direction === -1 && item.previousElementSibling && item.previousElementSibling.classList.contains('faq-builder-item')) {
            item.parentNode.insertBefore(item, item.previousElementSibling);
            renumberFaqs();
            hasUnsavedChanges = true;
        } else if (direction === 1 && item.nextElementSibling && item.nextElementSibling.classList.contains('faq-builder-item')) {
            item.parentNode.insertBefore(item.nextElementSibling, item);
            renumberFaqs();
            hasUnsavedChanges = true;
        }
    }

    function renumberFaqs() {
        const container = document.getElementById('faqBuilderContainer');
        const items = container.querySelectorAll('.faq-builder-item');
        items.forEach((it, idx) => {
            const numEl = it.querySelector('.faq-num');
            if (numEl) numEl.textContent = (idx + 1);
        });
    }

    // Character Counters & Live SERP Preview
    function initCharCounters() {
        const excerptInput = document.getElementById('articleExcerptInput');
        const excerptCount = document.getElementById('excerptCharCount');
        if (excerptInput && excerptCount) {
            const update = () => {
                const len = excerptInput.value.length;
                excerptCount.textContent = `${len} / 180`;
                excerptCount.className = 'char-counter ' + (len > 180 ? 'is-warning' : 'is-good');
                updateSerpPreview();
            };
            excerptInput.addEventListener('input', update);
            update();
        }

        const seoTitleInput = document.getElementById('seoTitleInput');
        const seoTitleCount = document.getElementById('seoTitleCount');
        if (seoTitleInput && seoTitleCount) {
            const update = () => {
                const len = seoTitleInput.value.length;
                seoTitleCount.textContent = `${len} / 60`;
                seoTitleCount.className = 'char-counter ' + (len > 60 ? 'is-warning' : (len >= 45 ? 'is-good' : ''));
                updateSerpPreview();
            };
            seoTitleInput.addEventListener('input', update);
            update();
        }

        const seoDescInput = document.getElementById('seoDescInput');
        const seoDescCount = document.getElementById('seoDescCount');
        if (seoDescInput && seoDescCount) {
            const update = () => {
                const len = seoDescInput.value.length;
                seoDescCount.textContent = `${len} / 160`;
                seoDescCount.className = 'char-counter ' + (len > 160 ? 'is-warning' : (len >= 120 ? 'is-good' : ''));
                updateSerpPreview();
            };
            seoDescInput.addEventListener('input', update);
            update();
        }
    }

    function updateSerpPreview() {
        const titleInput = document.getElementById('articleTitleInput');
        const seoTitleInput = document.getElementById('seoTitleInput');
        const slugInput = document.getElementById('articleSlugInput');
        const excerptInput = document.getElementById('articleExcerptInput');
        const seoDescInput = document.getElementById('seoDescInput');

        const serpTitle = document.getElementById('serpPreviewTitle');
        const serpUrl = document.getElementById('serpPreviewUrl');
        const serpDesc = document.getElementById('serpPreviewDesc');

        if (serpTitle) {
            const val = (seoTitleInput && seoTitleInput.value.trim()) 
                ? seoTitleInput.value.trim() 
                : ((titleInput && titleInput.value.trim()) ? titleInput.value.trim() + ' | OrderPrintex Blog' : 'Article Title | OrderPrintex Blog');
            serpTitle.textContent = val;
        }

        if (serpUrl && slugInput) {
            const slug = slugInput.value.trim() || 'article-slug';
            serpUrl.textContent = `https://orderprintex.com/blog/${slug}`;
        }

        if (serpDesc) {
            const val = (seoDescInput && seoDescInput.value.trim()) 
                ? seoDescInput.value.trim() 
                : ((excerptInput && excerptInput.value.trim()) ? excerptInput.value.trim() : 'Search snippet description will appear here...');
            serpDesc.textContent = val;
        }
    }

    // Tag Pills
    function renderTagPills() {
        const tagsInput = document.getElementById('articleTagsInput');
        const container = document.getElementById('tagsPreviewContainer');
        if (!tagsInput || !container) return;

        const updatePills = () => {
            container.innerHTML = '';
            const raw = tagsInput.value;
            const tags = raw.split(',').map(t => t.trim()).filter(t => t.length > 0);
            tags.forEach(t => {
                const pill = document.createElement('span');
                pill.className = 'tag-pill';
                pill.innerHTML = `#${t} <span class="tag-pill-remove" onclick="removeTag('${t.replace(/'/g, "\\'")}')">&times;</span>`;
                container.appendChild(pill);
            });
        };

        tagsInput.addEventListener('input', updatePills);
        updatePills();
    }

    function removeTag(tagToRemove) {
        const tagsInput = document.getElementById('articleTagsInput');
        if (!tagsInput) return;
        const current = tagsInput.value.split(',').map(t => t.trim()).filter(t => t.length > 0);
        const filtered = current.filter(t => t.toLowerCase() !== tagToRemove.toLowerCase());
        tagsInput.value = filtered.join(', ');
        renderTagPills();
        hasUnsavedChanges = true;
    }

    // Featured Image Preview
    function previewFeaturedImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('featuredImgPreviewBox');
                box.innerHTML = `<img id="featuredImgEl" src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
            };
            reader.readAsDataURL(input.files[0]);
            hasUnsavedChanges = true;
        }
    }
    </script>

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
            <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search title or tag..." class="form-input form-input-sm" style="width: 220px;">
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
                        <th style="width: 150px;">Actions</th>
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
                                        <img src="<?= asset_url($p['featured_image'] ?: 'assets/images/branding_stationery.jpg') ?>" 
                                             alt="<?= e(!empty($p['featured_image_alt']) ? $p['featured_image_alt'] : $p['title']) ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #F5F1E8; display: block; font-size: 0.9rem;">
                                        <?= e($p['title']) ?>
                                    </strong>
                                    <span style="font-size: 0.75rem; color: #D4AF37;">
                                        /blog/<?= e($p['slug']) ?>/
                                    </span>
                                    <?php if (!empty($p['tags'])): ?>
                                        <div style="margin-top: 3px;">
                                            <?php foreach (array_slice(array_filter(array_map('trim', explode(',', $p['tags']))), 0, 3) as $t): ?>
                                                <span style="font-size: 0.68rem; color: #94A3B8; background: rgba(255,255,255,0.05); padding: 1px 5px; border-radius: 3px; margin-right: 3px;">
                                                    #<?= e($t) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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
                                        <span class="badge" style="background:rgba(245, 158, 11, 0.15); color:#FBBF24; font-size: 0.7rem; border:1px solid rgba(245, 158, 11, 0.3);">Sample</span>
                                    <?php else: ?>
                                        <span class="badge badge-info" style="font-size: 0.7rem;">Original</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($p['read_time']) ?></td>
                                <td style="font-size: 0.8rem; color: var(--color-text-muted);">
                                    <?= !empty($p['published_at']) ? date('d M Y', strtotime($p['published_at'])) : date('d M Y', strtotime($p['created_at'])) ?>
                                </td>
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
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-outline btn-xs" title="<?= $p['status'] === 'published' ? 'Unpublish to draft' : 'Publish live' ?>">
                                                <?= $p['status'] === 'published' ? 'Draft' : 'Publish' ?>
                                            </button>
                                        </form>
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
