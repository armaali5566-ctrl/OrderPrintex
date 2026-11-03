<?php
/**
 * OrderPrintex - Customer Profile Settings
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('account/profile.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company_name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $pincode = trim($_POST['pincode'] ?? '');

        if (empty($name) || empty($phone)) {
            set_flash('error', 'Name and phone number are required.');
        } else {
            db_update('users', [
                'name'         => $name,
                'phone'        => $phone,
                'company_name' => $company,
                'address'      => $address,
                'city'         => $city,
                'state'        => $state,
                'pincode'      => $pincode
            ], 'id = :id', ['id' => $user['id']]);

            log_activity($user['id'], 'update_profile', 'Updated profile information.');
            set_flash('success', 'Your profile details have been updated successfully.');
        }
        header('Location: ' . account_url('profile'));
        exit;

    } elseif ($action === 'change_password') {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (!password_verify($currentPass, $user['password_hash'])) {
            set_flash('error', 'Your current password was entered incorrectly.');
        } elseif (strlen($newPass) < 6) {
            set_flash('error', 'New password must be at least 6 characters long.');
        } elseif ($newPass !== $confirmPass) {
            set_flash('error', 'New password and confirmation do not match.');
        } else {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            db_update('users', ['password_hash' => $newHash], 'id = :id', ['id' => $user['id']]);
            log_activity($user['id'], 'change_password', 'Password changed successfully.');
            set_flash('success', 'Your password has been changed successfully.');
        }
        header('Location: ' . account_url('profile'));
        exit;
    }
}

$pageTitle = "My Profile & Security Settings | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#F8FAFC; padding:2.5rem 0; min-height:85vh;">
  <div class="container container-narrow">
    <div style="margin-bottom:2rem;">
      <div class="section-label">Account Preferences</div>
      <h1 style="font-size:2.2rem; color:var(--navy);">Profile &amp; Security Settings</h1>
      <p style="color:var(--slate); margin:0;">
        Manage your contact details, default delivery address, and login credentials.
      </p>
    </div>

    <?= render_flash() ?>

    <div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-light); margin-bottom:2rem; overflow-x:auto;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn">My Enquiries</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn">My Quotations</a>
      <a href="<?= account_url('orders') ?>" class="gallery-filter-btn">My Orders</a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn active">Settings</a>
    </div>

    <!-- Edit Profile Card -->
    <div class="upload-card" style="margin-bottom:2rem;">
      <h3 style="margin-bottom:1.5rem; font-size:1.35rem;">Contact &amp; Shipping Profile</h3>
      
      <form action="<?= account_url('profile') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_profile">

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group">
            <label class="form-label" for="profName">Full Name <span class="required">*</span></label>
            <input type="text" name="name" id="profName" class="form-control" value="<?= e($user['name']) ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="profEmail">Email Address (Read-only)</label>
            <input type="email" id="profEmail" class="form-control" value="<?= e($user['email']) ?>" readonly style="background-color:var(--ivory);">
          </div>

          <div class="form-group">
            <label class="form-label" for="profPhone">Phone / WhatsApp Number <span class="required">*</span></label>
            <input type="tel" name="phone" id="profPhone" class="form-control" value="<?= e($user['phone']) ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="profCompany">Company / Brand Name</label>
            <input type="text" name="company_name" id="profCompany" class="form-control" value="<?= e($user['company_name'] ?? '') ?>" placeholder="e.g. My Apparel Brand">
          </div>

        </div>

        <!-- Saved Delivery Address Section -->
        <div style="margin-top:2.25rem; border-top:1px solid var(--border-light); padding-top:1.5rem;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h4 style="font-size:1.15rem; color:var(--navy); font-weight:700; margin:0;">Saved Delivery Address</h4>
            <span class="badge" style="background:#ECFDF5; color:#065F46; font-weight:700;">Default Destination</span>
          </div>

          <?php if (!empty($user['address'])): ?>
            <div style="background:var(--ivory); border:1px solid var(--border-gold); border-radius:8px; padding:1.25rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:flex-start;">
              <div>
                <strong style="color:var(--navy); font-size:1rem; display:block; margin-bottom:0.25rem;"><?= e($user['name']) ?></strong>
                <p style="margin:0; font-size:0.88rem; color:var(--charcoal); line-height:1.6;">
                  <?= nl2br(e($user['address'])) ?><br>
                  <?= e($user['city']) ?><?= !empty($user['state']) ? ', ' . e($user['state']) : '' ?> - <?= e($user['pincode']) ?><br>
                  <span style="color:var(--slate);">Phone: <?= e($user['phone']) ?></span>
                </p>
              </div>
              <span style="font-size:0.8rem; font-weight:700; color:var(--gold);">Active for Orders</span>
            </div>
          <?php endif; ?>

          <div class="form-grid" style="margin-top:0;">
            <div class="form-group full-width">
              <label class="form-label" for="profAddress">Shipping Street Address</label>
              <input type="text" name="address" id="profAddress" class="form-control" value="<?= e($user['address'] ?? '') ?>" placeholder="Flat/House, Street, Area">
            </div>

            <div class="form-group">
              <label class="form-label" for="profCity">City</label>
              <input type="text" name="city" id="profCity" class="form-control" value="<?= e($user['city'] ?? '') ?>" placeholder="e.g. New Delhi">
            </div>

            <div class="form-group">
              <label class="form-label" for="profState">State</label>
              <input type="text" name="state" id="profState" class="form-control" value="<?= e($user['state'] ?? '') ?>" placeholder="e.g. Delhi">
            </div>

            <div class="form-group">
              <label class="form-label" for="profPin">Pincode</label>
              <input type="text" name="pincode" id="profPin" class="form-control" value="<?= e($user['pincode'] ?? '') ?>" placeholder="e.g. 110059">
            </div>
          </div>
        </div>

        <div style="margin-top:2rem; text-align:right;">
          <button type="submit" class="btn btn-primary">
            <span>Save Profile &amp; Address</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Change Password Card -->
    <div class="upload-card">
      <h3 style="margin-bottom:1.5rem; font-size:1.35rem;">Change Password</h3>

      <form action="<?= account_url('profile') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="change_password">

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group full-width">
            <label class="form-label" for="currentPass">Current Password <span class="required">*</span></label>
            <input type="password" name="current_password" id="currentPass" class="form-control" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="newPass">New Password <span class="required">*</span></label>
            <input type="password" name="new_password" id="newPass" class="form-control" minlength="6" placeholder="Min. 6 chars" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirmPass">Confirm New Password <span class="required">*</span></label>
            <input type="password" name="confirm_password" id="confirmPass" class="form-control" minlength="6" placeholder="Repeat new password" required>
          </div>
        </div>

        <div style="margin-top:2rem; text-align:right;">
          <button type="submit" class="btn btn-navy">
            <span>Update Password</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
