<?php
/**
 * OrderPrintex - Customer Profile Settings
 * Pure Enquiry & Quotation Workflow
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_auth();
$user = auth_user();
$uid = $user['id'];
$uemail = $user['email'];

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

$pendingQuotesCount = (int)(db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE (user_id = :uid OR customer_email = :email) AND status = 'sent'", ['uid' => $uid, 'email' => $uemail])['cnt'] ?? 0);

$pageTitle = "My Profile & Security Settings | OrderPrintex";
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background-color:#080808; padding:2.5rem 0 4rem; min-height:85vh; color:#F5F1E8;">
  <div class="container container-narrow">
    <div style="margin-bottom:2rem;">
      <div class="section-label" style="color:#D4AF37; margin-bottom:0.25rem;">Account Preferences</div>
      <h1 style="font-size:clamp(1.75rem, 3vw, 2.25rem); color:#F5F1E8; margin:0 0 0.35rem; font-weight:800; font-family:var(--font-serif);">
        Profile &amp; Security Settings
      </h1>
      <p style="color:#B8B1A3; margin:0; font-size:0.95rem;">
        Manage your contact details, commercial billing address, and account credentials.
      </p>
    </div>

    <?= render_flash() ?>

    <!-- Sub-navigation Tabs -->
    <div style="display:flex; gap:0.5rem; border-bottom:1px solid #2A2415; margin-bottom:2rem; overflow-x:auto; padding-bottom:2px;">
      <a href="<?= account_url() ?>" class="gallery-filter-btn" style="text-decoration:none;">Overview</a>
      <a href="<?= account_url('enquiries') ?>" class="gallery-filter-btn" style="text-decoration:none;">My Enquiries</a>
      <a href="<?= account_url('quotations') ?>" class="gallery-filter-btn" style="text-decoration:none;">
        My Quotations <?= $pendingQuotesCount > 0 ? '<span style="background:#D4AF37; color:#080808; padding:2px 7px; border-radius:10px; font-size:0.75rem; font-weight:800; margin-left:4px;">' . $pendingQuotesCount . '</span>' : '' ?>
      </a>
      <a href="<?= account_url('profile') ?>" class="gallery-filter-btn active" style="text-decoration:none;">Settings</a>
    </div>

    <!-- Edit Profile Card -->
    <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:2rem; margin-bottom:2rem;">
      <h3 style="font-size:1.25rem; color:#F5F1E8; margin:0 0 1.25rem; font-weight:700;">Contact &amp; Business Profile</h3>
      
      <form action="<?= account_url('profile') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_profile">

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group">
            <label class="form-label" for="profName" style="color:#B8B1A3;">Full Name <span class="required">*</span></label>
            <input type="text" name="name" id="profName" class="form-control" value="<?= e($user['name']) ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="profEmail" style="color:#B8B1A3;">Email Address (Primary Login)</label>
            <input type="email" id="profEmail" class="form-control" value="<?= e($user['email']) ?>" readonly style="background-color:#181818; opacity:0.7; cursor:not-allowed;">
          </div>

          <div class="form-group">
            <label class="form-label" for="profPhone" style="color:#B8B1A3;">Phone / WhatsApp Number <span class="required">*</span></label>
            <input type="tel" name="phone" id="profPhone" class="form-control" value="<?= e($user['phone']) ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="profCompany" style="color:#B8B1A3;">Company / Brand Name</label>
            <input type="text" name="company_name" id="profCompany" class="form-control" value="<?= e($user['company_name'] ?? '') ?>" placeholder="e.g. My Apparel Brand">
          </div>
        </div>

        <!-- Saved Commercial Address Section -->
        <div style="margin-top:2rem; border-top:1px solid #2A2415; padding-top:1.5rem;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h4 style="font-size:1.1rem; color:#F5F1E8; font-weight:700; margin:0;">Default Dispatch Address</h4>
            <span style="background:rgba(212,175,55,0.12); color:#D4AF37; border:1px solid #40351E; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:4px;">
              Primary
            </span>
          </div>

          <?php if (!empty($user['address'])): ?>
            <div style="background:#181818; border:1px solid #40351E; border-radius:6px; padding:1.25rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:flex-start;">
              <div>
                <strong style="color:#FFD76A; font-size:0.95rem; display:block; margin-bottom:0.25rem;"><?= e($user['name']) ?></strong>
                <p style="margin:0; font-size:0.88rem; color:#B8B1A3; line-height:1.6;">
                  <?= nl2br(e($user['address'])) ?><br>
                  <?= e($user['city']) ?><?= !empty($user['state']) ? ', ' . e($user['state']) : '' ?> - <?= e($user['pincode']) ?><br>
                  <span style="color:#8A8275;">Contact: <?= e($user['phone']) ?></span>
                </p>
              </div>
            </div>
          <?php endif; ?>

          <div class="form-grid" style="margin-top:0;">
            <div class="form-group full-width">
              <label class="form-label" for="profAddress" style="color:#B8B1A3;">Street Address</label>
              <input type="text" name="address" id="profAddress" class="form-control" value="<?= e($user['address'] ?? '') ?>" placeholder="Unit, Floor, Building, Industrial Area">
            </div>

            <div class="form-group">
              <label class="form-label" for="profCity" style="color:#B8B1A3;">City</label>
              <input type="text" name="city" id="profCity" class="form-control" value="<?= e($user['city'] ?? '') ?>" placeholder="e.g. New Delhi">
            </div>

            <div class="form-group">
              <label class="form-label" for="profState" style="color:#B8B1A3;">State</label>
              <input type="text" name="state" id="profState" class="form-control" value="<?= e($user['state'] ?? '') ?>" placeholder="e.g. Delhi">
            </div>

            <div class="form-group">
              <label class="form-label" for="profPin" style="color:#B8B1A3;">Pincode</label>
              <input type="text" name="pincode" id="profPin" class="form-control" value="<?= e($user['pincode'] ?? '') ?>" placeholder="e.g. 110059">
            </div>
          </div>
        </div>

        <div style="margin-top:2rem; text-align:right;">
          <button type="submit" class="btn btn-primary" style="padding:0.65rem 1.4rem;">
            Save Profile Details
          </button>
        </div>
      </form>
    </div>

    <!-- Change Password Card -->
    <div style="background:#121212; border:1px solid #2A2415; border-radius:8px; padding:2rem;">
      <h3 style="font-size:1.25rem; color:#F5F1E8; margin:0 0 1.25rem; font-weight:700;">Change Account Password</h3>

      <form action="<?= account_url('profile') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="change_password">

        <div class="form-grid" style="margin-top:0;">
          <div class="form-group full-width">
            <label class="form-label" for="currentPass" style="color:#B8B1A3;">Current Password <span class="required">*</span></label>
            <input type="password" name="current_password" id="currentPass" class="form-control" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="newPass" style="color:#B8B1A3;">New Password <span class="required">*</span></label>
            <input type="password" name="new_password" id="newPass" class="form-control" minlength="6" placeholder="Min. 6 chars" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirmPass" style="color:#B8B1A3;">Confirm New Password <span class="required">*</span></label>
            <input type="password" name="confirm_password" id="confirmPass" class="form-control" minlength="6" placeholder="Repeat new password" required>
          </div>
        </div>

        <div style="margin-top:1.75rem; text-align:right;">
          <button type="submit" class="btn btn-outline" style="padding:0.65rem 1.4rem;">
            Update Password
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
