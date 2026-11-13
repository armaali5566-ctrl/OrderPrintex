<?php
/**
 * OrderPrintex - Single Blog Post & Technical Guide
 * Modern 2-Column Editorial Blog Layout with Sticky Table of Contents
 * Inspired by modern high-converting editorial layouts (Buzzspire Media)
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
$relatedPosts = db_fetch_all("SELECT p.*, c.name as category_name, c.slug as category_slug 
                              FROM blog_posts p 
                              LEFT JOIN blog_categories c ON p.category_id = c.id 
                              WHERE p.id != :id AND p.status = 'published' 
                              ORDER BY p.id ASC LIMIT 3", [
    'id' => $post['id']
]);

$pageTitle = $post['seo_title'] ?: $post['title'] . ' | OrderPrintex Blog';
$pageDesc  = $post['seo_description'] ?: $post['excerpt'];
$canonical = blog_url($post['slug']);
$ogImage   = !empty($post['featured_image']) ? url($post['featured_image']) : url('assets/images/logo.png');
$ogType    = 'article';

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

<style>
/* ==========================================================================
   BUZZSPIRE-INSPIRED MODERN EDITORIAL 2-COLUMN LAYOUT
   ========================================================================== */
html {
  scroll-behavior: smooth;
}

html, body {
  overflow-x: clip !important;
}

.site-wrapper,
section.blog-reading-section,
.blog-reading-section,
.blog-reading-section .container,
.blog-layout-grid {
  overflow: visible !important;
}

.blog-editorial-hero {
  position: relative;
  overflow: hidden;
  padding-top: clamp(5.75rem, 8vw, 7.5rem);
  padding-bottom: clamp(3rem, 4.5vw, 4rem);
  background: radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.14) 0%, transparent 52%),
    radial-gradient(circle at 20% 80%, rgba(143, 107, 30, 0.10) 0%, transparent 52%),
    var(--bg-primary, #0B0E14);
  border-bottom: 1px solid var(--border-color, #1E2330);
}

.blog-category-pill {
  background: rgba(212, 175, 55, 0.12);
  color: #FFD76A;
  border: 1px solid rgba(212, 175, 55, 0.32);
  font-weight: 700;
  font-size: 0.8rem;
  padding: 0.35rem 0.95rem;
  border-radius: 999px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  display: inline-flex;
  align-items: center;
}

.blog-hero-title {
  font-size: clamp(2rem, 3.8vw, 2.85rem);
  line-height: 1.24;
  color: #F5F1E8;
  margin-top: 0.75rem;
  margin-bottom: 1.25rem;
  font-weight: 800;
  letter-spacing: -0.01em;
  max-width: 980px;
}

.blog-hero-divider {
  width: 64px;
  height: 3px;
  background: var(--primary-gold, #D4AF37);
  margin-bottom: 1.25rem;
  border-radius: 2px;
}

.blog-author-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #181818;
  border: 1.5px solid #D4AF37;
  color: #FFD76A;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 1rem;
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
}

.btn-outline-share {
  border: 1px solid rgba(212, 175, 55, 0.35);
  color: #F5F1E8;
  background: #12151C;
  padding: 0.4rem 0.8rem;
  font-size: 0.82rem;
  border-radius: 6px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.btn-outline-share:hover {
  border-color: #D4AF37;
  color: #FFD76A;
  background: #1A1F2B;
}

/* 2-Column Desktop Grid (Article Content Left, Sticky TOC Right) */
.blog-layout-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: 2.5rem;
  align-items: start;
  position: relative;
  overflow: visible !important;
}

@media (max-width: 991px) {
  .blog-layout-grid {
    grid-template-columns: 1fr;
    gap: 2.25rem;
  }
}

/* Sticky TOC Sidebar (Right Column) */
.blog-sidebar-col {
  align-self: start;
  position: -webkit-sticky;
  position: sticky;
  top: 145px;
  max-height: calc(100vh - 165px);
  overflow-y: auto;
  z-index: 10;
}

.blog-sidebar-col::-webkit-scrollbar {
  width: 4px;
}

.blog-sidebar-col::-webkit-scrollbar-thumb {
  background: rgba(212, 175, 55, 0.35);
  border-radius: 4px;
}

@media (max-width: 991px) {
  .blog-sidebar-col {
    position: static;
    max-height: none;
    overflow-y: visible;
  }
}

.blog-toc-card {
  background: var(--bg-surface, #12151C);
  border: 1px solid var(--border-color, #232834);
  border-radius: 12px;
  padding: 1.35rem;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
}

.blog-toc-header {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  font-size: 0.88rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--primary-gold, #D4AF37);
  padding-bottom: 0.75rem;
  border-bottom: 1px solid rgba(212, 175, 55, 0.2);
  margin-bottom: 0.75rem;
}

.blog-toc-progress-track {
  width: 100%;
  height: 3px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 2px;
  margin-bottom: 0.95rem;
  overflow: hidden;
}

.blog-toc-progress-bar {
  height: 100%;
  width: 0%;
  background: var(--primary-gold, #D4AF37);
  transition: width 0.15s ease-out;
}

.blog-toc-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.blog-toc-item {
  margin: 0;
  padding: 0;
}

.blog-toc-link {
  display: block;
  font-size: 0.865rem;
  line-height: 1.45;
  color: var(--text-muted, #94A3B8);
  padding: 0.45rem 0.65rem;
  border-radius: 6px;
  border-left: 2px solid transparent;
  text-decoration: none;
  transition: all 0.2s ease;
}

.blog-toc-link:hover {
  color: #F5F1E8;
  background: rgba(255, 255, 255, 0.04);
}

.blog-toc-link.is-active {
  color: var(--primary-gold, #D4AF37);
  font-weight: 700;
  border-left: 2px solid var(--primary-gold, #D4AF37);
  background: rgba(212, 175, 55, 0.08);
  padding-left: 0.85rem;
}

/* Article Body Typography */
.blog-main-content {
  min-width: 0;
  overflow: visible !important;
}

.article-body-content {
  font-size: 1.0625rem;
  line-height: 1.8;
  color: var(--text-primary, #F5F1E8);
}

.article-body-content h2 {
  font-size: clamp(1.55rem, 2.4vw, 1.95rem);
  color: #F5F1E8;
  margin-top: 2.75rem;
  margin-bottom: 1.15rem;
  font-weight: 800;
  line-height: 1.3;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(212, 175, 55, 0.2);
  position: relative;
  scroll-margin-top: 145px;
}

.article-body-content h2::after {
  content: "";
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 48px;
  height: 2px;
  background: var(--primary-gold, #D4AF37);
}

.article-body-content h3 {
  font-size: clamp(1.2rem, 1.8vw, 1.4rem);
  color: var(--primary-gold, #D4AF37);
  margin-top: 2rem;
  margin-bottom: 0.85rem;
  font-weight: 700;
  line-height: 1.4;
  scroll-margin-top: 145px;
}

.article-body-content p {
  margin-bottom: 1.35rem;
  color: #CBD5E1;
  line-height: 1.8;
}

.article-body-content ul {
  list-style: none;
  padding-left: 0;
  margin: 1.25rem 0 1.75rem;
}

.article-body-content ul li {
  position: relative;
  padding-left: 1.65rem;
  margin-bottom: 0.65rem;
  color: #CBD5E1;
  line-height: 1.7;
}

.article-body-content ul li::before {
  content: "•";
  color: var(--primary-gold, #D4AF37);
  font-size: 1.4rem;
  line-height: 1;
  position: absolute;
  left: 0.35rem;
  top: 0;
  font-weight: bold;
}

.article-body-content ol {
  padding-left: 1.35rem;
  margin: 1.25rem 0 1.75rem;
  color: #CBD5E1;
}

.article-body-content ol li {
  margin-bottom: 0.65rem;
  line-height: 1.7;
}

.article-body-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 2rem 0;
  background: var(--bg-surface, #12151C);
  border: 1px solid var(--border-color, #232834);
  border-radius: 8px;
  overflow: hidden;
}

.article-body-content th {
  background: var(--bg-surface-elevated, #1A1F2B);
  padding: 1rem 1.25rem;
  color: var(--primary-gold, #D4AF37);
  font-weight: 800;
  text-align: left;
  border-bottom: 2px solid var(--border-color, #232834);
}

.article-body-content td {
  padding: 0.85rem 1.25rem;
  border-bottom: 1px solid var(--border-color, #232834);
  color: #CBD5E1;
}

.article-body-content blockquote {
  background: rgba(212, 175, 55, 0.05);
  border-left: 4px solid var(--primary-gold, #D4AF37);
  padding: 1.25rem 1.5rem;
  margin: 2rem 0;
  border-radius: 6px;
  font-style: italic;
  color: #F5F1E8;
}

.article-body-content a {
  color: var(--primary-gold, #D4AF37);
  text-decoration: underline;
  text-underline-offset: 3px;
}

.article-body-content a:hover {
  color: #F5E5C9;
}

/* FAQ Accordion Styling */
.faq-accordion {
  margin: 1.75rem 0 2.5rem;
}

.faq-item {
  background: var(--bg-surface, #12151C);
  border: 1px solid var(--border-color, #232834);
  border-radius: 8px;
  margin-bottom: 0.85rem;
  overflow: hidden;
  transition: border-color 0.25s ease, box-shadow 0.25s ease;
}

.faq-item:hover {
  border-color: rgba(212, 175, 55, 0.4);
}

.faq-item.active {
  border-color: var(--primary-gold, #D4AF37);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
}

.faq-question {
  padding: 1.15rem 1.35rem;
  font-weight: 700;
  font-size: 1.05rem;
  color: var(--text-primary, #F5F1E8);
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  user-select: none;
  transition: color 0.2s ease, background-color 0.2s ease;
}

.faq-question:hover {
  color: var(--primary-gold, #D4AF37);
}

.faq-icon {
  color: var(--primary-gold, #D4AF37);
  font-size: 1.35rem;
  font-weight: 700;
  line-height: 1;
  flex-shrink: 0;
  transition: transform 0.25s ease;
}

.faq-answer {
  max-height: 0 !important;
  overflow: hidden !important;
  opacity: 0 !important;
  padding: 0 1.35rem !important;
  transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease, padding 0.3s ease !important;
  color: var(--text-secondary, #CBD5E1);
  font-size: 0.98rem;
  line-height: 1.75;
}

.faq-item.active .faq-answer {
  max-height: 800px !important;
  opacity: 1 !important;
  padding: 0.5rem 1.35rem 1.35rem !important;
  border-top: 1px dashed rgba(212, 175, 55, 0.2);
}

/* Author Box */
.blog-author-box {
  margin-top: 3.5rem;
  padding: 1.75rem 2rem;
  background: var(--bg-surface, #12151C);
  border-radius: 12px;
  border: 1px solid var(--border-color, #232834);
  border-left: 4px solid var(--primary-gold, #D4AF37);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}

.blog-author-avatar-lg {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background: #181818;
  border: 1.5px solid #D4AF37;
  color: #FFD76A;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 1.25rem;
  flex-shrink: 0;
  box-shadow: 0 0 12px rgba(212, 175, 55, 0.25);
}

/* Bottom CTA Card */
.blog-cta-card {
  margin-top: 3rem;
  padding: 2.75rem 2rem;
  background: linear-gradient(135deg, #161A22 0%, #0D0F14 100%);
  border: 1.5px solid rgba(212, 175, 55, 0.35);
  border-radius: 14px;
  text-align: center;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
}
</style>

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb-bar" style="background-color:#0A0C10; border-bottom:1px solid #1E2330; padding:0.85rem 0;" aria-label="Breadcrumb">
  <div class="container" style="max-width:1200px; margin:0 auto; font-size:0.85rem; color:#A0AEC0; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <a href="<?= url('') ?>" style="color:#A0AEC0; font-weight:600; text-decoration:none;">Home</a>
    <span style="color:#64748B;">/</span>
    <a href="<?= blog_url() ?>" style="color:#A0AEC0; font-weight:600; text-decoration:none;">Blog</a>
    <?php if (!empty($post['category_slug'])): ?>
      <span style="color:#64748B;">/</span>
      <a href="<?= blog_category_url($post['category_slug']) ?>" style="color:#D4AF37; font-weight:600; text-decoration:none;">
        <?= e($post['category_name']) ?>
      </a>
    <?php endif; ?>
  </div>
</nav>

<!-- Clean Dark / Gold Editorial Hero Header -->
<header class="blog-editorial-hero">
  <div class="container" style="max-width:1200px; margin:0 auto;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.15rem; flex-wrap:wrap;">
      <span class="blog-category-pill">
        <?= e($post['category_name'] ?? 'Printing Guide') ?>
      </span>
      <span style="color:rgba(212,175,55,0.4);">&bull;</span>
      <span style="font-size:0.875rem; color:#CBD5E1; font-weight:600; display:inline-flex; align-items:center; gap:5px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?= e($post['read_time'] ?? '5 min read') ?>
      </span>
      <span style="color:rgba(212,175,55,0.4);">&bull;</span>
      <span style="font-size:0.875rem; color:#94A3B8;">
        <?= date('F j, Y', strtotime($post['created_at'])) ?>
      </span>
    </div>

    <h1 class="blog-hero-title">
      <?= e($post['title']) ?>
    </h1>

    <div class="blog-hero-divider"></div>

    <?php if (!empty($post['excerpt'])): ?>
      <p style="font-size:1.12rem; color:#CBD5E1; line-height:1.75; margin-bottom:1.75rem; max-width:920px;">
        <?= e($post['excerpt']) ?>
      </p>
    <?php endif; ?>

    <!-- Author & Social Share Strip -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.25rem; padding-top:1.25rem; border-top:1px solid rgba(212,175,55,0.18);">
      <div style="display:flex; align-items:center; gap:0.85rem;">
        <div class="blog-author-avatar">
          OP
        </div>
        <div>
          <div style="font-weight:700; color:#F5F1E8; font-size:0.95rem;">
            <?= e($post['author'] ?? 'By Printex') ?>
          </div>
          <div style="font-size:0.8rem; color:#94A3B8;">
            OrderPrintex Technical Prepress Studio
          </div>
        </div>
      </div>

      <!-- Social / Share Pills -->
      <div style="display:flex; align-items:center; gap:0.6rem;">
        <span style="font-size:0.825rem; font-weight:600; color:#94A3B8;">Share:</span>
        <a href="https://api.whatsapp.com/send?text=<?= urlencode($post['title'] . ' ' . $canonical) ?>" target="_blank" rel="noopener" class="btn-outline-share" title="Share on WhatsApp">
          WhatsApp
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonical) ?>" target="_blank" rel="noopener" class="btn-outline-share" title="Share on LinkedIn">
          LinkedIn
        </a>
        <button type="button" onclick="navigator.clipboard.writeText('<?= $canonical ?>'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Link', 2000);" class="btn-outline-share" title="Copy Link">
          Copy Link
        </button>
      </div>
    </div>
  </div>
</header>

<!-- Main Reading Container (2-Column Buzzspire-Inspired Grid) -->
<section class="section section-white blog-reading-section" style="padding-top:2.75rem; padding-bottom:5rem; background:var(--bg-primary, #0B0E14); overflow:visible !important;">
  <div class="container" style="max-width:1200px; margin:0 auto; overflow:visible !important;">

    <div class="blog-layout-grid">
      <!-- Left Column: Article Content -->
      <main class="blog-main-content">
        <!-- Featured Blog Image Frame -->
        <?php if (!empty($post['featured_image'])): ?>
          <div class="blog-featured-image-container" style="width: 100%; max-height: 440px; border-radius: 14px; overflow: hidden; margin-bottom: 2rem; border: 1px solid rgba(185, 154, 97, 0.25); box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12); background: #12151C;">
            <img src="<?= url(e($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" style="width: 100%; height: 100%; max-height: 440px; object-fit: cover; object-position: center; display: block;">
          </div>
        <?php endif; ?>

        <div class="article-body-content" id="articleBody">
          <?= $post['content'] ?>
        </div>

        <!-- Author Box -->
        <div class="blog-author-box">
          <div class="blog-author-avatar-lg">
            OP
          </div>
          <div>
            <h4 style="margin:0 0 0.35rem; color:#F5F1E8; font-size:1.1rem; font-weight:700;">
              <?= e($post['author'] ?? 'By Printex') ?>
            </h4>
            <p style="font-size:0.92rem; color:#94A3B8; margin:0 0 0.75rem; line-height:1.6;">
              Technical prepress specialists at OrderPrintex managing industrial direct-to-film, UV decal technology, and premium custom garment production in Delhi.
            </p>
            <a href="<?= url('get-quote/') ?>" style="font-weight:700; color:#D4AF37; font-size:0.92rem; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
              Request a free prepress file inspection &rarr;
            </a>
          </div>
        </div>

        <!-- Bottom Conversion CTA Card -->
        <div class="blog-cta-card">
          <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.14em; color:#D4AF37; margin-bottom:0.5rem;">
            Direct Production Support
          </div>
          <h3 style="font-size:clamp(1.5rem, 2.4vw, 2rem); color:#F5F1E8; margin:0 0 0.75rem; font-weight:800;">
            Ready to Start Your Print Order?
          </h3>
          <p style="color:#CBD5E1; max-width:620px; margin:0 auto 1.75rem; font-size:1.02rem; line-height:1.65;">
            Upload your design file or discuss fabric specifications, gang sheets, and custom merchandise runs with our Delhi prepress team.
          </p>
          <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
            <a href="<?= url('get-quote/') ?>" class="btn btn-primary btn-lg">
              <span>Get Instant Quote</span> &rarr;
            </a>
            <a href="<?= url('services.php') ?>" class="btn btn-outline btn-lg" style="border-color:rgba(212,175,55,0.4); color:#F5F1E8; background:#12151C;">
              <span>Explore All Services</span> &rarr;
            </a>
          </div>
        </div>
      </main>

      <!-- Right Column: Sticky Table of Contents -->
      <aside class="blog-sidebar-col">
        <div class="blog-toc-card">
          <div class="blog-toc-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="8" y1="6" x2="21" y2="6"></line>
              <line x1="8" y1="12" x2="21" y2="12"></line>
              <line x1="8" y1="18" x2="21" y2="18"></line>
              <line x1="3" y1="6" x2="3.01" y2="6"></line>
              <line x1="3" y1="12" x2="3.01" y2="12"></line>
              <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            <span>Table of Contents</span>
          </div>
          <div class="blog-toc-progress-track">
            <div class="blog-toc-progress-bar" id="blogTocProgressBar"></div>
          </div>
          <nav class="blog-toc-nav" id="blogTocNav">
            <!-- Dynamically populated from h2 headings by JS -->
          </nav>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- Related Articles Section -->
<?php if (!empty($relatedPosts)): ?>
<section class="section section-ivory" style="padding-top:4rem; padding-bottom:4.5rem;">
  <div class="container" style="max-width:1200px; margin:0 auto;">
    <div class="section-header text-center reveal slide-up">
      <span class="section-label" style="justify-content:center;">Recommended Reading</span>
      <h2 style="font-size:2.2rem; color:var(--navy);">Related Printing &amp; Production Guides</h2>
    </div>

    <div class="blog-related-grid">
      <?php foreach ($relatedPosts as $rel): ?>
        <article class="blog-card">
          <div class="blog-card-media" style="height:190px;">
            <img src="<?= url(e($rel['featured_image'])) ?>" alt="<?= e($rel['title']) ?>" loading="lazy">
            <span class="blog-card-badge">
              <?= e($rel['category_name']) ?>
            </span>
          </div>
          <div class="blog-card-body">
            <h3 class="blog-card-title">
              <a href="<?= blog_url($rel['slug']) ?>">
                <?= e($rel['title']) ?>
              </a>
            </h3>
            <p class="blog-card-desc">
              <?= e(mb_strimwidth($rel['excerpt'], 0, 110, '...')) ?>
            </p>
            <div class="blog-card-footer">
              <a href="<?= blog_url($rel['slug']) ?>" class="blog-card-link">
                Read Full Guide &rarr;
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Dynamic TOC, Scroll Spy & Collapsible FAQ Accordion Engine -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const article = document.getElementById('articleBody');
  const tocNav = document.getElementById('blogTocNav');
  if (!article) return;

  // 1. Auto-detect and format FAQs in older posts if not already wrapped
  const faqHeadings = Array.from(article.querySelectorAll('h2')).filter(function(h2) {
    return /frequently\s+asked/i.test(h2.textContent);
  });

  faqHeadings.forEach(function(faqH2) {
    let next = faqH2.nextElementSibling;
    if (next && next.classList.contains('faq-accordion')) return;

    const accordion = document.createElement('div');
    accordion.className = 'faq-accordion';

    let curr = faqH2.nextElementSibling;
    const itemsToWrap = [];
    while (curr && curr.tagName !== 'H2' && !curr.classList.contains('blog-author-box') && !curr.classList.contains('blog-cta-card')) {
      itemsToWrap.push(curr);
      curr = curr.nextElementSibling;
    }

    if (itemsToWrap.length > 0) {
      itemsToWrap.forEach(function(el) {
        const h4 = el.querySelector('h4') || (el.tagName === 'H4' ? el : null);
        const p = el.querySelector('p') || (el.tagName === 'P' ? el : null);
        if (h4 && p) {
          const item = document.createElement('div');
          item.className = 'faq-item';

          const q = document.createElement('div');
          q.className = 'faq-question';
          q.innerHTML = `<span>${h4.innerHTML}</span><span class="faq-icon">+</span>`;

          const a = document.createElement('div');
          a.className = 'faq-answer';
          a.innerHTML = p.innerHTML;

          item.appendChild(q);
          item.appendChild(a);
          accordion.appendChild(item);
          el.remove();
        }
      });

      if (accordion.children.length > 0) {
        faqH2.parentNode.insertBefore(accordion, faqH2.nextSibling);
      }
    }
  });

  // 2. Build Sticky Table of Contents (TOC) from all H2s
  if (tocNav) {
    const h2s = article.querySelectorAll('h2');
    if (h2s.length === 0) {
      const aside = document.querySelector('.blog-sidebar-col');
      if (aside) aside.style.display = 'none';
    } else {
      const tocList = document.createElement('ul');
      tocList.className = 'blog-toc-list';

      h2s.forEach(function(h2, index) {
        if (!h2.id) {
          const cleanSlug = h2.textContent
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
          h2.id = cleanSlug || ('section-' + (index + 1));
        }

        const li = document.createElement('li');
        li.className = 'blog-toc-item';

        const a = document.createElement('a');
        a.href = '#' + h2.id;
        a.className = 'blog-toc-link';
        a.textContent = h2.textContent.trim();
        a.setAttribute('data-target', h2.id);

        a.addEventListener('click', function(e) {
          e.preventDefault();
          const targetEl = document.getElementById(h2.id);
          if (targetEl) {
            const offset = 100;
            const elementPosition = targetEl.getBoundingClientRect().top + window.scrollY;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
            history.pushState(null, null, '#' + h2.id);
          }
        });

        li.appendChild(a);
        tocList.appendChild(li);
      });

      tocNav.appendChild(tocList);

      // Active state scroll spy
      const tocLinks = tocNav.querySelectorAll('.blog-toc-link');
      const progressBar = document.getElementById('blogTocProgressBar');

      function updateActiveToc() {
        let currentId = '';

        h2s.forEach(function(h2) {
          const rect = h2.getBoundingClientRect();
          if (rect.top <= 140) {
            currentId = h2.id;
          }
        });

        if (!currentId && h2s.length > 0) {
          const firstRect = h2s[0].getBoundingClientRect();
          if (firstRect.top < window.innerHeight * 0.7) {
            currentId = h2s[0].id;
          }
        }

        tocLinks.forEach(function(link) {
          if (link.getAttribute('data-target') === currentId) {
            link.classList.add('is-active');
          } else {
            link.classList.remove('is-active');
          }
        });

        // Reading Progress Bar
        if (progressBar) {
          const articleRect = article.getBoundingClientRect();
          const articleTopFromDoc = window.scrollY + articleRect.top;
          const articleHeight = article.offsetHeight;
          const currentProgress = Math.min(100, Math.max(0, ((window.scrollY - articleTopFromDoc + 150) / articleHeight) * 100));
          progressBar.style.width = currentProgress + '%';
        }
      }

      window.addEventListener('scroll', updateActiveToc, { passive: true });
      updateActiveToc();
    }
  }

  // 3. Universal Interactive FAQ Accordion Click Handler
  article.addEventListener('click', function(e) {
    const q = e.target.closest('.faq-question');
    if (!q) return;

    const item = q.parentElement;
    const accordion = item.closest('.faq-accordion');
    const isActive = item.classList.contains('active');

    if (accordion) {
      accordion.querySelectorAll('.faq-item.active').forEach(function(other) {
        if (other !== item) {
          other.classList.remove('active');
          const otherIcon = other.querySelector('.faq-icon');
          if (otherIcon) otherIcon.textContent = '+';
        }
      });
    }

    item.classList.toggle('active', !isActive);
    const icon = q.querySelector('.faq-icon');
    if (icon) {
      icon.textContent = !isActive ? '−' : '+';
    }
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
