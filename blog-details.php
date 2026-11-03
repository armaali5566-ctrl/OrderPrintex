<?php
/**
 * OrderPrintex - Single Blog Post & Technical Guide
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, 'blog-details.php') !== false) {
    if (!empty($_GET['slug'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . blog_url($_GET['slug']));
        exit;
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . blog_url());
    exit;
}

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Fetch article with category
$post = db_fetch_one("SELECT p.*, c.name as category_name, c.slug as category_slug 
                      FROM blog_posts p 
                      LEFT JOIN blog_categories c ON p.category_id = c.id 
                      WHERE p.slug = :slug AND p.status = 'published'", [
    'slug' => $slug
]);

if (!$post) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Increment view count
db_query("UPDATE blog_posts SET views = views + 1 WHERE id = :id", ['id' => $post['id']]);

// Fetch 3 Related Articles
$relatedPosts = db_fetch_all("SELECT p.*, c.name as category_name 
                              FROM blog_posts p 
                              LEFT JOIN blog_categories c ON p.category_id = c.id 
                              WHERE p.id != :id AND p.status = 'published' 
                              ORDER BY p.id ASC LIMIT 3", [
    'id' => $post['id']
]);

$pageTitle = e($post['seo_title'] ?: $post['title'] . ' | OrderPrintex Blog');
$pageDesc  = e($post['seo_description'] ?: $post['excerpt']);
$canonical = blog_url($post['slug']);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Schema.org BlogPosting Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": <?= json_encode($post['title']) ?>,
  "description": <?= json_encode($post['excerpt']) ?>,
  "image": [
    <?= json_encode(url($post['featured_image'])) ?>
  ],
  "datePublished": <?= json_encode(date('c', strtotime($post['created_at']))) ?>,
  "dateModified": <?= json_encode(date('c', strtotime($post['updated_at'] ?? $post['created_at']))) ?>,
  "author": {
    "@type": "Person",
    "name": <?= json_encode($post['author']) ?>
  },
  "publisher": {
    "@type": "Organization",
    "name": "OrderPrintex",
    "logo": {
      "@type": "ImageObject",
      "url": <?= json_encode(url('assets/images/logo.png')) ?>
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": <?= json_encode($canonical) ?>
  }
}
</script>

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-bar" style="background-color:var(--ivory); border-bottom:1px solid var(--border-light); padding:0.9rem 0;" aria-label="Breadcrumb">
  <div class="container" style="font-size:0.85rem; color:var(--slate); display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <a href="<?= url('') ?>" style="color:var(--navy); font-weight:600;">Home</a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <a href="<?= blog_url() ?>" style="color:var(--navy); font-weight:600;">Blog</a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <a href="<?= blog_category_url($post['category_slug'] ?? '') ?>" style="color:var(--navy); font-weight:600;">
      <?= e($post['category_name']) ?>
    </a>
    <span style="color:var(--slate-subtle);">&rsaquo;</span>
    <span style="color:var(--royal); font-weight:700;" aria-current="page"><?= e(mb_strimwidth($post['title'], 0, 45, '...')) ?></span>
  </div>
</nav>

<!-- Article Header Section -->
<header class="section section-white" style="padding-top:3.5rem; padding-bottom:2rem;">
  <div class="container container-readable">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
      <span style="background:rgba(29,78,216,0.1); color:var(--royal); font-weight:700; font-size:0.85rem; padding:0.35rem 0.85rem; border-radius:999px;">
        <?= e($post['category_name']) ?>
      </span>
      <span style="font-size:0.85rem; color:var(--text-muted);">&bull;</span>
      <span style="font-size:0.85rem; color:var(--text-muted);"><?= e($post['read_time']) ?></span>
      <span style="font-size:0.85rem; color:var(--text-muted);">&bull;</span>
      <span style="font-size:0.85rem; color:var(--text-muted);"><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
    </div>

    <h1 style="font-size:clamp(2rem, 3.5vw, 2.85rem); line-height:1.25; color:var(--navy); margin-bottom:1.25rem; font-weight:800;">
      <?= e($post['title']) ?>
    </h1>

    <p style="font-size:1.15rem; color:var(--text-muted); line-height:1.7; margin-bottom:2rem; font-weight:400;">
      <?= e($post['excerpt']) ?>
    </p>

    <div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 0; border-top:1px solid var(--border-light); border-bottom:1px solid var(--border-light); flex-wrap:wrap; gap:1rem;">
      <div style="display:flex; align-items:center; gap:0.75rem;">
        <div style="width:44px; height:44px; border-radius:50%; background:var(--navy); color:var(--gold); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem;">
          OP
        </div>
        <div>
          <div style="font-weight:700; color:var(--navy); font-size:0.95rem;"><?= e($post['author']) ?></div>
          <div style="font-size:0.8rem; color:var(--text-muted);">OrderPrintex Prepress Division</div>
        </div>
      </div>

      <!-- Social Sharing Links -->
      <div style="display:flex; align-items:center; gap:0.6rem;">
        <span style="font-size:0.825rem; font-weight:600; color:var(--text-muted);">Share:</span>
        <a href="https://api.whatsapp.com/send?text=<?= urlencode($post['title'] . ' ' . $canonical) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="padding:0.35rem 0.65rem;" title="Share on WhatsApp">
          WhatsApp
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonical) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="padding:0.35rem 0.65rem;" title="Share on LinkedIn">
          LinkedIn
        </a>
        <button type="button" onclick="navigator.clipboard.writeText('<?= $canonical ?>'); alert('Link copied to clipboard!');" class="btn btn-sm btn-outline" style="padding:0.35rem 0.65rem;" title="Copy Article URL">
          Copy Link
        </button>
      </div>
    </div>
  </div>
</header>

<!-- Featured Image Hero Card -->
<div class="container container-readable" style="margin-bottom:3rem;">
  <div style="border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--border-color); box-shadow: 0 4px 14px rgba(15, 25, 45, 0.09), 0 16px 36px rgba(15, 25, 45, 0.12); max-height:480px; background:#F8F5F0;">
    <img src="<?= url(e($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
  </div>
</div>

<!-- Article Body Content -->
<article class="section section-white" style="padding-top:0; padding-bottom:4rem;">
  <div class="container container-readable" style="line-height:1.85; font-size:1.05rem; color:var(--text-main);">
    <div class="article-content" style="color:var(--navy);">
      <?= $post['content'] ?>
    </div>

    <!-- Editorial Author Card -->
    <div style="margin-top:4rem; padding:2rem; background:var(--bg-surface); border-radius:var(--radius-md); border:1px solid var(--border-color); border-left:4px solid var(--gold); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.08), 0 10px 24px rgba(15, 25, 45, 0.09); display:flex; gap:1.5rem; align-items:flex-start;">
      <div style="width:56px; height:56px; border-radius:50%; background:var(--navy); color:var(--gold); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.3rem; flex-shrink:0;">
        OP
      </div>
      <div>
        <h4 style="margin-bottom:0.4rem; color:var(--navy); font-size:1.1rem;"><?= e($post['author']) ?></h4>
        <p style="font-size:0.92rem; color:var(--text-muted); margin-bottom:0.75rem; line-height:1.6;">
          Our technical prepress team at OrderPrintex works daily with direct-to-film equipment, UV flatbed printers, and textile engineering to ensure every print file achieves optimal color fidelity and physical durability.
        </p>
        <a href="<?= url('get-quote/') ?>" style="font-weight:700; color:var(--royal); font-size:0.9rem; text-decoration:none;">
          Request a free artwork audit &rarr;
        </a>
      </div>
    </div>
  </div>
</article>

<!-- Related Articles Section -->
<?php if (!empty($relatedPosts)): ?>
<section class="section section-ivory" style="padding-top:4rem; padding-bottom:4.5rem;">
  <div class="container">
    <div class="section-header text-center reveal slide-up">
      <span class="section-label" style="justify-content:center;">Recommended Reading</span>
      <h2 style="font-size:2.2rem; color:var(--navy);">Related Printing &amp; Production Guides</h2>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:2rem; margin-top:2.5rem;">
      <?php foreach ($relatedPosts as $rel): ?>
        <article class="product-card" style="background:var(--bg-surface); border-radius:var(--radius-md); overflow:hidden; border:1px solid var(--border-color); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.09), 0 12px 28px rgba(15, 25, 45, 0.10); display:flex; flex-direction:column;">
          <div style="height:190px; overflow:hidden;">
            <img src="<?= url(e($rel['featured_image'])) ?>" alt="<?= e($rel['title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;" loading="lazy">
          </div>
          <div style="padding:1.5rem; display:flex; flex-direction:column; flex:1;">
            <span style="font-size:0.75rem; font-weight:700; color:var(--royal); text-transform:uppercase; margin-bottom:0.5rem;">
              <?= e($rel['category_name']) ?>
            </span>
            <h3 style="font-size:1.15rem; color:var(--navy); margin-bottom:0.75rem; line-height:1.4;">
              <a href="<?= blog_url($rel['slug']) ?>" style="color:inherit; text-decoration:none;">
                <?= e($rel['title']) ?>
              </a>
            </h3>
            <p style="font-size:0.88rem; color:var(--text-muted); line-height:1.6; margin-bottom:1.25rem; flex:1;">
              <?= e(mb_strimwidth($rel['excerpt'], 0, 110, '...')) ?>
            </p>
            <a href="<?= blog_url($rel['slug']) ?>" style="font-weight:700; font-size:0.85rem; color:var(--royal); text-decoration:none;">
              Read Guide &rarr;
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
