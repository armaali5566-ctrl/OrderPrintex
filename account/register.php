<?php
/**
 * OrderPrintex - Customer Registration
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirect if already logged in
if (auth_check()) {
    header('Location: ' . account_url());
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Security token expired. Please try again.';
    } else {
        $result = auth_register($_POST);
        if ($result['success']) {
            set_flash('success', 'Your OrderPrintex customer account has been created successfully!');
            header('Location: ' . account_url());
            exit;
        } else {
            $errors[] = $result['error'];
        }
    }
}

$pageTitle = "Create Customer Account | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section section-ivory" style="min-height:80vh; display:flex; align-items:center;">
  <div class="container container-narrow">
    <div class="upload-card reveal scale-in" style="max-width:580px; margin:0 auto; padding:2.5rem;">
      <div class="text-center" style="margin-bottom:2rem;">
        <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:44px; width:auto; margin:0 auto 1.25rem; display:block;">
        <div class="section-label" style="justify-content:center;">Client Portal</div>
        <h2>Create an Account</h2>
        <p style="font-size:0.9rem; color:var(--slate);">
          Manage your artwork files, track quotations, and follow production progress in real-time.
        </p>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <div class="alert-content">
            <?php foreach ($errors as $err): ?>
              <div>&bull; <?= e($err) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <form action="<?= account_url('register') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group full-width">
            <label class="form-label" for="regName">Full Name <span class="required">*</span></label>
            <input type="text" name="name" id="regName" class="form-control" value="<?= e($_POST['name'] ?? '') ?>" placeholder="e.g. Vikram Mehta" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="regEmail">Email Address <span class="required">*</span></label>
            <input type="email" name="email" id="regEmail" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" placeholder="e.g. vikram@brand.com" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="regPhone">Phone Number <span class="required">*</span></label>
            <input type="tel" name="phone" id="regPhone" class="form-control" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="e.g. +91 93540 28199" required>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="regPass">Account Password <span class="required">*</span></label>
            <input type="password" name="password" id="regPass" class="form-control" placeholder="Minimum 6 characters" minlength="6" required>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="regCompany">Company / Brand Name (Optional)</label>
            <input type="text" name="company_name" id="regCompany" class="form-control" value="<?= e($_POST['company_name'] ?? '') ?>" placeholder="e.g. Mehta Apparel Studio">
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="regAddress">Shipping Street Address</label>
            <input type="text" name="address" id="regAddress" class="form-control" value="<?= e($_POST['address'] ?? '') ?>" placeholder="Street, building, area">
          </div>

          <div class="form-group">
            <label class="form-label" for="regCity">City</label>
            <input type="text" name="city" id="regCity" class="form-control" value="<?= e($_POST['city'] ?? '') ?>" placeholder="e.g. New Delhi">
          </div>

          <div class="form-group">
            <label class="form-label" for="regPin">Pincode</label>
            <input type="text" name="pincode" id="regPin" class="form-control" value="<?= e($_POST['pincode'] ?? '') ?>" placeholder="e.g. 110059">
          </div>
        </div>

        <div style="margin-top:2rem;">
          <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
            <span>Create Customer Account</span> &rarr;
          </button>
        </div>

        <div class="text-center" style="margin-top:1.5rem; font-size:0.88rem; color:var(--slate);">
          Already have an account? <a href="<?= account_url('login') ?>" class="text-gold" style="font-weight:600;">Sign in here</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
