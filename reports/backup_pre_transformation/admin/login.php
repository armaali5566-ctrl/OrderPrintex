<?php
/**
 * OrderPrintex - Dedicated Administrator Login
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirect if already logged in as admin
if (auth_check() && auth_is_admin()) {
    header('Location: ' . url('admin/dashboard.php'));
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $result = auth_login($email, $password);

        if ($result['success']) {
            $user = $result['user'];
            if ($user['role'] !== 'admin') {
                auth_logout();
                $error = 'Access denied. You do not have administrator permissions.';
            } else {
                set_flash('success', "Welcome to the OrderPrintex Admin Console, {$user['name']}!");
                header('Location: ' . url('admin/dashboard.php'));
                exit;
            }
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    (function() {
      try {
        var t = localStorage.getItem('op_theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>
  <title>Admin Authentication | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
  <style>
    body {
      background-color: var(--bg-main);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
    }
    .admin-login-box {
      width: 100%;
      max-width: 440px;
      background-color: var(--bg-surface);
      border: var(--border-card);
      border-radius: var(--radius-md);
      padding: 2.75rem 2.25rem;
      box-shadow: var(--shadow-xl);
    }
    .admin-login-theme-pos {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
    }
  </style>
</head>
<body>

<div class="admin-login-theme-pos">
  <button type="button" class="theme-toggle-btn" aria-label="Toggle light or dark theme" title="Toggle Theme">
    <span class="theme-icon-light" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
        <circle cx="12" cy="12" r="5"></circle>
        <line x1="12" y1="1" x2="12" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="23"></line>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
        <line x1="1" y1="12" x2="3" y2="12"></line>
        <line x1="21" y1="12" x2="23" y2="12"></line>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
      </svg>
    </span>
    <span class="theme-icon-dark" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
      </svg>
    </span>
  </button>
</div>

<div class="admin-login-box">
  <div style="text-align:center; margin-bottom:2rem;">
    <a href="<?= url('') ?>" style="display:inline-block; background:transparent; padding:0;">
      <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:64px; width:64px; border-radius:50%; object-fit:cover; margin:0 auto; display:block; border:1.5px solid rgba(212,175,55,0.6); box-shadow:0 0 16px rgba(212,175,55,0.35);">
    </a>
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.15em; color:var(--btn-primary-bg); font-weight:700; margin-top:0.75rem;">
      Admin Management Console
    </div>
  </div>

  <?= render_flash() ?>

  <?php if ($error): ?>
    <div class="alert alert-error">
      <div class="alert-content"><?= e($error) ?></div>
    </div>
  <?php endif; ?>

  <form action="<?= url('admin/login.php') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group" style="margin-bottom:1.25rem;">
      <label class="form-label" for="adminEmail">Administrator Email</label>
      <input type="email" name="email" id="adminEmail" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" placeholder="admin@orderprintex.com" required autofocus>
    </div>

    <div class="form-group" style="margin-bottom:1.75rem;">
      <label class="form-label" for="adminPassword">Master Password</label>
      <input type="password" name="password" id="adminPassword" class="form-control" placeholder="••••••••••••" required>
    </div>

    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
      <span>Authenticate &amp; Enter Dashboard</span> &rarr;
    </button>
  </form>

  <div style="margin-top:2rem; text-align:center; font-size:0.825rem; color:var(--text-secondary);">
    <a href="<?= url('') ?>" style="color:var(--btn-primary-bg); font-weight:600;">&larr; Return to Public Website</a>
  </div>
</div>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
