<?php
/**
 * OrderPrintex - Admin Portal Header Partial
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();
$adminUser = auth_user();

$activeRoute = basename($_SERVER['PHP_SELF']);

// Live notification badges
$newEnquiriesCount = db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE status = 'received'")['cnt'] ?? 0;
$draftQuotesCount  = db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status = 'draft'")['cnt'] ?? 0;
$activeOrdersCount = db_fetch_one("SELECT COUNT(*) as cnt FROM orders WHERE order_status NOT IN ('delivered', 'cancelled')")['cnt'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($adminTitle ?? 'Admin Console') ?> | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/animations.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
</head>
<body>

<div class="dashboard-layout">
  <!-- Admin Sidebar -->
  <aside class="dashboard-sidebar" id="adminSidebar">
    <div class="sidebar-header">
      <a href="<?= url('admin/dashboard.php') ?>" class="sidebar-brand" style="display:inline-flex; align-items:center; background:#FFFFFF; padding:4px 12px; border-radius:6px;">
        <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:28px; width:auto; max-width:160px; display:block;">
      </a>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-label">Core Business</div>
      <a href="<?= url('admin/dashboard.php') ?>" class="sidebar-link <?= ($activeRoute === 'dashboard.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <a href="<?= url('admin/orders.php') ?>" class="sidebar-link <?= ($activeRoute === 'orders.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
          <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
        </svg>
        <span>Manage Orders</span>
        <?php if ($activeOrdersCount > 0): ?>
          <span class="sidebar-badge"><?= $activeOrdersCount ?></span>
        <?php endif; ?>
      </a>

      <a href="<?= url('admin/enquiries.php') ?>" class="sidebar-link <?= ($activeRoute === 'enquiries.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
        <span>Enquiries &amp; Artwork</span>
        <?php if ($newEnquiriesCount > 0): ?>
          <span class="sidebar-badge"><?= $newEnquiriesCount ?></span>
        <?php endif; ?>
      </a>

      <a href="<?= url('admin/quotations.php') ?>" class="sidebar-link <?= ($activeRoute === 'quotations.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
        </svg>
        <span>Quotations</span>
      </a>

      <div class="sidebar-label">Catalog &amp; Production</div>
      <a href="<?= url('admin/products.php') ?>" class="sidebar-link <?= ($activeRoute === 'products.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
        </svg>
        <span>Manage Products</span>
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
        <span>Product Reviews</span>
      </a>

      <a href="<?= url('admin/customers.php') ?>" class="sidebar-link <?= ($activeRoute === 'customers.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
        </svg>
        <span>Customers</span>
      </a>

      <a href="<?= url('admin/gallery.php') ?>" class="sidebar-link <?= ($activeRoute === 'gallery.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
        </svg>
        <span>Gallery &amp; Showcase</span>
      </a>

      <div class="sidebar-label">Content &amp; Configuration</div>
      <a href="<?= url('admin/blog.php') ?>" class="sidebar-link <?= ($activeRoute === 'blog.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
          <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
        </svg>
        <span>Blog Articles</span>
      </a>

      <a href="<?= url('admin/announcement.php') ?>" class="sidebar-link <?= ($activeRoute === 'announcement.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"/>
        </svg>
        <span>Announcement &amp; Equipment</span>
      </a>

      <a href="<?= url('admin/content.php') ?>" class="sidebar-link <?= ($activeRoute === 'content.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
        </svg>
        <span>Website Content</span>
      </a>

      <a href="<?= url('admin/faqs.php') ?>" class="sidebar-link <?= ($activeRoute === 'faqs.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
        </svg>
        <span>Manage FAQs</span>
      </a>

      <a href="<?= url('admin/settings.php') ?>" class="sidebar-link <?= ($activeRoute === 'settings.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
        </svg>
        <span>Website Settings</span>
      </a>

      <a href="<?= url('admin/users.php') ?>" class="sidebar-link <?= ($activeRoute === 'users.php') ? 'active' : '' ?>">
        <svg class="sidebar-link-icon" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>Admin Users</span>
      </a>

      <div style="margin-top:1rem; border-top:1px solid rgba(255,255,255,0.08); padding-top:0.75rem;">
        <a href="<?= url('') ?>" target="_blank" class="sidebar-link" style="color:var(--gold);">
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
        <div class="user-avatar"><?= strtoupper(substr($adminUser['name'], 0, 1)) ?></div>
        <div class="user-info">
          <div class="user-name"><?= e($adminUser['name']) ?></div>
          <div class="user-role">Administrator</div>
        </div>
      </div>
      <a href="<?= url('account/logout.php') ?>" title="Logout" style="color:rgba(255,255,255,0.6); padding:0.35rem;">
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
        <button type="button" class="dashboard-sidebar-toggle" aria-label="Toggle Sidebar">
          &#9776;
        </button>
        <div class="topbar-title"><?= e($adminTitle ?? 'Admin Dashboard') ?></div>
      </div>
      <div class="topbar-right">
        <span style="font-size:0.85rem; color:var(--slate);">
          Logged in as <strong><?= e($adminUser['email']) ?></strong>
        </span>
        <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-sm btn-primary">
          + Create Quotation
        </a>
      </div>
    </header>

    <main class="dashboard-content">
      <?= render_flash() ?>
