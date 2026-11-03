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

// Fetch Categories
$categories = db_fetch_all("SELECT * FROM blog_categories ORDER BY id ASC");

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

$pageTitle = "Commercial Printing Guides & Insights | OrderPrintex Delhi Blog";
$pageDesc  = "Explore expert printing tutorials, DTF transfer advice, paper stock guides, and apparel branding strategies from OrderPrintex's Delhi prepress engineers.";
$canonical = blog_url();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Blog Hero Header -->
<section class="page-intro-header">
  <div class="container">
    <div class="page-intro-box">
      <div class="page-intro-eyebrow">
        <span class="page-intro-dot"></span>
        <span>Industry Knowledge &amp; Prepress Guides</span>
      </div>
      <h1 class="page-intro-title">The OrderPrintex Knowledge Hub</h1>
      <div class="page-intro-divider"></div>
      <p class="page-intro-desc">
        In-depth technical guides, material comparisons, and production insights written by commercial printing specialists and prepress engineers.
      </p>

      <!-- Search Form -->
      <div style="width:100%; max-width:540px; margin:2.25rem auto 0;">
        <form action="<?= blog_url() ?>" method="GET" style="display:flex; gap:0.5rem; background:var(--bg-surface); padding:6px; border-radius:999px; box-shadow:0 8px 30px rgba(0,0,0,0.25);">
          <input type="text" name="q" value="<?= e($searchQuery) ?>" placeholder="Search printing guides, DTF, paper stocks..." style="flex:1; border:none; outline:none; padding:0.6rem 1.25rem; font-size:0.95rem; border-radius:999px; color:var(--navy);">
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:999px; padding:0.6rem 1.4rem;">
            <span>Search</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Category Filter Navigation -->
<section style="background:var(--ivory); border-bottom:1px solid var(--border-light); padding:1rem 0;">
  <div class="container">
    <div style="display:flex; gap:0.75rem; align-items:center; overflow-x:auto; padding-bottom:0.25rem; scrollbar-width:none;">
      <a href="<?= blog_url() ?>" class="btn btn-sm <?= empty($categorySlug) ? 'btn-navy' : 'btn-outline' ?>" style="white-space:nowrap; border-radius:999px;">
        All Articles (<?= count($posts) ?>)
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= blog_category_url($cat['slug']) ?>" class="btn btn-sm <?= ($categorySlug === $cat['slug']) ? 'btn-navy' : 'btn-outline' ?>" style="white-space:nowrap; border-radius:999px;">
          <?= e($cat['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

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
        <article class="reveal slide-up" style="background:var(--bg-surface); border-radius:var(--radius-lg); border:1px solid var(--border-color); box-shadow: 0 4px 14px rgba(15, 25, 45, 0.09), 0 16px 36px rgba(15, 25, 45, 0.12); overflow:hidden; display:grid; grid-template-columns:1.2fr 1fr; gap:0; margin-bottom:3.5rem; transition:transform 0.3s ease, box-shadow 0.3s ease;">
          <div style="position:relative; min-height:360px; overflow:hidden; background:#F8F5F0;">
            <img src="<?= url(e($featuredPost['featured_image'])) ?>" alt="<?= e($featuredPost['title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
            <span style="position:absolute; top:1.25rem; left:1.25rem; background:var(--navy); color:var(--gold); font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; padding:0.35rem 0.85rem; border-radius:999px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
              Featured Editorial
            </span>
          </div>

          <div style="padding:clamp(1.75rem, 3vw, 2.75rem); display:flex; flex-direction:column; justify-content:center;">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.85rem; font-size:0.825rem; color:var(--text-muted);">
              <span style="background:rgba(29,78,216,0.1); color:var(--royal); font-weight:700; padding:0.25rem 0.65rem; border-radius:4px;">
                <?= e($featuredPost['category_name']) ?>
              </span>
              <span>&bull;</span>
              <span><?= e($featuredPost['read_time']) ?></span>
              <span>&bull;</span>
              <span><?= date('M d, Y', strtotime($featuredPost['created_at'])) ?></span>
            </div>

            <h2 style="font-size:clamp(1.6rem, 2.4vw, 2.1rem); color:var(--navy); line-height:1.3; margin-bottom:1rem;">
              <a href="<?= blog_url($featuredPost['slug']) ?>" style="color:inherit; text-decoration:none;">
                <?= e($featuredPost['title']) ?>
              </a>
            </h2>

            <p style="color:var(--text-muted); line-height:1.7; margin-bottom:1.75rem; font-size:0.98rem;">
              <?= e($featuredPost['excerpt']) ?>
            </p>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:auto; padding-top:1.25rem; border-top:1px solid var(--border-light);">
              <div style="font-size:0.85rem; font-weight:600; color:var(--navy);">
                By <?= e($featuredPost['author']) ?>
              </div>
              <a href="<?= blog_url($featuredPost['slug']) ?>" class="btn btn-navy btn-sm">
                <span>Read Full Guide</span> &rarr;
              </a>
            </div>
          </div>
        </article>
      <?php endif; ?>

      <!-- Articles Grid -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2.5rem;">
        <?php 
        $displayList = (empty($categorySlug) && empty($searchQuery) && $featuredPost) ? $gridPosts : $posts;
        foreach ($displayList as $idx => $post): 
        ?>
          <article class="product-card reveal slide-up" style="display:flex; flex-direction:column; height:100%; border-radius:var(--radius-md); overflow:hidden; background:var(--bg-surface); border:1px solid var(--border-color); box-shadow: 0 4px 12px rgba(15, 25, 45, 0.09), 0 12px 28px rgba(15, 25, 45, 0.10); transition:transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="height:210px; overflow:hidden; position:relative; background:#F8F5F0;">
              <img src="<?= url(e($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.4s ease;" loading="lazy">
              <span style="position:absolute; top:1rem; left:1rem; background:rgba(16,27,48,0.9); color:#FFFFFF; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:999px;">
                <?= e($post['category_name']) ?>
              </span>
            </div>

            <div style="padding:1.5rem; display:flex; flex-direction:column; flex:1;">
              <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.75rem;">
                <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                <span>&bull;</span>
                <span><?= e($post['read_time']) ?></span>
              </div>

              <h3 style="font-size:1.25rem; color:var(--navy); line-height:1.4; margin-bottom:0.75rem; font-weight:700;">
                <a href="<?= blog_url($post['slug']) ?>" style="color:inherit; text-decoration:none;">
                  <?= e($post['title']) ?>
                </a>
              </h3>

              <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.6; margin-bottom:1.5rem; flex:1;">
                <?= e(mb_strimwidth($post['excerpt'], 0, 140, '...')) ?>
              </p>

              <div style="display:flex; align-items:center; justify-content:space-between; padding-top:1rem; border-top:1px solid var(--border-light);">
                <span style="font-size:0.8rem; font-weight:600; color:var(--navy);"><?= e($post['author']) ?></span>
                <a href="<?= blog_url($post['slug']) ?>" style="font-size:0.85rem; font-weight:700; color:var(--royal); text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem;">
                  Read Article &rarr;
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
    <span class="section-label" style="justify-content:center;">Ready to Print?</span>
    <h2 style="font-size:2.4rem; margin-bottom:1rem; color:var(--navy);">Put Commercial Printing Knowledge into Action</h2>
    <p style="color:var(--text-muted); margin-bottom:2rem; line-height:1.7;">
      Upload your artwork files today. Our prepress technicians inspect line thickness, color trapping, and alpha transparency with zero obligation.
    </p>
    <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
      <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
        <span>Upload Design for Free Proof</span> &rarr;
      </a>
      <a href="<?= url('products/') ?>" class="btn btn-navy btn-lg">
        <span>Explore Commercial Catalog</span>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
