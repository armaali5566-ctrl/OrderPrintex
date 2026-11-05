<?php
/**
 * OrderPrintex - Customer & Admin Login Page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirect if already logged in
if (auth_check()) {
    $u = auth_user();
    if ($u && $u['role'] === 'admin') {
        header('Location: ' . url('admin/dashboard.php'));
    } else {
        header('Location: ' . url('account/dashboard.php'));
    }
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
            set_flash('success', "Welcome back, {$user['name']}!");

            $targetUrl = $_SESSION['intended_url'] ?? '';
            unset($_SESSION['intended_url']);

            if (!empty($targetUrl) && strpos($targetUrl, 'logout') === false) {
                header('Location: ' . $targetUrl);
            } else {
                if ($user['role'] === 'admin') {
                    header('Location: ' . url('admin/dashboard.php'));
                } else {
                    header('Location: ' . account_url());
                }
            }
            exit;
        } else {
            $error = $result['error'];
        }
    }
}

$pageTitle = "Sign In to Your Account | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section section-ivory" style="min-height:80vh; display:flex; align-items:center;">
  <div class="container container-narrow">
    <div class="upload-card reveal scale-in" style="max-width:480px; margin:0 auto; padding:2.5rem;">
      <div class="text-center" style="margin-bottom:2rem;">
        <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:44px; width:auto; margin:0 auto 1.25rem; display:block;">
        <div class="section-label" style="justify-content:center;">Client Portal</div>
        <h2>Sign In to OrderPrintex</h2>
        <p style="font-size:0.9rem; color:var(--slate);">
          Access your quotation history, track custom prepress enquiries, and collaborate with our printing team.
        </p>
      </div>

      <?= render_flash() ?>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <div class="alert-content">
            <?= e($error) ?>
          </div>
        </div>
      <?php endif; ?>

      <form action="<?= account_url('login') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group full-width">
            <label class="form-label" for="loginEmail">Email Address</label>
            <input type="email" name="email" id="loginEmail" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" placeholder="name@company.com" required autofocus>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="loginPass">Password</label>
            <input type="password" name="password" id="loginPass" class="form-control" placeholder="Your password" required>
          </div>
        </div>

        <div style="margin-top:1.75rem;">
          <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
            <span>Sign In</span> &rarr;
          </button>
        </div>

        <div class="text-center" style="margin-top:1.5rem; font-size:0.88rem; color:var(--slate);">
          Don't have an account yet? <a href="<?= account_url('register') ?>" class="text-gold" style="font-weight:600;">Register here</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
