<?php
/**
 * OrderPrintex - Sticky Top Contact & Social Media Bar + Sticky Navigation Bar
 * Features: Professional Contact details, Social media profiles, Responsive Drawer, Sticky Double-Bar
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$user = auth_user();

// Dynamic settings for Contact & Social bar
$topEmail     = get_setting('email', 'sales@orderprintex.com');
$topPhone     = get_setting('phone', '+91 93540 28199');
$topPhoneRaw  = get_setting('phone_raw', '919354028199');
$topWhatsApp  = get_setting('whatsapp_raw', get_setting('phone_raw', '919354028199'));
$topInstagram = get_setting('social_instagram', 'https://www.instagram.com/orderprintex');
$topFacebook  = get_setting('social_facebook', 'https://www.facebook.com/orderprintex');
$topYouTube   = get_setting('social_youtube', 'https://www.youtube.com/@orderprintex');
?>

<!-- Public Top Contact & Verified Channels Bar (Non-Sticky) -->
<div class="top-contact-bar announcement-bar" id="topContactBar" role="region" aria-label="Contact and Social Information">
  <div class="top-contact-container">
    <!-- Left: Direct Contact Information -->
    <div class="top-contact-left">
      <!-- Email -->
      <a href="mailto:<?= e($topEmail) ?>" class="top-contact-item" title="Send us an email">
        <svg class="top-contact-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
        <span class="top-contact-text"><?= e($topEmail) ?></span>
      </a>

      <span class="top-contact-sep" aria-hidden="true">&bull;</span>

      <!-- Phone -->
      <a href="tel:<?= e($topPhoneRaw) ?>" class="top-contact-item" title="Call our Delhi studio">
        <svg class="top-contact-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
        </svg>
        <span class="top-contact-text"><?= e($topPhone) ?></span>
      </a>

      <span class="top-contact-sep" aria-hidden="true">&bull;</span>

      <!-- WhatsApp -->
      <a href="https://wa.me/<?= e($topWhatsApp) ?>?text=<?= urlencode('Hello OrderPrintex, I have an enquiry regarding printing services.') ?>" target="_blank" rel="noopener noreferrer" class="top-contact-item top-whatsapp-item" title="Chat on WhatsApp">
        <svg class="top-contact-icon whatsapp-color" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12.031 2c-5.509 0-9.986 4.477-9.986 9.986 0 1.761.459 3.479 1.331 4.991l-1.376 5.023 5.143-1.348c1.464.799 3.111 1.22 4.888 1.22 5.509 0 9.986-4.477 9.986-9.986 0-5.509-4.477-9.986-9.986-9.986zm5.828 14.153c-.244.686-1.42 1.312-1.956 1.393-.518.077-1.196.111-3.666-.913-2.923-1.213-4.81-4.186-4.954-4.38-.146-.194-1.187-1.58-1.187-3.013 0-1.433.748-2.14 1.015-2.433.267-.293.582-.366.777-.366.195 0 .389.002.559.01.18.008.423-.068.662.506.244.585.83 2.025.903 2.172.073.146.122.317.024.512-.097.195-.146.317-.292.488-.146.17-.308.38-.44.51-.146.145-.3.303-.13.595.17.292.756 1.246 1.623 2.018 1.116.994 2.057 1.303 2.349 1.449.293.146.463.122.634-.073.17-.195.731-.853.926-1.145.195-.292.39-.244.658-.146.268.098 1.706.804 1.998.951.293.146.488.219.56.341.073.122.073.707-.171 1.393z"/>
        </svg>
        <span class="top-contact-text">WhatsApp</span>
      </a>
    </div>

    <!-- Right: Verified Social Media Profiles -->
    <div class="top-contact-right">
      <span class="top-social-label">Follow Us:</span>

      <!-- Instagram -->
      <a href="<?= e($topInstagram) ?>" target="_blank" rel="noopener noreferrer" class="top-social-link" title="Follow OrderPrintex on Instagram" aria-label="Instagram">
        <svg class="top-social-icon" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
        </svg>
      </a>

      <!-- Facebook -->
      <a href="<?= e($topFacebook) ?>" target="_blank" rel="noopener noreferrer" class="top-social-link" title="Follow OrderPrintex on Facebook" aria-label="Facebook">
        <svg class="top-social-icon" viewBox="0 0 24 24" fill="currentColor">
          <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
        </svg>
      </a>

      <!-- YouTube -->
      <a href="<?= e($topYouTube) ?>" target="_blank" rel="noopener noreferrer" class="top-social-link" title="Watch OrderPrintex on YouTube" aria-label="YouTube">
        <svg class="top-social-icon" viewBox="0 0 24 24" fill="currentColor">
          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
      </a>
    </div>
  </div>
</div>

<header class="site-header" id="mainHeader">
  <div class="nav-container">
    <!-- Brand / Original OrderPrintex Logo -->
    <a href="<?= url('') ?>" class="site-brand" aria-label="OrderPrintex Home">
      <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex - Manufacturer & Bulk Supplier of DTF and UV DTF in India" class="site-logo-img" width="48" height="48">
    </a>

    <!-- Main Navigation Menu with Restored Printing Services Dropdown -->
    <nav class="nav-menu" id="primaryNav" aria-label="Primary Navigation">
      <div class="nav-item">
        <a href="<?= url('') ?>" class="nav-link <?= ($currentUri === '/' || str_ends_with($trimmedUri ?? '', 'index.php')) ? 'active' : '' ?>">Home</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('about') ?>" class="nav-link <?= strpos($currentUri, 'about') !== false ? 'active' : '' ?>">About Us</a>
      </div>
      
      <!-- Restored Printing Services Dropdown -->
      <div class="nav-item has-dropdown <?= (strpos($currentUri, 'services') !== false) ? 'active' : '' ?>" id="servicesDropdownItem">
        <a href="<?= url('services') ?>" class="nav-link dropdown-toggle <?= (strpos($currentUri, 'services') !== false) ? 'active' : '' ?>" id="servicesDropdownToggle" aria-haspopup="true" aria-expanded="false" role="button">
          <span>Printing Services</span>
          <svg class="dropdown-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </a>
        <div class="dropdown-menu" id="servicesDropdownMenu" aria-labelledby="servicesDropdownToggle" role="menu">
          <a href="<?= service_url('dtf-printing') ?>" class="dropdown-link <?= (strpos($currentUri, 'dtf-printing') !== false) ? 'active' : '' ?>" role="menuitem">
            <span>DTF Printing</span>
          </a>
          <a href="<?= service_url('uv-dtf-printing') ?>" class="dropdown-link <?= (strpos($currentUri, 'uv-dtf-printing') !== false) ? 'active' : '' ?>" role="menuitem">
            <span>UV DTF Printing</span>
          </a>
          <a href="<?= service_url('t-shirt-printing') ?>" class="dropdown-link <?= (strpos($currentUri, 't-shirt-printing') !== false) ? 'active' : '' ?>" role="menuitem">
            <span>T-Shirt Printing</span>
          </a>
          <a href="<?= service_url('corporate-printing') ?>" class="dropdown-link <?= (strpos($currentUri, 'corporate-printing') !== false) ? 'active' : '' ?>" role="menuitem">
            <span>Corporate Printing</span>
          </a>
          <a href="<?= url('services') ?>" class="dropdown-link dropdown-link-all" role="menuitem">
            <span>All Printing Services &rarr;</span>
          </a>
        </div>
      </div>

      <div class="nav-item">
        <a href="<?= url('products') ?>" class="nav-link <?= (strpos($currentUri, 'product') !== false) ? 'active' : '' ?>">Products</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('gallery') ?>" class="nav-link <?= strpos($currentUri, 'gallery') !== false ? 'active' : '' ?>">Portfolio</a>
      </div>
      <!-- Dedicated Blog Page (Replaced FAQ in primary navigation) -->
      <div class="nav-item">
        <a href="<?= blog_url() ?>" class="nav-link <?= strpos($currentUri, 'blog') !== false ? 'active' : '' ?>">Blog</a>
      </div>
      <div class="nav-item">
        <a href="<?= url('contact') ?>" class="nav-link <?= strpos($currentUri, 'contact') !== false ? 'active' : '' ?>">Contact</a>
      </div>
    </nav>

    <!-- Nav Actions Group -->
    <div class="nav-actions">
      <!-- Account User Dropdown -->
      <div class="nav-user-dropdown" id="navUserDropdown">
        <button type="button" class="btn-nav-icon nav-user-btn" id="navUserBtn" aria-label="Account Menu" aria-haspopup="true" aria-expanded="false" title="Account">
          <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 24 24">
            <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"/>
          </svg>
        </button>
        <div class="user-dropdown-menu" id="userDropdownMenu" role="menu" aria-labelledby="navUserBtn">
          <?php if ($user): ?>
            <div class="user-dropdown-header">
              <span class="user-dropdown-name"><?= e($user['name'] ?? 'Account') ?></span>
              <span class="user-dropdown-email"><?= e($user['email'] ?? '') ?></span>
            </div>
            <div class="user-dropdown-divider"></div>
            <?php if ($user['role'] === 'admin'): ?>
              <a href="<?= url('admin/dashboard.php') ?>" class="user-dropdown-item" role="menuitem">
                <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                <span>Admin Console</span>
              </a>
            <?php endif; ?>
            <a href="<?= account_url() ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
              <span>Dashboard Overview</span>
            </a>
            <a href="<?= account_url('enquiries') ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
              <span>My Enquiries</span>
            </a>
            <a href="<?= account_url('quotations') ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
              <span>My Quotations</span>
            </a>
            <a href="<?= account_url('profile') ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
              <span>Account Settings</span>
            </a>
            <div class="user-dropdown-divider"></div>
            <a href="<?= account_url('logout') ?>" class="user-dropdown-item text-gold" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
              <span>Sign Out</span>
            </a>
          <?php else: ?>
            <a href="<?= account_url('login') ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              <span>Login</span>
            </a>
            <a href="<?= account_url('register') ?>" class="user-dropdown-item" role="menuitem">
              <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/></svg>
              <span>Register</span>
            </a>
          <?php endif; ?>
        </div>
      </div>

<!-- Mobile Hamburger Toggle -->
      <button type="button" class="mobile-toggle" id="mobileMenuToggle" aria-label="Toggle mobile navigation menu">
        <svg style="width:26px;height:26px;fill:currentColor;" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
  </div>
</header>
