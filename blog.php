<?php
/**
 * OrderPrintex - Commercial Printing Blog & Industry Guides
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$categorySlug = trim($_GET['category'] ?? '');
$searchQuery  = trim($_GET['q'] ?? '');

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (!empty($categorySlug) && (strpos($requestUri, 'blog.php') !== false || strpos($requestUri, '?category=') !== false)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . blog_category_url($categorySlug));
    exit;
}
if (strpos($requestUri, 'blog.php') !== false) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . blog_url());
    exit;
}

// Fetch Categories with published post counts
$categories = db_fetch_all("
    SELECT c.*, COUNT(p.id) as post_count 
    FROM blog_categories c 
    LEFT JOIN blog_posts p ON p.category_id = c.id AND p.status = 'published' 
    GROUP BY c.id 
    ORDER BY c.id ASC
");
$totalPublishedCount = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM blog_posts WHERE status = 'published'")['cnt'] ?? 0);

// Validate Category Slug if present
$activeCategory = null;
if (!empty($categorySlug)) {
    foreach ($categories as $c) {
        if ($c['slug'] === $categorySlug) {
            $activeCategory = $c;
            break;
        }
    }
    if (!$activeCategory) {
        http_response_code(404);
        $pageTitle = "Blog Category Not Found - OrderPrintex";
        require __DIR__ . '/404.php';
        exit;
    }
}

// Fetch Posts
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM blog_posts p 
        LEFT JOIN blog_categories c ON p.category_id = c.id 
        WHERE p.status = 'published'";
$params = [];

if (!empty($categorySlug)) {
    $sql .= " AND c.slug = :cat";
    $params['cat'] = $categorySlug;
}

if (!empty($searchQuery)) {
    $sql .= " AND (p.title LIKE :q OR p.excerpt LIKE :q OR p.content LIKE :q)";
    $params['q'] = '%' . $searchQuery . '%';
}

$sql .= " ORDER BY p.id ASC";
$posts = db_fetch_all($sql, $params);

// Featured Post (first post or matching)
$featuredPost = !empty($posts) ? $posts[0] : null;
$gridPosts = !empty($posts) ? array_slice($posts, 1) : [];

if ($activeCategory) {
    $pageTitle = $activeCategory['name'] . " — Commercial Printing Guides | OrderPrintex Delhi";
    $pageDesc  = !empty($activeCategory['description']) 
        ? $activeCategory['description'] 
        : ("Explore expert " . $activeCategory['name'] . " guides, DTF printing tutorials, and apparel advice from OrderPrintex's Delhi prepress engineers.");
    $canonical = blog_category_url($activeCategory['slug']);
} else {
    $pageTitle = "Commercial Printing Guides & Insights | OrderPrintex Delhi Blog";
    $pageDesc  = "Explore expert printing tutorials, DTF transfer advice, paper stock guides, and apparel branding strategies from OrderPrintex's Delhi prepress engineers.";
    $canonical = blog_url();
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Blog Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>PRINTING GUIDES &amp; TIPS</span>
      </div>
      <h1 class="page-intro-title">Printing Guides &amp; Tips</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        Simple, practical guides on DTF, UV DTF, t-shirt and corporate printing from our Delhi team. Learn what works before you order.
      </p>

      <!-- Search Form -->
      <div style="width:100%; max-width:540px; margin:2.25rem auto 0;">
        <form action="<?= blog_url() ?>" method="GET" class="blog-search-form" style="display:flex; gap:0.5rem; background:var(--bg-surface); padding:6px; border-radius:999px; border:1px solid var(--border-color); box-shadow:0 8px 30px rgba(0,0,0,0.45); max-width:100%;">
          <input type="text" name="q" value="<?= e($searchQuery) ?>" placeholder="Search guides, e.g. DTF, UV decals, t-shirts" style="flex:1; min-width:0; border:none; outline:none; padding:0.6rem 1rem; font-size:0.95rem; border-radius:999px; color:var(--text-primary); background:transparent;">
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:999px; padding:0.6rem 1.4rem; flex-shrink:0;">
            <span>Search</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Category Filter Navigation -->
<nav class="blog-category-nav-section" aria-label="Blog Category Filter">
  <div class="container">
    <div class="blog-category-track">
      <a href="<?= blog_url() ?>" class="blog-cat-chip <?= empty($categorySlug) ? 'is-active' : '' ?>">
        <span>All Guides (<?= $totalPublishedCount ?>)</span>
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= blog_category_url($cat['slug']) ?>" class="blog-cat-chip <?= ($categorySlug === $cat['slug']) ? 'is-active' : '' ?>">
          <span><?= e($cat['name']) ?> (<?= (int)($cat['post_count'] ?? 0) ?>)</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</nav>

<!-- Main Blog Section -->
<section class="section section-white" style="padding-top:3.5rem; padding-bottom:5rem;">
  <div class="container">
    
    <?php if (empty($posts)): ?>
      <div style="text-align:center; padding:4rem 1rem; background:var(--ivory); border-radius:var(--radius-lg); border:1px dashed var(--border-light);">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🔍</div>
        <h3 style="color:var(--navy); margin-bottom:0.5rem;">No Articles Found</h3>
        <p style="color:var(--text-muted); margin-bottom:1.5rem;">We couldn't find any printing guides matching your search criteria.</p>
        <a href="<?= blog_url() ?>" class="btn btn-primary">View All Printing Guides</a>
      </div>
    <?php else: ?>

      <!-- Hero Featured Post (Only on main blog page when no category/search active) -->
      <?php if (empty($categorySlug) && empty($searchQuery) && $featuredPost): ?>
        <article class="reveal slide-up blog-featured-article">
          <div class="blog-featured-media">
            <img src="<?= url(e($featuredPost['featured_image'])) ?>" alt="<?= e($featuredPost['title']) ?>" loading="lazy">
            <span class="blog-featured-badge">
              Featured Editorial
            </span>
          </div>

          <div class="blog-featured-content">
            <div class="blog-meta-row">
              <span class="blog-meta-pill">
                <?= e($featuredPost['category_name']) ?>
              </span>
              <span>&bull;</span>
              <span><?= e($featuredPost['read_time']) ?></span>
              <span>&bull;</span>
              <span><?= date('M d, Y', strtotime($featuredPost['created_at'])) ?></span>
            </div>

            <h2 class="blog-featured-title">
              <a href="<?= blog_url($featuredPost['slug']) ?>">
                <?= e($featuredPost['title']) ?>
              </a>
            </h2>

            <p class="blog-featured-desc">
              <?= e($featuredPost['excerpt']) ?>
            </p>

            <div class="blog-featured-footer">
              <div class="blog-author-tag">
                By <?= e($featuredPost['author']) ?>
              </div>
              <a href="<?= blog_url($featuredPost['slug']) ?>" class="btn btn-primary btn-sm">
                <span>Read Full Guide</span> &rarr;
              </a>
            </div>
          </div>
        </article>
      <?php endif; ?>

      <!-- Articles Grid -->
      <div class="blog-posts-grid">
        <?php 
        $displayList = (empty($categorySlug) && empty($searchQuery) && $featuredPost) ? $gridPosts : $posts;
        foreach ($displayList as $idx => $post): 
        ?>
          <article class="blog-card reveal slide-up">
            <div class="blog-card-media">
              <img src="<?= url(e($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
              <span class="blog-card-badge">
                <?= e($post['category_name']) ?>
              </span>
            </div>

            <div class="blog-card-body">
              <div class="blog-card-meta">
                <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                <span>&bull;</span>
                <span><?= e($post['read_time']) ?></span>
              </div>

              <h3 class="blog-card-title">
                <a href="<?= blog_url($post['slug']) ?>">
                  <?= e($post['title']) ?>
                </a>
              </h3>

              <p class="blog-card-desc">
                <?= e(mb_strimwidth($post['excerpt'], 0, 140, '...')) ?>
              </p>

              <div class="blog-card-footer">
                <span class="blog-card-author">By <?= e($post['author']) ?></span>
                <a href="<?= blog_url($post['slug']) ?>" class="blog-card-link">
                  Read Full Article &rarr;
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<!-- Prepress Quote Call to Action -->
<section class="section section-ivory text-center">
  <div class="container container-narrow reveal scale-in">
    <span class="section-label" style="justify-content:center;">READY TO PRINT?</span>
    <h2 style="font-size:2.4rem; margin-bottom:1rem; color:var(--navy);">Ready to Start Your Print Order?</h2>
    <p style="color:var(--text-muted); margin-bottom:2rem; line-height:1.7;">
      Send us your design and our team will check the lines, colors and background for free. There’s no obligation to order.
    </p>
    <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
        <span>Upload Your Design</span> &rarr;
      </a>
      <a href="<?= url('products/') ?>" class="btn btn-navy btn-lg">
        <span>See Our Products</span>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
