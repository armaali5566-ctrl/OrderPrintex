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
  <title>Admin Authentication | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
  <style>
    body {
      background-color: var(--navy-dark);
      color: var(--white);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .admin-login-box {
      width: 100%;
      max-width: 440px;
      background-color: rgba(16, 27, 48, 0.95);
      border: 1px solid var(--border-gold);
      border-radius: var(--radius-md);
      padding: 2.75rem 2.25rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
    }
  </style>
</head>
<body>

<div class="admin-login-box">
  <div style="text-align:center; margin-bottom:2rem;">
    <a href="<?= url('') ?>" style="display:inline-block; background:#FFFFFF; padding:6px 18px; border-radius:8px;">
      <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:38px; margin:0 auto; display:block;">
    </a>
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.15em; color:var(--gold); font-weight:700; margin-top:0.75rem;">
      Admin Management Console
    </div>
  </div>

  <?= render_flash() ?>

  <?php if ($error): ?>
    <div class="alert alert-error" style="background-color:rgba(168,62,60,0.18); border-color:var(--crimson); color:#FCA5A5;">
      <div class="alert-content"><?= e($error) ?></div>
    </div>
  <?php endif; ?>

  <form action="<?= url('admin/login.php') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group" style="margin-bottom:1.25rem;">
      <label class="form-label" style="color:rgba(255,255,255,0.85);" for="adminEmail">Administrator Email</label>
      <input type="email" name="email" id="adminEmail" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" placeholder="admin@orderprintex.com" required autofocus style="background-color:rgba(255,255,255,0.06); color:#fff; border-color:rgba(255,255,255,0.15);">
    </div>

    <div class="form-group" style="margin-bottom:1.75rem;">
      <label class="form-label" style="color:rgba(255,255,255,0.85);" for="adminPassword">Master Password</label>
      <input type="password" name="password" id="adminPassword" class="form-control" placeholder="••••••••••••" required style="background-color:rgba(255,255,255,0.06); color:#fff; border-color:rgba(255,255,255,0.15);">
    </div>

    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
      <span>Authenticate &amp; Enter Dashboard</span> &rarr;
    </button>
  </form>

  <div style="margin-top:2rem; text-align:center; font-size:0.825rem; color:rgba(255,255,255,0.5);">
    <a href="<?= url('') ?>" style="color:var(--gold);">&larr; Return to Public Website</a>
  </div>
</div>

</body>
</html>
