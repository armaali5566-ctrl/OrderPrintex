<?php
/**
 * OrderPrintex - Authentication & Role-Based Authorization
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Check if a user is currently logged in
 */
function auth_check(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Get current logged in user details
 */
function auth_user(): ?array {
    if (!auth_check()) {
        return null;
    }
    static $currentUser = null;
    if ($currentUser === null) {
        $currentUser = db_fetch_one("SELECT * FROM users WHERE id = :id AND status = 'active'", [
            'id' => $_SESSION['user_id']
        ]);
        if (!$currentUser) {
            // User deleted or suspended
            auth_logout();
            return null;
        }
    }
    return $currentUser;
}

/**
 * Check if the logged-in user is an admin
 */
function auth_is_admin(): bool {
    $user = auth_user();
    return $user && ($user['role'] === 'admin');
}

/**
 * Check if the logged-in user is a staff member
 */
function auth_is_staff(): bool {
    $user = auth_user();
    return $user && ($user['role'] === 'staff');
}

/**
 * Check if the logged-in user is staff or admin
 */
function auth_is_staff_or_admin(): bool {
    $user = auth_user();
    return $user && in_array($user['role'] ?? '', ['admin', 'staff'], true);
}

/**
 * Check if current user has any of the specified roles
 */
function auth_has_role(string|array $roles): bool {
    $user = auth_user();
    if (!$user) {
        return false;
    }
    $roles = (array) $roles;
    return in_array($user['role'] ?? '', $roles, true);
}

/**
 * Attempt user login
 */
function auth_login(string $email, string $password): array {
    $cleanEmail = trim(strtolower($email));
    if (empty($cleanEmail) || empty($password)) {
        return ['success' => false, 'error' => 'Please provide both email and password.'];
    }

    $user = db_fetch_one("SELECT * FROM users WHERE email = :email LIMIT 1", [
        'email' => $cleanEmail
    ]);

    if (!$user) {
        return ['success' => false, 'error' => 'Invalid email address or password.'];
    }

    if ($user['status'] !== 'active') {
        return ['success' => false, 'error' => 'Your account is currently inactive. Please contact support.'];
    }

    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Invalid email address or password.'];
    }

    // Check if password needs rehash with modern cost
    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        db_update('users', ['password_hash' => $newHash], 'id = :id', ['id' => $user['id']]);
    }

    // Regenerate session ID to prevent session fixation attacks
    session_regenerate_id(true);

    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role'];

    log_activity($user['id'], 'user_login', 'User logged in successfully.');

    return ['success' => true, 'user' => $user];
}

/**
 * Register a new customer
 */
function auth_register(array $data): array {
    $name = trim($data['name'] ?? '');
    $email = trim(strtolower($data['email'] ?? ''));
    $phone = trim($data['phone'] ?? '');
    $password = $data['password'] ?? '';
    $company = trim($data['company_name'] ?? '');
    $address = trim($data['address'] ?? '');
    $city = trim($data['city'] ?? '');
    $state = trim($data['state'] ?? '');
    $pincode = trim($data['pincode'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        return ['success' => false, 'error' => 'Name, email, phone number, and password are required.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Please enter a valid email address.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters long.'];
    }

    // Check existing email
    $existing = db_fetch_one("SELECT id FROM users WHERE email = :email LIMIT 1", ['email' => $email]);
    if ($existing) {
        return ['success' => false, 'error' => 'An account with this email address already exists.'];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $userId = db_insert('users', [
        'name'          => $name,
        'email'         => $email,
        'password_hash' => $passwordHash,
        'phone'         => $phone,
        'company_name'  => $company,
        'address'       => $address,
        'city'          => $city,
        'state'         => $state,
        'pincode'       => $pincode,
        'role'          => 'customer',
        'status'        => 'active'
    ]);

    if (!$userId) {
        return ['success' => false, 'error' => 'Database error while creating your account.'];
    }

    // Automatically log the newly registered customer in
    session_regenerate_id(true);
    $_SESSION['user_id']    = $userId;
    $_SESSION['user_name']  = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role']  = 'customer';

    log_activity($userId, 'user_register', 'Customer registered account.');

    return ['success' => true, 'user_id' => $userId];
}

/**
 * Logout
 */
function auth_logout(): void {
    if (auth_check()) {
        log_activity($_SESSION['user_id'] ?? null, 'user_logout', 'User logged out.');
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Require authentication guard
 */
function require_auth(string $redirectTo = 'account/login.php'): void {
    if (!auth_check()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
        set_flash('info', 'Please sign in to access this page.');
        header('Location: ' . url($redirectTo));
        exit;
    }
}

/**
 * Display a dedicated Access Denied (403) page when permissions are insufficient
 */
function auth_access_denied(string $message = 'You do not have sufficient permissions to access this administrative resource.'): void {
    http_response_code(403);
    $adminUser = auth_user();
    $adminTitle = "403 - Access Denied";
    $denialReason = $message;

    $deniedTemplate = __DIR__ . '/../admin/access_denied.php';
    if (file_exists($deniedTemplate)) {
        require $deniedTemplate;
        exit;
    }

    // Direct luxury dark fallback if template file is absent
    ?>
    <!DOCTYPE html>
    <html lang="en" data-theme="dark">
    <head>
      <meta charset="UTF-8">
      <title>403 Forbidden | OrderPrintex</title>
      <style>
        body { background: #0D0D11; color: #F3F4F6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 1.5rem; }
        .box { background: #16161D; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; padding: 2.5rem; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .icon { width: 56px; height: 56px; border-radius: 50%; background: rgba(239,68,68,0.15); border: 1.5px solid #EF4444; color: #EF4444; font-size: 26px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        h1 { color: #D4AF37; margin: 0 0 0.5rem; font-size: 1.5rem; }
        p { color: #94A3B8; font-size: 0.92rem; line-height: 1.5; margin: 0 0 1.5rem; }
        .btn { display: inline-block; background: #D4AF37; color: #0D0D11; font-weight: 700; padding: 0.65rem 1.5rem; border-radius: 6px; text-decoration: none; font-size: 0.88rem; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.9; }
      </style>
    </head>
    <body>
      <div class="box">
        <div class="icon">&#x1F6AB;</div>
        <h1>403 - Access Denied</h1>
        <p><?= htmlspecialchars($denialReason) ?></p>
        <a href="<?= url('admin/dashboard.php') ?>" class="btn">&larr; Return to Dashboard</a>
      </div>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Require general Admin Panel access (allows both 'admin' and 'staff' roles)
 */
function require_admin_panel(string $redirectTo = 'admin/login.php'): void {
    if (!auth_check()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
        set_flash('info', 'Please sign in with staff or administrative credentials.');
        header('Location: ' . url($redirectTo));
        exit;
    }
    if (!auth_is_staff_or_admin()) {
        auth_access_denied('Administrator or Staff credentials are required to access the OrderPrintex Console.');
    }
}

/**
 * Strict Super Administrator guard (allows ONLY 'admin').
 * Staff accounts attempting to access restricted pages receive a 403 Access Denied response.
 */
function require_admin(string $redirectTo = 'admin/login.php'): void {
    if (!auth_check()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
        set_flash('info', 'Please sign in to access the Admin Console.');
        header('Location: ' . url($redirectTo));
        exit;
    }
    $user = auth_user();
    if (!$user || !in_array($user['role'] ?? '', ['admin', 'staff'], true)) {
        header('Location: ' . url($redirectTo));
        exit;
    }
    if ($user['role'] !== 'admin') {
        auth_access_denied('Full Super Administrator privileges are required to access this section. Staff accounts are restricted from modifying personnel, website settings, or security audit logs.');
    }
}

/**
 * Require specific role(s)
 */
function require_role(string|array $roles, string $message = ''): void {
    require_admin_panel();
    if (!auth_has_role($roles)) {
        auth_access_denied($message ?: 'Your assigned role does not grant permission to view or manage this module.');
    }
}
