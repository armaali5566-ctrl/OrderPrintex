<?php
/**
 * OrderPrintex - 403 Forbidden / Access Denied Page
 * Rendered when a staff or restricted account attempts to access Super Admin modules.
 */

if (!headers_sent()) {
    http_response_code(403);
}

$currentUser = auth_user();
$denialReason = $denialReason ?? 'Administrator authorization is required to access this system module.';
$adminTitle = "Access Denied (403)";
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($adminTitle) ?> | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
  <style>
    body {
      background: var(--admin-bg-base, #0B0B0E);
      color: var(--admin-text-main, #F3F4F6);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .denied-card {
      background: var(--admin-bg-card, #131318);
      border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.22));
      border-radius: 14px;
      max-width: 580px;
      width: 100%;
      padding: 2.75rem 2.25rem;
      text-align: center;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.65), 0 0 30px rgba(212, 175, 55, 0.05);
      position: relative;
    }
    .denied-icon-wrap {
      width: 68px;
      height: 68px;
      margin: 0 auto 1.5rem;
      border-radius: 50%;
      background: rgba(239, 68, 68, 0.12);
      border: 2px solid rgba(239, 68, 68, 0.4);
      color: #EF4444;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
    }
    .denied-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      background: rgba(212, 175, 55, 0.15);
      color: var(--admin-gold, #D4AF37);
      border: 1px solid rgba(212, 175, 55, 0.3);
      margin-bottom: 1rem;
    }
    .denied-title {
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--admin-text-main, #FFFFFF);
      margin: 0 0 0.75rem;
      letter-spacing: -0.02em;
    }
    .denied-desc {
      color: var(--admin-text-muted, #94A3B8);
      font-size: 0.92rem;
      line-height: 1.6;
      margin: 0 0 1.75rem;
    }
    .denied-details {
      background: rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 8px;
      padding: 1rem 1.25rem;
      text-align: left;
      font-size: 0.83rem;
      margin-bottom: 2rem;
    }
    .denied-actions {
      display: flex;
      justify-content: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>

<div class="denied-card">
  <div class="denied-icon-wrap">
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
  </div>

  <span class="denied-badge">Role Permission Boundary</span>
  <h1 class="denied-title">Administrative Access Denied</h1>

  <p class="denied-desc">
    <?= e($denialReason) ?>
  </p>

  <?php if ($currentUser): ?>
    <div class="denied-details">
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
        <span style="color: var(--admin-text-muted, #94A3B8);">Signed in as:</span>
        <strong style="color: var(--admin-text-main, #FFFFFF);"><?= e($currentUser['name']) ?> (<?= e($currentUser['email']) ?>)</strong>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span style="color: var(--admin-text-muted, #94A3B8);">Current Role:</span>
        <span class="badge" style="background: <?= $currentUser['role'] === 'staff' ? 'rgba(56,189,248,0.2)' : 'rgba(212,175,55,0.2)' ?>; color: <?= $currentUser['role'] === 'staff' ? '#38BDF8' : '#D4AF37' ?>; font-weight: 700; padding: 2px 8px; font-size: 0.75rem; border-radius: 4px;">
          <?= strtoupper(e($currentUser['role'])) ?>
        </span>
      </div>
    </div>
  <?php endif; ?>

  <div class="denied-actions">
    <a href="<?= url('admin/dashboard.php') ?>" class="btn btn-primary" style="padding: 0.65rem 1.4rem; font-weight: 700;">
      &larr; Return to Dashboard
    </a>
    <a href="<?= url('admin/enquiries.php') ?>" class="btn btn-outline" style="padding: 0.65rem 1.25rem;">
      Customer Enquiries
    </a>
    <a href="<?= url('admin/quotations.php') ?>" class="btn btn-outline" style="padding: 0.65rem 1.25rem;">
      Quotation Desk
    </a>
  </div>
</div>

</body>
</html>

