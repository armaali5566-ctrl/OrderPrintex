<?php
/**
 * OrderPrintex - Employee Email Verification
 * Validates cryptographically secure, time-limited verification tokens.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

$token = trim($_GET['token'] ?? '');
$email = trim(strtolower($_GET['email'] ?? ''));

$status = 'pending';
$message = '';
$userName = '';

if (empty($token) || empty($email)) {
    $status = 'error';
    $message = 'Incomplete verification link. Missing security token or email parameter.';
} else {
    $user = db_fetch_one("SELECT * FROM users WHERE email = :email LIMIT 1", ['email' => $email]);

    if (!$user) {
        $status = 'error';
        $message = 'No account found matching this email address.';
    } elseif (!empty($user['email_verified_at'])) {
        $status = 'already_verified';
        $userName = $user['name'];
        $message = 'This email address has already been verified and is in good standing.';
    } else {
        $tokenHash = hash('sha256', $token);
        $storedHash = $user['email_verification_token'] ?? '';
        $expiresAt = $user['email_verification_expires_at'] ?? '';

        if (empty($storedHash) || !hash_equals($storedHash, $tokenHash)) {
            $status = 'error';
            $message = 'Invalid security verification token. Please request a new verification link from your administrator.';
        } elseif (!empty($expiresAt) && strtotime($expiresAt) < time()) {
            $status = 'expired';
            $message = 'This verification link has expired (links expire after 48 hours). Please contact your administrator to receive a fresh verification link.';
        } else {
            // Valid token! Mark email verified and invalidate token to prevent replay
            db_update('users', [
                'email_verified_at'             => date('Y-m-d H:i:s'),
                'email_verification_token'      => null,
                'email_verification_expires_at' => null
            ], 'id = :id', ['id' => $user['id']]);

            log_activity((int)$user['id'], 'verify_email', "Employee verified email address: {$email}");

            $status = 'success';
            $userName = $user['name'];
            $message = 'Your corporate email address has been successfully verified! Your account is now fully authorized for administrative access.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification | OrderPrintex</title>
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/animations.css') ?>">
  <style>
    body {
      background: #0D0D11;
      color: #F3F4F6;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 1.5rem;
    }
    .verify-card {
      background: #141419;
      border: 1px solid rgba(212, 175, 55, 0.35);
      border-radius: 14px;
      max-width: 500px;
      width: 100%;
      padding: 2.5rem 2rem;
      text-align: center;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85);
      position: relative;
    }
    .verify-icon-wrap {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 2.2rem;
      margin-bottom: 1.25rem;
    }
    .icon-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1.5px solid #10B981;
      color: #10B981;
      box-shadow: 0 0 20px rgba(16, 185, 129, 0.25);
    }
    .icon-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1.5px solid #EF4444;
      color: #EF4444;
      box-shadow: 0 0 20px rgba(239, 68, 68, 0.25);
    }
    .icon-warning {
      background: rgba(245, 158, 11, 0.15);
      border: 1.5px solid #F59E0B;
      color: #F59E0B;
      box-shadow: 0 0 20px rgba(245, 158, 11, 0.25);
    }
    h1 {
      margin: 0 0 0.5rem;
      font-size: 1.5rem;
      color: #D4AF37;
      font-weight: 700;
    }
    p {
      color: #94A3B8;
      font-size: 0.95rem;
      line-height: 1.6;
      margin: 0 0 2rem;
    }
    .btn-verify {
      display: inline-block;
      background: #D4AF37;
      color: #0D0D11;
      font-weight: 700;
      padding: 0.75rem 2rem;
      border-radius: 8px;
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.2s ease;
      box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3);
    }
    .btn-verify:hover {
      background: #E5C158;
      transform: translateY(-1px);
    }
  </style>
</head>
<body>

  <div class="verify-card">
    <div style="margin-bottom: 1.25rem;">
      <img src="<?= url('assets/images/logo.png') ?>" alt="OrderPrintex" style="height:44px; width:44px; border-radius:50%; object-fit:cover; border:1.5px solid rgba(212,175,55,0.7);">
    </div>

    <?php if ($status === 'success'): ?>
      <div class="verify-icon-wrap icon-success">&#x2713;</div>
      <h1>Email Verified!</h1>
      <p>Welcome, <strong style="color: #F3F4F6;"><?= e($userName) ?></strong>.<br><?= e($message) ?></p>
      <a href="<?= url('admin/login.php') ?>" class="btn-verify">Proceed to Admin Console &rarr;</a>
    <?php elseif ($status === 'already_verified'): ?>
      <div class="verify-icon-wrap icon-success">&#x2713;</div>
      <h1>Already Verified</h1>
      <p>Hello <strong style="color: #F3F4F6;"><?= e($userName) ?></strong>.<br><?= e($message) ?></p>
      <a href="<?= url('admin/login.php') ?>" class="btn-verify">Go to Admin Login &rarr;</a>
    <?php elseif ($status === 'expired'): ?>
      <div class="verify-icon-wrap icon-warning">&#x26A0;</div>
      <h1>Link Expired</h1>
      <p><?= e($message) ?></p>
      <a href="<?= url('admin/login.php') ?>" class="btn-verify">Back to Login</a>
    <?php else: ?>
      <div class="verify-icon-wrap icon-error">&#x2715;</div>
      <h1>Verification Failed</h1>
      <p><?= e($message) ?></p>
      <a href="<?= url('admin/login.php') ?>" class="btn-verify">Return to Login</a>
    <?php endif; ?>
  </div>

</body>
</html>

