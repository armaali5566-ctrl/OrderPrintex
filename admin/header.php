<?php
/**
 * OrderPrintex - Enterprise Admin Portal Header Partial
 * Unified navigation, breadcrumbs, live counters & theme management
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_panel();
$adminUser = auth_user();
$isAdmin = ($adminUser && $adminUser['role'] === 'admin');
$isStaff = ($adminUser && $adminUser['role'] === 'staff');

$activeRoute = basename($_SERVER['PHP_SELF']);

// Live notification badges
$newEnquiriesCount   = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE status = 'received'")['cnt'] ?? 0);
$draftQuotesCount    = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status IN ('draft', 'sent')")['cnt'] ?? 0);
$pendingReviewsCount = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM product_reviews WHERE status = 'pending'")['cnt'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    document.documentElement.setAttribute('data-theme', 'dark');
  </script>
  <title><?= e($adminTitle ?? 'Admin Console') ?> | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/animations.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/responsive.css') ?>">
</head>
<body>

<div class="dashboard-layout">
  <!-- Admin Sidebar -->
  <aside class="dashboard-sidebar" id="adminSidebar">
    <div class="sidebar-header">
      <a href="<?= url('admin/dashboard.php') ?>" class="sidebar-brand">
        <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:38px; width:38px; border-radius:50%; object-fit:cover; border:1.5px solid rgba(212,175,55,0.7); box-shadow:0 0 10px rgba(212,175,55,0.3);">
        <div>
          <span class="sidebar-brand-title">OrderPrintex</span>
          <span class="sidebar-brand-badge">Enterprise Console</span>
        </div>
      </a>
      <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close Navigation Drawer">&times;</button>
    </div>

    <nav class="sidebar-nav" id="adminSidebarNav">
      <script>
        (function() {
          try {
            var pos = sessionStorage.getItem('op_admin_sidebar_scroll');
            if (pos !== null) {
              var nav = document.getElementById('adminSidebarNav');
              if (nav) nav.scrollTop = parseInt(pos, 10);
            }
          } catch (e) {}
        })();
      </script>
      
      <!-- 1. OVERVIEW -->
      <div class="sidebar-label">Overview</div>
      <a href="<?= url('admin/dashboard.php') ?>" class="sidebar-link <?= ($activeRoute === 'dashboard.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <!-- 2. CORE BUSINESS -->
      <div class="sidebar-label">Core Business</div>
      <a href="<?= url('admin/enquiries.php') ?>" class="sidebar-link <?= ($activeRoute === 'enquiries.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
        <span>Customer Enquiries</span>
        <?php if ($newEnquiriesCount > 0): ?>
          <span class="sidebar-badge"><?= $newEnquiriesCount ?></span>
        <?php endif; ?>
      </a>

      <a href="<?= url('admin/quotations.php') ?>" class="sidebar-link <?= ($activeRoute === 'quotations.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
        </svg>
        <span>Quotations</span>
        <?php if ($draftQuotesCount > 0): ?>
          <span class="sidebar-badge"><?= $draftQuotesCount ?></span>
        <?php endif; ?>
      </a>

      <a href="<?= url('admin/customers.php') ?>" class="sidebar-link <?= ($activeRoute === 'customers.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
        </svg>
        <span>Customer Accounts</span>
      </a>

      <!-- 3. CATALOGUE & SERVICES -->
      <div class="sidebar-label">Catalogue &amp; Services</div>
      <a href="<?= url('admin/products.php') ?>" class="sidebar-link <?= ($activeRoute === 'products.php' || $activeRoute === 'variations.php' || $activeRoute === 'attributes.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
        </svg>
        <span>Product Management</span>
      </a>

      <?php if ($isAdmin): ?>
      <a href="<?= url('admin/categories.php') ?>" class="sidebar-link <?= ($activeRoute === 'categories.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
        </svg>
        <span>Manage Categories</span>
      </a>

      <a href="<?= url('admin/services.php') ?>" class="sidebar-link <?= ($activeRoute === 'services.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
        </svg>
        <span>Printing Services</span>
      </a>

      <a href="<?= url('admin/reviews.php') ?>" class="sidebar-link <?= ($activeRoute === 'reviews.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span>Customer Reviews</span>
        <?php if ($pendingReviewsCount > 0): ?>
          <span class="sidebar-badge" style="background:#F59E0B;"><?= $pendingReviewsCount ?></span>
        <?php endif; ?>
      </a>

      <!-- 4. SHOWCASE & EDITORIAL -->
      <div class="sidebar-label">Showcase &amp; Editorial</div>
      <a href="<?= url('admin/gallery.php') ?>" class="sidebar-link <?= ($activeRoute === 'gallery.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
        </svg>
        <span>Portfolio Management</span>
      </a>

      <a href="<?= url('admin/blog.php') ?>" class="sidebar-link <?= ($activeRoute === 'blog.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
          <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
        </svg>
        <span>Blog Articles</span>
      </a>

      <a href="<?= url('admin/settings.php') ?>" class="sidebar-link <?= ($activeRoute === 'settings.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
        </svg>
        <span>Website Settings</span>
      </a>

      <!-- 5. ADMINISTRATION -->
      <div class="sidebar-label">Administration</div>
      <a href="<?= url('admin/users.php') ?>" class="sidebar-link <?= ($activeRoute === 'users.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>Admin Personnel</span>
      </a>

      <a href="<?= url('admin/activity.php') ?>" class="sidebar-link <?= ($activeRoute === 'activity.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h7a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
        </svg>
        <span>Security &amp; Activity Log</span>
      </a>
      <?php endif; ?>

      <!-- Public Site Link -->
      <div style="margin-top:1rem; border-top:1px solid rgba(255,255,255,0.08); padding-top:0.75rem;">
        <a href="<?= url('') ?>" target="_blank" class="sidebar-link" style="color:var(--admin-gold);">
          <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
          </svg>
          <span>View Public Site &rarr;</span>
        </a>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="user-chip">
        <div class="user-avatar" style="background: <?= $isAdmin ? 'var(--admin-sidebar-active-bg, rgba(212,175,55,0.18))' : 'rgba(56,189,248,0.18)' ?>; color: <?= $isAdmin ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; border: 1.5px solid <?= $isAdmin ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>;">
          <?= strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="user-info">
          <div class="user-name"><?= e($adminUser['name'] ?? 'Personnel') ?></div>
          <div class="user-role" style="color: <?= $isAdmin ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; font-weight: 700; font-size: 0.75rem;">
            <?= $isAdmin ? 'Super Admin' : 'Staff Member' ?>
          </div>
        </div>
      </div>
      <a href="<?= account_url('logout') ?>" title="Logout" style="color:rgba(255,255,255,0.6); padding:0.35rem; display:flex;">
        <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
  </aside>

  <!-- Main Body Wrapper -->
  <div class="dashboard-main">
    <header class="dashboard-topbar">
      <div class="topbar-left">
        <button type="button" class="dashboard-sidebar-toggle" aria-label="Toggle Sidebar" title="Toggle Sidebar">
          &#9776;
        </button>
        <div class="dashboard-breadcrumbs">
          <a href="<?= url('admin/dashboard.php') ?>" class="breadcrumb-root">Admin</a>
          <span class="breadcrumb-sep">/</span>
          <span class="breadcrumb-current"><?= e($adminTitle ?? 'Dashboard') ?></span>
        </div>
      </div>
      <div class="topbar-right">
        <span class="topbar-user-badge" style="display:inline-flex; align-items:center; gap:6px;">
          Signed in as <strong><?= e($adminUser['email'] ?? 'admin') ?></strong>
          <span class="badge" style="font-size:0.7rem; padding:2px 8px; background:<?= $isAdmin ? 'rgba(212,175,55,0.2)' : 'rgba(56,189,248,0.2)' ?>; color:<?= $isAdmin ? 'var(--admin-gold)' : '#38BDF8' ?>; border:1px solid <?= $isAdmin ? 'rgba(212,175,55,0.4)' : 'rgba(56,189,248,0.4)' ?>; font-weight:700;">
            <?= $isAdmin ? 'Super Admin' : 'Staff' ?>
          </span>
        </span>
        <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-sm btn-primary">
          + Create Quotation
        </a>
      </div>
    </header>

    <main class="dashboard-content">
      <?= render_flash() ?>
