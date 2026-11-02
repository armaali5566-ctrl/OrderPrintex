<?php
/**
 * OrderPrintex - Top Announcement Bar & Sticky Navigation Bar
 * Features: Slim rotating announcement banner, direct clean links, Upload CTA, Mobile Off-Canvas Drawer
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$user = auth_user();
?>

<?php
// Dynamic announcement messages from database if available
$announcements = [];
if (class_exists('Database') && Database::isConnected()) {
    try {
        $announcements = db_fetch_all("SELECT message_text, link_url FROM announcement_bar WHERE is_active = 1 ORDER BY display_order ASC");
    } catch (Exception $e) {
        $announcements = [];
    }
}
if (empty($announcements)) {
    $announcements = [
        ['message_text' => '🚚 Free Shipping Across India on Commercial Orders Above ₹999', 'link_url' => 'services/'],
        ['message_text' => '🎨 Upload Your Artwork & Get Instant Prepress Proof Analysis', 'link_url' => 'get-quote/'],
        ['message_text' => '📦 Need Bulk Wholesale Printing? Contact Our Commercial Desk', 'link_url' => 'contact/']
    ];
}
?>

<!-- Top Slim Announcement Bar (Non-Sticky, Scrolls Away Naturally) -->
<div class="top-announcement-bar" id="topAnnouncementBar" role="region" aria-label="Promotions and notices">
  <div class="announcement-container">
    <div class="announcement-slider" id="announcementSlider">
      <?php foreach ($announcements as $idx => $ann): ?>
        <div class="announcement-slide <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>">
          <span>
            <strong><?= e($ann['message_text']) ?></strong>
            <?php if (!empty($ann['link_url'])): ?>
              &bull; <a href="<?= url(e($ann['link_url'])) ?>" style="color:var(--gold); text-decoration:underline;">Learn More &rarr;</a>
            <?php endif; ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="announcement-close" id="announcementCloseBtn" onclick="dismissAnnouncement()" aria-label="Dismiss announcement">&times;</button>
  </div>
</div>

<header class="site-header" id="mainHeader">
  <div class="nav-container">
    <!-- Brand / Original OrderPrintex Logo -->
    <a href="<?= url('') ?>" class="site-brand" aria-label="OrderPrintex Home">
      <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex - Manufacturer & Bulk Supplier of DTF and UV DTF in India" class="site-logo-img">
    </a>

    <!-- Main Navigation Menu (Clean navigation with NO dropdowns) -->
    <nav class="nav-menu" id="primaryNav" aria-label="Primary Navigation">
      <div class="nav-item">
        <a href="<?= url('') ?>" class="nav-link <?= ($currentUri === '/' || str_ends_with($trimmedUri ?? '', 'index.php')) ? 'active' : '' ?>">Home</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('about/') ?>" class="nav-link <?= strpos($currentUri, 'about') !== false ? 'active' : '' ?>">About Us</a>
      </div>
      
      <!-- Printing Services (Normal direct navigation link without dropdown) -->
      <div class="nav-item">
        <a href="<?= url('services/') ?>" class="nav-link <?= (strpos($currentUri, 'services') !== false) ? 'active' : '' ?>">Printing Services</a>
      </div>

      <div class="nav-item">
        <a href="<?= url('products/') ?>" class="nav-link <?= (strpos($currentUri, 'product') !== false) ? 'active' : '' ?>">Products</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('gallery/') ?>" class="nav-link <?= strpos($currentUri, 'gallery') !== false ? 'active' : '' ?>">Portfolio</a>
      </div>
      <!-- Dedicated Blog Page (Replaced FAQ in primary navigation) -->
      <div class="nav-item">
        <a href="<?= blog_url() ?>" class="nav-link <?= strpos($currentUri, 'blog') !== false ? 'active' : '' ?>">Blog</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('contact/') ?>" class="nav-link <?= strpos($currentUri, 'contact') !== false ? 'active' : '' ?>">Contact</a>
      </div>

      <!-- Mobile drawer account link if authenticated -->
      <?php if ($user): ?>
        <div class="nav-item mobile-auth-link" style="margin-top:1rem; border-top:1px solid rgba(16,27,48,0.1); padding-top:1rem; width:100%;">
          <?php if ($user['role'] === 'admin'): ?>
            <a href="<?= url('admin/dashboard.php') ?>" class="nav-link text-gold">Admin Console</a>
          <?php else: ?>
            <a href="<?= url('account/dashboard.php') ?>" class="nav-link text-gold">My Dashboard</a>
          <?php endif; ?>
          <a href="<?= url('account/logout.php') ?>" class="nav-link" style="color:var(--crimson);">Sign Out</a>
        </div>
      <?php endif; ?>
    </nav>

    <!-- Nav Actions Group -->
    <div class="nav-actions">
      <?php if ($user): ?>
        <?php if ($user['role'] === 'admin'): ?>
          <a href="<?= url('admin/dashboard.php') ?>" class="btn btn-sm btn-navy">Admin Panel</a>
        <?php else: ?>
          <a href="<?= url('account/dashboard.php') ?>" class="btn btn-sm btn-outline">My Account</a>
        <?php endif; ?>
      <?php endif; ?>

      <a href="<?= url('get-quote.php') ?>" class="btn btn-sm btn-primary nav-cta-btn">
        <span>Upload Your Design</span>
        <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
      </a>

      <!-- Mobile Hamburger Toggle -->
      <button type="button" class="mobile-toggle" id="mobileMenuToggle" aria-label="Toggle mobile navigation menu">
        <svg style="width:26px;height:26px;fill:currentColor;" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
  </div>
</header>
