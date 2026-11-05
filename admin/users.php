<?php
/**
 * OrderPrintex - Enterprise Personnel Management & Granular Staff Permissions
 * Multi-role assignments, department workflows, custom permissions matrix, and secure credentials management.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/permissions.php';
require_once __DIR__ . '/../includes/mailer.php';

// Strict Super Admin & Personnel Management Guard
require_permission('personnel.view');
$adminUser = auth_user();
$isSuperAdmin = user_is_super_admin((int) $adminUser['id']);

// Helper: Check if user is the last active Super Admin
function is_last_active_super_admin(int $userId): bool {
    if (!user_is_super_admin($userId)) {
        return false;
    }
    $activeCount = (int) (db_fetch_one("
        SELECT COUNT(DISTINCT u.id) as cnt
        FROM users u
        LEFT JOIN admin_user_roles ur ON u.id = ur.user_id
        LEFT JOIN admin_roles r ON ur.role_id = r.id
        WHERE (u.role = 'admin' OR r.slug = 'super_admin') AND u.status = 'active'
    ")['cnt'] ?? 0);
    return $activeCount <= 1;
}

// ============================================================================
// HANDLE POST ACTIONS
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Security token expired. Please refresh the page.']);
            exit;
        }
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // Require personnel management capability
    require_permission('personnel.manage');
    $action = $_POST['action'];

    // ------------------------------------------------------------------------
    // ACTION 1: CREATE NEW PERSONNEL ACCOUNT
    // ------------------------------------------------------------------------
    if ($action === 'create_personnel') {
        $name        = trim($_POST['name'] ?? '');
        $email       = trim(strtolower($_POST['email'] ?? ''));
        $phone       = trim($_POST['phone'] ?? '');
        $department  = trim($_POST['department'] ?? 'General Operations');
        $customDept  = trim($_POST['custom_department'] ?? '');
        if (strtolower($department) === 'other' || !empty($customDept)) {
            if (!empty($customDept)) {
                $department = $customDept;
            } else {
                $_SESSION['form_old_input'] = $_POST;
                set_flash('error', 'Please specify a custom department name.');
                header('Location: ' . url('admin/users.php'));
                exit;
            }
        }
        $password    = $_POST['password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';
        $roleIds     = array_map('intval', $_POST['roles'] ?? []);
        $customPerms = $_POST['custom_permissions'] ?? [];
        $status      = in_array($_POST['status'] ?? '', ['active', 'suspended'], true) ? $_POST['status'] : 'active';

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['form_old_input'] = $_POST;
            set_flash('error', 'Full Name, Email Address, and Initial Password are required.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        if (!is_valid_email_strict($email)) {
            $_SESSION['form_old_input'] = $_POST;
            set_flash('error', 'Please enter a valid, well-formed corporate email address with a valid domain.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Indian Mobile Number Validation
        if (!empty($phone)) {
            $normalizedPhone = validate_indian_mobile($phone);
            if (!$normalizedPhone) {
                $_SESSION['form_old_input'] = $_POST;
                set_flash('error', 'Invalid Indian Mobile Number: Must be exactly 10 digits starting with 6, 7, 8, or 9 (e.g. 9876543210).');
                header('Location: ' . url('admin/users.php'));
                exit;
            }
            $phone = $normalizedPhone;
        }

        if (strlen($password) < 8) {
            $_SESSION['form_old_input'] = $_POST;
            set_flash('error', 'Password must be at least 8 characters long.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        if ($password !== $confirmPass) {
            $_SESSION['form_old_input'] = $_POST;
            set_flash('error', 'Password confirmation does not match.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $exists = db_fetch_one("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($exists) {
            $_SESSION['form_old_input'] = $_POST;
            set_flash('error', 'A user account with this email address already exists.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Determine base system role
        $superAdminRoleId = (int) (db_fetch_one("SELECT id FROM admin_roles WHERE slug = 'super_admin'")['id'] ?? 0);
        $hasSuperAdminRole = in_array($superAdminRoleId, $roleIds, true);
        $baseRole = $hasSuperAdminRole ? 'admin' : 'staff';

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $newUserId = db_insert('users', [
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'department'    => $department,
            'password_hash' => $hash,
            'role'          => $baseRole,
            'status'        => $status
        ]);

        if ($newUserId) {
            sync_user_roles_and_permissions((int) $newUserId, $roleIds, $customPerms, $department);
            log_activity($adminUser['id'], 'create_personnel', "Provisioned new {$baseRole} account for {$email} ({$department})");

            // Dispatch email verification with SMTP configuration awareness
            $mailResult = mailer_send_verification_email((int)$newUserId);
            if ($mailResult['sent']) {
                set_flash('success', "Personnel account for '{$name}' created successfully! Verification email dispatched to {$email}.");
            } else {
                $missingStr = !empty($mailResult['missing_settings']) ? implode(', ', $mailResult['missing_settings']) : 'SMTP Credentials';
                set_flash('warning', "Personnel account for '{$name}' created! (Notice: SMTP is not configured on this server — Missing: {$missingStr}. Verification token generated and link available for verification).");
            }
        } else {
            set_flash('error', 'Database error creating personnel account.');
        }

        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // ------------------------------------------------------------------------
    // ACTION 2: EDIT PERSONNEL PROFILE & PERMISSIONS
    // ------------------------------------------------------------------------
    if ($action === 'edit_personnel') {
        $userId      = intval($_POST['user_id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $email       = trim(strtolower($_POST['email'] ?? ''));
        $phone       = trim($_POST['phone'] ?? '');
        $department  = trim($_POST['department'] ?? 'General Operations');
        $customDept  = trim($_POST['custom_department'] ?? '');
        if (strtolower($department) === 'other' || !empty($customDept)) {
            if (!empty($customDept)) {
                $department = $customDept;
            } else {
                set_flash('error', 'Please specify a custom department name.');
                header('Location: ' . url('admin/users.php'));
                exit;
            }
        }
        $roleIds     = array_map('intval', $_POST['roles'] ?? []);
        $customPerms = $_POST['custom_permissions'] ?? [];

        if (empty($name) || empty($email)) {
            set_flash('error', 'Personnel Name and Email Address are required.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Safeguard 0: Logged-in Super Admin self-management is disallowed
        if ($userId === (int)$adminUser['id']) {
            set_flash('error', 'Security Policy: Super Administrators cannot modify their own roles or administrative permissions via personnel management. Another Super Admin must perform administrative modifications.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        if (!is_valid_email_strict($email)) {
            set_flash('error', 'Please enter a valid, well-formed corporate email address.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Indian Mobile Number Validation
        if (!empty($phone)) {
            $normalizedPhone = validate_indian_mobile($phone);
            if (!$normalizedPhone) {
                set_flash('error', 'Invalid Indian Mobile Number: Must be exactly 10 digits starting with 6, 7, 8, or 9 (e.g. 9876543210).');
                header('Location: ' . url('admin/users.php'));
                exit;
            }
            $phone = $normalizedPhone;
        }

        $target = db_fetch_one("SELECT * FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $userId]);
        if (!$target) {
            set_flash('error', 'Personnel account not found.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Email uniqueness check
        $emailCheck = db_fetch_one("SELECT id FROM users WHERE email = :email AND id != :id", [
            'email' => $email,
            'id'    => $userId
        ]);
        if ($emailCheck) {
            set_flash('error', 'Another account is already using that email address.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $superAdminRoleId = (int) (db_fetch_one("SELECT id FROM admin_roles WHERE slug = 'super_admin'")['id'] ?? 0);
        $willHaveSuperRole = in_array($superAdminRoleId, $roleIds, true);

        // Safeguard 1: Administrator cannot revoke their own Super Admin role
        if ($userId === (int)$adminUser['id'] && user_is_super_admin($userId) && !$willHaveSuperRole) {
            set_flash('error', 'Security Violation: You cannot revoke your own Super Administrator role.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Safeguard 2: Cannot demote the last remaining active Super Admin
        if (user_is_super_admin($userId) && !$willHaveSuperRole && is_last_active_super_admin($userId)) {
            set_flash('error', 'Security Protection: Cannot demote the last remaining active Super Administrator.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $newBaseRole = $willHaveSuperRole ? 'admin' : 'staff';
        db_update('users', [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'department' => $department,
            'role'       => $newBaseRole
        ], 'id = :id', ['id' => $userId]);

        sync_user_roles_and_permissions($userId, $roleIds, $customPerms, $department);

        log_activity($adminUser['id'], 'edit_personnel', "Updated personnel profile and permissions for {$email}");
        set_flash('success', "Personnel profile for '{$name}' updated successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // ------------------------------------------------------------------------
    // ACTION 3: SECURE PASSWORD RESET
    // ------------------------------------------------------------------------
    if ($action === 'reset_password') {
        $userId      = intval($_POST['user_id'] ?? 0);
        $newPass     = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (strlen($newPass) < 8) {
            set_flash('error', 'Password must be at least 8 characters long.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        if ($newPass !== $confirmPass) {
            set_flash('error', 'Password confirmation does not match.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $target = db_fetch_one("SELECT id, name, email FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $userId]);
        if (!$target) {
            set_flash('error', 'Personnel account not found.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
        db_update('users', ['password_hash' => $newHash], 'id = :id', ['id' => $userId]);

        log_activity($adminUser['id'], 'reset_password', "Reset security credentials for {$target['email']}");
        set_flash('success', "Password for '{$target['name']}' has been updated securely.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // ------------------------------------------------------------------------
    // ACTION 4: TOGGLE ACCOUNT STATUS (ACTIVATE / SUSPEND)
    // ------------------------------------------------------------------------
    if ($action === 'toggle_status') {
        $targetId = intval($_POST['user_id'] ?? 0);
        $isAjax   = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($targetId === (int)$adminUser['id']) {
            $msg = 'Security Policy Violation: Self-suspension or deactivation of the active administrative session is strictly prohibited.';
            if ($isAjax) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $target = db_fetch_one("SELECT id, name, email, role, status FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $targetId]);
        if (!$target) {
            $msg = 'Personnel account not found.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Safeguard: Protect the last active Super Admin
        if ($target['status'] === 'active' && is_last_active_super_admin($targetId)) {
            $msg = 'Security Protection: Cannot suspend the last remaining active Super Administrator.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $newStatus = ($target['status'] === 'active') ? 'suspended' : 'active';
        db_update('users', ['status' => $newStatus], 'id = :id', ['id' => $targetId]);
        log_activity($adminUser['id'], 'toggle_status', "Changed status of {$target['email']} to {$newStatus}");

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success'    => true,
                'user_id'    => $targetId,
                'new_status' => $newStatus,
                'label'      => ucfirst($newStatus),
                'badge_html' => '<span class="badge ' . ($newStatus === 'active' ? 'badge-success' : 'badge-danger') . '" style="font-size:0.75rem; padding:4px 10px;">' . ucfirst($newStatus) . '</span>',
                'btn_text'   => ($newStatus === 'active' ? 'Suspend' : 'Activate'),
                'message'    => "Account for '{$target['name']}' has been " . ($newStatus === 'active' ? 'activated' : 'suspended') . " successfully."
            ]);
            exit;
        }

        set_flash('success', "Account status for '{$target['name']}' changed to " . ucfirst($newStatus) . ".");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // ------------------------------------------------------------------------
    // ACTION 5: CREATE CUSTOM ROLE
    // ------------------------------------------------------------------------
    if ($action === 'create_custom_role') {
        $roleName  = trim($_POST['role_name'] ?? '');
        $roleDept  = trim($_POST['role_department'] ?? 'Custom Operations');
        $roleDesc  = trim($_POST['role_description'] ?? '');
        $permCodes = $_POST['permissions'] ?? [];

        if (empty($roleName)) {
            set_flash('error', 'Role Name is required.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $slug = 'custom_' . preg_replace('/[^a-z0-9_]+/i', '_', strtolower($roleName));
        $existing = db_fetch_one("SELECT id FROM admin_roles WHERE slug = :slug", ['slug' => $slug]);
        if ($existing) {
            set_flash('error', 'A role with a similar identifier already exists.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $roleId = db_insert('admin_roles', [
            'slug'        => $slug,
            'name'        => $roleName,
            'department'  => $roleDept,
            'description' => $roleDesc,
            'is_system'   => 0
        ]);

        if ($roleId && !empty($permCodes)) {
            $inCodes = implode("','", array_map('addslashes', $permCodes));
            $permRecords = db_fetch_all("SELECT id FROM admin_permissions WHERE code IN ('{$inCodes}')") ?: [];
            foreach ($permRecords as $pr) {
                db_insert('admin_role_permissions', [
                    'role_id'       => $roleId,
                    'permission_id' => (int) $pr['id']
                ]);
            }
        }

        log_activity($adminUser['id'], 'create_custom_role', "Created custom role '{$roleName}' with " . count($permCodes) . " permissions");
        set_flash('success', "Custom role '{$roleName}' created successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }
}

// ============================================================================
// DATA GATHERING FOR DIRECTORY & FORMS
// ============================================================================
$adminStaffUsers = db_fetch_all("
    SELECT id, name, email, phone, department, role, status, email_verified_at, email_verification_token, created_at,
           (SELECT MAX(created_at) FROM activity_logs WHERE user_id = users.id) as last_active
    FROM users 
    WHERE role IN ('admin', 'staff')
    ORDER BY role ASC, id ASC
") ?: [];
$admins = $adminStaffUsers; // Compatibility guarantee

$oldInput = $_SESSION['form_old_input'] ?? [];
unset($_SESSION['form_old_input']);

$allRoles = get_all_roles_with_permissions();
$groupedPermissions = get_all_grouped_permissions();

// Gather role & permission mappings for each user
$userRoleMap  = [];
$userPermsMap = [];
foreach ($adminStaffUsers as $u) {
    $uId = (int) $u['id'];
    $userRoleMap[$uId]  = user_get_roles($uId);
    $userPermsMap[$uId] = user_get_permissions($uId);
}

$adminTitle = "Admin Personnel & Role Management";
require_once __DIR__ . '/header.php';
?>

<!-- Topbar Header -->
<div class="admin-topbar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div>
        <h1 class="admin-page-title" style="margin: 0 0 0.35rem;">Admin Personnel &amp; Granular Permissions</h1>
        <p class="admin-page-subtitle" style="margin: 0;">Configure employee accounts with tailored responsibilities, work-based roles, custom permission matrices, and security controls.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <button type="button" class="btn btn-sm btn-outline" onclick="openCustomRoleModal()" style="color: var(--admin-gold, #D4AF37); border-color: rgba(212, 175, 55, 0.4);">
            + Create Custom Role
        </button>
        <a href="<?= url('admin/activity.php') ?>" class="btn btn-sm btn-outline">
            Security Logs &rarr;
        </a>
    </div>
</div>

<?= render_flash() ?>

<!-- ========================================================================= -->
<!-- 1. FULL-WIDTH REDESIGNED CREATE PERSONNEL FORM (AT TOP) -->
<!-- ========================================================================= -->
<div class="admin-card" style="margin-bottom: 2.25rem;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.18)); padding: 1.25rem 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h2 class="admin-card-title" style="margin: 0 0 0.2rem; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">
                + Provision New Personnel Account
            </h2>
            <p style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
                Assign dedicated work-based roles or construct a customized permission set
            </p>
        </div>
        <span class="badge" style="background: rgba(212, 175, 55, 0.15); color: var(--admin-gold, #D4AF37); border: 1px solid rgba(212, 175, 55, 0.3); font-size: 0.75rem; padding: 4px 10px;">
            <?= count($allRoles) ?> Work Roles Available
        </span>
    </div>

    <div class="admin-card-body" style="padding: 1.75rem 1.5rem;">
        <form method="POST" action="<?= url('admin/users.php') ?>" id="createPersonnelForm" onsubmit="return validateCreatePersonnelForm(event)">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create_personnel">

            <!-- Section 1: Basic Identity (4-Column Desktop Grid) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Full Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="e.g. Alex Morgan" value="<?= e($oldInput['name'] ?? '') ?>" style="width: 100%;">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Corporate Email</label>
                    <input type="email" name="email" id="createEmailInput" class="form-input" required placeholder="alex@orderprintex.com" value="<?= e($oldInput['email'] ?? '') ?>" style="width: 100%;" oninput="validateEmailInput(this, 'createEmailError')">
                    <span id="createEmailError" class="field-error-msg" style="display: none; color: #EF4444; font-size: 0.74rem; margin-top: 4px; font-weight: 600;">
                        Please enter a valid email address with a valid domain.
                    </span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">
                        Indian Mobile <span style="font-weight: 400; color: #94A3B8; font-size: 0.75rem;">(10 digits)</span>
                    </label>
                    <div style="display: flex; align-items: center; width: 100%;">
                        <span style="background: #1B1B26; border: 1px solid rgba(255,255,255,0.1); border-right: none; border-radius: 6px 0 0 6px; padding: 0.65rem 0.75rem; font-size: 0.85rem; color: var(--admin-gold, #D4AF37); font-weight: 700; user-select: none;">+91</span>
                        <input type="text" name="phone" id="createPhoneInput" class="form-input" placeholder="9876543210" maxlength="15" value="<?= e($oldInput['phone'] ?? '') ?>" style="border-radius: 0 6px 6px 0; width: 100%;" oninput="validatePhoneInput(this, 'createPhoneError')">
                    </div>
                    <span id="createPhoneError" class="field-error-msg" style="display: none; color: #EF4444; font-size: 0.74rem; margin-top: 4px; font-weight: 600;">
                        Must be a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.
                    </span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Department / Team</label>
                    <select name="department" id="createPersonnelDept" class="form-select dynamic-other-select" required
                            data-enable-other="true"
                            data-other-name="custom_department"
                            data-other-label="Specify Custom Department / Team"
                            data-other-placeholder="e.g. Logistics & Courier Dispatch"
                            style="width: 100%;">
                        <option value="Executive Administration" <?= (($oldInput['department'] ?? '') === 'Executive Administration') ? 'selected' : '' ?>>Executive Administration</option>
                        <option value="Sales & Customer Inquiries" <?= (($oldInput['department'] ?? '') === 'Sales & Customer Inquiries') ? 'selected' : '' ?>>Sales &amp; Customer Inquiries</option>
                        <option value="Commercial Prepress & Pricing" <?= (($oldInput['department'] ?? '') === 'Commercial Prepress & Pricing') ? 'selected' : '' ?>>Commercial Prepress &amp; Pricing</option>
                        <option value="Client Services" <?= (($oldInput['department'] ?? '') === 'Client Services') ? 'selected' : '' ?>>Client Services</option>
                        <option value="Catalogue & Merchandise" <?= (($oldInput['department'] ?? '') === 'Catalogue & Merchandise') ? 'selected' : '' ?>>Catalogue &amp; Merchandise</option>
                        <option value="Content & Editorial" <?= (($oldInput['department'] ?? '') === 'Content & Editorial') ? 'selected' : '' ?>>Content &amp; Editorial</option>
                        <option value="Creative Studio Showcase" <?= (($oldInput['department'] ?? '') === 'Creative Studio Showcase') ? 'selected' : '' ?>>Creative Studio Showcase</option>
                        <option value="Production & Capabilities" <?= (($oldInput['department'] ?? '') === 'Production & Capabilities') ? 'selected' : '' ?>>Production &amp; Capabilities</option>
                        <option value="Marketing & CMS" <?= (($oldInput['department'] ?? '') === 'Marketing & CMS') ? 'selected' : '' ?>>Marketing &amp; CMS</option>
                        <option value="Search & AI Discovery" <?= (($oldInput['department'] ?? '') === 'Search & AI Discovery') ? 'selected' : '' ?>>Search &amp; AI Discovery</option>
                        <option value="other">Other (Specify Custom Department)</option>
                    </select>
                </div>
            </div>

            <!-- Section 2: Security & Status (3-Column Desktop Grid) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Initial Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="newPass1" class="form-input" required minlength="8" placeholder="Min. 8 characters" style="width: 100%; padding-right: 36px;" oninput="validatePasswordMatch()">
                        <button type="button" onclick="togglePasswordVisibility('newPass1', this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px; font-size: 0.95rem;" title="Toggle Password Visibility">&#x1F441;</button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" name="confirm_password" id="newPass2" class="form-input" required minlength="8" placeholder="Re-type password" style="width: 100%; padding-right: 36px;" oninput="validatePasswordMatch()">
                        <button type="button" onclick="togglePasswordVisibility('newPass2', this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px; font-size: 0.95rem;" title="Toggle Password Visibility">&#x1F441;</button>
                    </div>
                    <span id="passMatchError" class="field-error-msg" style="display: none; color: #EF4444; font-size: 0.74rem; margin-top: 4px; font-weight: 600;">
                        Passwords do not match.
                    </span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Initial Status</label>
                    <select name="status" class="form-select" style="width: 100%;">
                        <option value="active" selected>Active — Permit Immediate Login</option>
                        <option value="suspended">Suspended — Inactive Onboarding</option>
                    </select>
                </div>
            </div>

            <!-- Section 3: Predefined Role Groups Selection -->
            <div style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1.25rem 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <label style="font-size: 0.9rem; font-weight: 700; color: var(--admin-gold, #D4AF37); text-transform: uppercase; letter-spacing: 0.05em; margin: 0; display: block;">
                            1. Select Assigned Role Groups
                        </label>
                        <span style="font-size: 0.78rem; color: var(--admin-text-muted, #94A3B8);">
                            Check one or more roles. Their recommended permissions will be automatically loaded into the matrix below.
                        </span>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline" onclick="clearAllFormRoles('createPersonnelForm')" style="font-size: 0.72rem;">
                        Clear Role Selections
                    </button>
                </div>

                <!-- Roles Grid (Responsive Cards) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 0.85rem;">
                    <?php foreach ($allRoles as $role): ?>
                        <label class="role-selector-card" style="display: flex; align-items: flex-start; gap: 10px; background: #181822; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 0.75rem 1rem; cursor: pointer; transition: all 0.2s ease;">
                            <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" class="create-role-checkbox" data-perms="<?= htmlspecialchars(json_encode($role['permission_codes']), ENT_QUOTES) ?>" onchange="recalculateFormPermissions('createPersonnelForm')" style="margin-top: 3px; accent-color: var(--admin-gold, #D4AF37);">
                            <div>
                                <strong style="font-size: 0.88rem; color: #F3F4F6; display: block; line-height: 1.3;">
                                    <?= e($role['name']) ?>
                                    <?php if ($role['slug'] === 'super_admin'): ?>
                                        <span class="badge" style="background: rgba(212,175,55,0.2); color: #D4AF37; font-size: 0.65rem; padding: 1px 6px; margin-left: 4px;">Full</span>
                                    <?php endif; ?>
                                </strong>
                                <small style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 3px; line-height: 1.35;">
                                    <?= e($role['description']) ?>
                                </small>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Section 4: Granular Permissions Matrix -->
            <div style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1.25rem 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <label style="font-size: 0.9rem; font-weight: 700; color: var(--admin-gold, #D4AF37); text-transform: uppercase; letter-spacing: 0.05em; margin: 0; display: block;">
                            2. Granular Permissions Matrix &amp; Custom Overrides
                        </label>
                        <span style="font-size: 0.78rem; color: var(--admin-text-muted, #94A3B8);">
                            Permissions auto-check based on selected roles above. You may toggle any individual action as a custom grant or restriction.
                        </span>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-xs btn-outline" onclick="toggleAllMatrixPerms('createPersonnelForm', true)">Select All</button>
                        <button type="button" class="btn btn-xs btn-outline" onclick="toggleAllMatrixPerms('createPersonnelForm', false)">Deselect All</button>
                    </div>
                </div>

                <!-- Module-Wise Permission Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 1rem;">
                    <?php foreach ($groupedPermissions as $module => $perms): ?>
                        <div style="background: #16161F; border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; overflow: hidden;">
                            <div style="padding: 0.6rem 0.9rem; background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center;">
                                <strong style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--admin-gold, #D4AF37);">
                                    <?= ucfirst($module) ?>
                                </strong>
                                <span style="font-size: 0.72rem; color: #94A3B8;"><?= count($perms) ?> actions</span>
                            </div>
                            <div style="padding: 0.75rem 0.9rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                <?php foreach ($perms as $p): ?>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #E5E7EB; cursor: pointer; user-select: none;">
                                        <input type="checkbox" name="custom_permissions[]" value="<?= e($p['code']) ?>" class="create-perm-checkbox" onchange="updateSummaryCounter('createPersonnelForm')" style="accent-color: var(--admin-gold, #D4AF37);">
                                        <span><?= e($p['name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Permission Summary Strip & Submit -->
            <div style="background: rgba(212, 175, 55, 0.08); border: 1px solid rgba(212, 175, 55, 0.25); border-radius: 8px; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div id="createSummaryText" style="font-size: 0.85rem; color: #FDE68A;">
                    <strong>Effective Permissions:</strong> <span class="summary-count">0</span> individual capabilities selected across <span class="module-count">0</span> functional modules.
                </div>
                <button type="submit" id="createPersonnelSubmitBtn" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <span>Provision Personnel Account</span> &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. FULL-WIDTH AUTHORIZED PERSONNEL DIRECTORY -->
<!-- ========================================================================= -->
<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.18)); padding: 1.25rem 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h2 class="admin-card-title" style="margin: 0 0 0.2rem; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">
                Authorized Personnel Directory (<?= count($admins) ?>)
            </h2>
            <p style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
                Active staff accounts, assigned work responsibilities, departments, and security controls
            </p>
        </div>
        <div style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
            All actions protected by PHP RBAC &amp; BCrypt encryption
        </div>
    </div>

    <div class="table-responsive" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">User / Employee</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Assigned Roles &amp; Department</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Contact Phone</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Last Active</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $u): ?>
                    <?php
                        $uId        = (int) $u['id'];
                        $roles      = $userRoleMap[$uId] ?? [];
                        $perms      = $userPermsMap[$uId] ?? [];
                        $isSuper    = user_is_super_admin($uId);
                        $isCurrent  = ($uId === (int)$adminUser['id']);
                        $dept       = $u['department'] ?: 'General Operations';
                    ?>
                    <tr id="user-row-<?= $uId ?>">
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: <?= $isSuper ? 'rgba(212, 175, 55, 0.18)' : 'rgba(56, 189, 248, 0.18)' ?>; border: 1.5px solid <?= $isSuper ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; color: <?= $isSuper ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong style="color: var(--admin-text-main, #F3F4F6); font-size: 0.92rem;"><?= e($u['name']) ?></strong>
                                    <div style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8); display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                        <span><?= e($u['email']) ?></span>
                                        <?php if (!empty($u['email_verified_at'])): ?>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35); font-size: 0.68rem; padding: 1px 6px;">
                                                &#x2713; Verified
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.35); font-size: 0.68rem; padding: 1px 6px;">
                                                &#x23F3; Verification Pending
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($isCurrent): ?>
                                        <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: #FDE68A; border: 1px solid rgba(212, 175, 55, 0.4); font-size: 0.68rem; padding: 1px 6px; font-weight: 700; margin-top: 3px; display: inline-block;">
                                            Current Session
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 12px 16px;">
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    <?php if ($isSuper): ?>
                                        <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; border: 1px solid rgba(212, 175, 55, 0.4); font-size: 0.75rem; padding: 2px 8px; font-weight: 700;">
                                            SUPER ADMIN
                                        </span>
                                    <?php elseif (!empty($roles)): ?>
                                        <?php foreach ($roles as $r): ?>
                                            <span class="badge" style="background: rgba(56, 189, 248, 0.18); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.35); font-size: 0.72rem; padding: 2px 7px; font-weight: 600;">
                                                <?= e($r['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge" style="background: rgba(148, 163, 184, 0.15); color: #94A3B8; font-size: 0.72rem; padding: 2px 7px;">
                                            Staff
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <small style="font-size: 0.75rem; color: var(--admin-text-muted, #94A3B8);">
                                    Dept: <?= e($dept) ?>
                                </small>
                            </div>
                        </td>

                        <td style="padding: 12px 16px; font-size: 0.85rem; color: var(--admin-text-main, #CBD5E1);">
                            <?= e($u['phone'] ?: '—') ?>
                        </td>

                        <td style="padding: 12px 16px;" id="status-cell-<?= $uId ?>">
                            <span class="badge <?= $u['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <?= ucfirst($u['status']) ?>
                            </span>
                        </td>

                        <td style="padding: 12px 16px; font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
                            <?= $u['last_active'] ? date('d M Y, h:i A', strtotime($u['last_active'])) : '—' ?>
                        </td>

                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end; flex-wrap: nowrap;">
                                <?php if ($isCurrent): ?>
                                    <!-- Current Session Super Admin: Password Reset only; Edit, Perms & Status buttons removed -->
                                    <button type="button" class="btn btn-xs btn-outline" onclick="openPasswordModal(<?= $uId ?>, '<?= e(addslashes($u['name'])) ?>')" style="padding: 0.35rem 0.65rem; color: var(--admin-gold, #D4AF37); border-color: rgba(212, 175, 55, 0.4);">
                                        Password
                                    </button>
                                <?php else: ?>
                                    <!-- Other Personnel Accounts -->
                                    <button type="button" class="btn btn-xs btn-outline action-edit-btn" onclick="openEditPersonnelModal(<?= htmlspecialchars(json_encode([
                                        'id'         => $uId,
                                        'name'       => $u['name'],
                                        'email'      => $u['email'],
                                        'phone'      => $u['phone'],
                                        'department' => $dept,
                                        'role_ids'   => array_column($roles, 'id'),
                                        'perms'      => array_values($perms),
                                        'is_current' => $isCurrent,
                                        'is_super'   => $isSuper
                                    ]), ENT_QUOTES, 'UTF-8') ?>)" style="padding: 0.35rem 0.65rem;">
                                        Edit
                                    </button>

                                    <button type="button" class="btn btn-xs btn-outline" onclick="openPermissionsInspectModal('<?= e(addslashes($u['name'])) ?>', <?= htmlspecialchars(json_encode(array_values($perms)), ENT_QUOTES, 'UTF-8') ?>)" style="padding: 0.35rem 0.65rem; color: #38BDF8; border-color: rgba(56, 189, 248, 0.4);">
                                        Perms (<?= count($perms) ?>)
                                    </button>

                                    <button type="button" class="btn btn-xs btn-outline" onclick="openPasswordModal(<?= $uId ?>, '<?= e(addslashes($u['name'])) ?>')" style="padding: 0.35rem 0.65rem; color: var(--admin-gold, #D4AF37); border-color: rgba(212, 175, 55, 0.4);">
                                        Password
                                    </button>

                                    <button type="button" id="toggle-btn-<?= $uId ?>" class="btn btn-xs <?= $u['status'] === 'active' ? 'btn-outline' : 'btn-secondary' ?>" onclick="confirmToggleStatus(<?= $uId ?>, '<?= e(addslashes($u['name'])) ?>', '<?= $u['status'] ?>')" style="<?= $u['status'] === 'active' ? 'color: var(--color-crimson, #EF4444); border-color: rgba(239, 68, 68, 0.4);' : 'color: #34D399; border-color: rgba(52, 211, 153, 0.4);' ?>">
                                        <?= $u['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: EDIT PERSONNEL MODAL -->
<!-- ========================================================================= -->
<div id="editPersonnelModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.35)); border-radius: 12px; width: 100%; max-width: 860px; max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 25px 60px rgba(0,0,0,0.85); overflow: hidden;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #181822;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Edit Personnel Profile &amp; Permissions</h3>
                <small style="color: #94A3B8;">Update profile details, work role assignments, or custom individual permissions</small>
            </div>
            <button type="button" id="closeEditModalBtn" onclick="closeEditPersonnelModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.75rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form method="POST" action="<?= url('admin/users.php') ?>" id="editPersonnelForm" style="display: flex; flex-direction: column; overflow: hidden; flex: 1;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="edit_personnel">
            <input type="hidden" name="user_id" id="editUserId">

            <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                <!-- Identity Fields -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Full Name</label>
                        <input type="text" name="name" id="editUserName" class="form-input" required style="width: 100%;">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Email Address</label>
                        <input type="email" name="email" id="editUserEmail" class="form-input" required style="width: 100%;" oninput="validateEmailInput(this, 'editEmailError')">
                        <span id="editEmailError" class="field-error-msg" style="display: none; color: #EF4444; font-size: 0.74rem; margin-top: 4px; font-weight: 600;">
                            Please enter a valid email address.
                        </span>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">
                            Indian Mobile <span style="font-weight: 400; color: #94A3B8; font-size: 0.75rem;">(10 digits)</span>
                        </label>
                        <div style="display: flex; align-items: center; width: 100%;">
                            <span style="background: #1B1B26; border: 1px solid rgba(255,255,255,0.1); border-right: none; border-radius: 6px 0 0 6px; padding: 0.65rem 0.75rem; font-size: 0.85rem; color: var(--admin-gold, #D4AF37); font-weight: 700; user-select: none;">+91</span>
                            <input type="text" name="phone" id="editUserPhone" class="form-input" maxlength="15" placeholder="9876543210" style="border-radius: 0 6px 6px 0; width: 100%;" oninput="validatePhoneInput(this, 'editPhoneError')">
                        </div>
                        <span id="editPhoneError" class="field-error-msg" style="display: none; color: #EF4444; font-size: 0.74rem; margin-top: 4px; font-weight: 600;">
                            Must be a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.
                        </span>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Department / Team</label>
                        <select name="department" id="editUserDept" class="form-select dynamic-other-select" required
                                data-enable-other="true"
                                data-other-name="custom_department"
                                data-other-label="Specify Custom Department / Team"
                                data-other-placeholder="e.g. Logistics & Courier Dispatch"
                                style="width: 100%;">
                            <option value="Executive Administration">Executive Administration</option>
                            <option value="Sales & Customer Inquiries">Sales &amp; Customer Inquiries</option>
                            <option value="Commercial Prepress & Pricing">Commercial Prepress &amp; Pricing</option>
                            <option value="Client Services">Client Services</option>
                            <option value="Catalogue & Merchandise">Catalogue &amp; Merchandise</option>
                            <option value="Content & Editorial">Content &amp; Editorial</option>
                            <option value="Creative Studio Showcase">Creative Studio Showcase</option>
                            <option value="Production & Capabilities">Production &amp; Capabilities</option>
                            <option value="Marketing & CMS">Marketing &amp; CMS</option>
                            <option value="Search & AI Discovery">Search &amp; AI Discovery</option>
                            <option value="other">Other (Specify Custom Department)</option>
                        </select>
                    </div>
                </div>

                <!-- Role Selection Section -->
                <div style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-gold, #D4AF37); text-transform: uppercase;">
                            Assigned Work Role Groups
                        </label>
                        <span id="editSelfNotice" style="display: none; color: #FDE68A; font-size: 0.75rem; font-weight: 700;">
                            (Current Admin Session Protected)
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.75rem;">
                        <?php foreach ($allRoles as $role): ?>
                            <label class="role-selector-card" style="display: flex; align-items: flex-start; gap: 8px; background: #1B1B26; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; padding: 0.6rem 0.85rem; cursor: pointer;">
                                <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" class="edit-role-checkbox" data-roleid="<?= $role['id'] ?>" data-perms="<?= htmlspecialchars(json_encode($role['permission_codes']), ENT_QUOTES) ?>" onchange="recalculateFormPermissions('editPersonnelForm')" style="margin-top: 3px; accent-color: var(--admin-gold, #D4AF37);">
                                <div>
                                    <strong style="font-size: 0.82rem; color: #F3F4F6; display: block;">
                                        <?= e($role['name']) ?>
                                    </strong>
                                    <small style="font-size: 0.7rem; color: #94A3B8; display: block;">
                                        <?= e($role['department']) ?>
                                    </small>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Custom Permissions Matrix -->
                <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-gold, #D4AF37); text-transform: uppercase;">
                            Permissions Matrix Overrides
                        </label>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" class="btn btn-xs btn-outline" onclick="toggleAllMatrixPerms('editPersonnelForm', true)">Select All</button>
                            <button type="button" class="btn btn-xs btn-outline" onclick="toggleAllMatrixPerms('editPersonnelForm', false)">Deselect All</button>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.85rem;">
                        <?php foreach ($groupedPermissions as $module => $perms): ?>
                            <div style="background: #181822; border: 1px solid rgba(255,255,255,0.07); border-radius: 6px; padding: 0.65rem 0.85rem;">
                                <strong style="font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37); display: block; margin-bottom: 0.4rem; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 3px;">
                                    <?= ucfirst($module) ?>
                                </strong>
                                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                    <?php foreach ($perms as $p): ?>
                                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.76rem; color: #E5E7EB; cursor: pointer;">
                                            <input type="checkbox" name="custom_permissions[]" value="<?= e($p['code']) ?>" class="edit-perm-checkbox" onchange="updateSummaryCounter('editPersonnelForm')" style="accent-color: var(--admin-gold, #D4AF37);">
                                            <span><?= e($p['name']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 1rem 1.5rem; background: #181822; border-top: 1px solid rgba(255, 255, 255, 0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                <div id="editSummaryText" style="font-size: 0.8rem; color: #FDE68A;">
                    <span class="summary-count">0</span> permissions active across <span class="module-count">0</span> modules.
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" class="btn btn-outline" onclick="closeEditPersonnelModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Save Personnel Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: RESET PASSWORD MODAL -->
<!-- ========================================================================= -->
<div id="resetPasswordModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.35)); border-radius: 12px; width: 100%; max-width: 480px; box-shadow: 0 25px 60px rgba(0,0,0,0.85); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #181822;">
            <h3 style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Reset Security Credentials</h3>
            <button type="button" id="closePassModalBtn" onclick="closePasswordModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.75rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/users.php') ?>" style="padding: 1.5rem;" onsubmit="return validatePasswordReset(event);">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="user_id" id="passwordUserId">

            <p style="color: #94A3B8; font-size: 0.85rem; margin: 0 0 1.25rem;">
                Provisioning new encrypted password for <strong id="passwordTargetName" style="color: #F3F4F6;"></strong>.
            </p>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">New Password</label>
                <input type="password" name="new_password" id="resetPass1" class="form-input" required minlength="8" placeholder="Minimum 8 characters" style="width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Confirm New Password</label>
                <input type="password" name="confirm_password" id="resetPass2" class="form-input" required minlength="8" placeholder="Re-type new password" style="width: 100%;">
                <small style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 4px;">
                    Password will be encrypted using PHP BCrypt algorithm (`password_hash`).
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-outline" onclick="closePasswordModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">Update Password</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 3: VIEW EFFECTIVE PERMISSIONS MODAL -->
<!-- ========================================================================= -->
<div id="viewPermissionsModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.35)); border-radius: 12px; width: 100%; max-width: 640px; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 25px 60px rgba(0,0,0,0.85); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #181822;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Effective Permission Matrix</h3>
                <small id="viewPermsTargetName" style="color: #94A3B8;"></small>
            </div>
            <button type="button" id="closePermsModalBtn" onclick="closePermissionsInspectModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.75rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <div id="viewPermsContent" style="padding: 1.5rem; overflow-y: auto; flex: 1;">
            <!-- Populated via JavaScript -->
        </div>
        <div style="padding: 1rem 1.5rem; background: #181822; border-top: 1px solid rgba(255, 255, 255, 0.08); text-align: right;">
            <button type="button" class="btn btn-outline" onclick="closePermissionsInspectModal()">Close</button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 4: CREATE CUSTOM ROLE MODAL -->
<!-- ========================================================================= -->
<div id="customRoleModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.35)); border-radius: 12px; width: 100%; max-width: 760px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 60px rgba(0,0,0,0.85); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #181822;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Create Custom Role Definition</h3>
                <small style="color: #94A3B8;">Define a new custom organizational role and assign specific module capabilities</small>
            </div>
            <button type="button" onclick="closeCustomRoleModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.75rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/users.php') ?>" style="display: flex; flex-direction: column; overflow: hidden; flex: 1;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create_custom_role">

            <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Role Title</label>
                        <input type="text" name="role_name" required placeholder="e.g. Social Media Content Team" class="form-input" style="width: 100%;">
                    </div>
                    <div>
                        <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Department</label>
                        <input type="text" name="role_department" required placeholder="e.g. Marketing &amp; Outreach" class="form-input" style="width: 100%;">
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.4rem; display: block;">Role Description &amp; Scope</label>
                    <textarea name="role_description" rows="2" class="form-textarea" placeholder="Describe the responsibilities and boundaries of this role..." style="width: 100%;"></textarea>
                </div>

                <label style="font-size: 0.85rem; font-weight: 700; color: var(--admin-gold, #D4AF37); text-transform: uppercase; margin-bottom: 0.5rem; display: block;">
                    Select Role Permissions
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.85rem;">
                    <?php foreach ($groupedPermissions as $module => $perms): ?>
                        <div style="background: #181822; border: 1px solid rgba(255,255,255,0.07); border-radius: 6px; padding: 0.65rem 0.85rem;">
                            <strong style="font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37); display: block; margin-bottom: 0.35rem;">
                                <?= ucfirst($module) ?>
                            </strong>
                            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                <?php foreach ($perms as $p): ?>
                                    <label style="display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #E5E7EB; cursor: pointer;">
                                        <input type="checkbox" name="permissions[]" value="<?= e($p['code']) ?>" style="accent-color: var(--admin-gold, #D4AF37);">
                                        <span><?= e($p['name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="padding: 1rem 1.5rem; background: #181822; border-top: 1px solid rgba(255, 255, 255, 0.08); display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-outline" onclick="closeCustomRoleModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">Save Custom Role</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT LOGIC & MODAL CONTROLLERS -->
<!-- ========================================================================= -->
<script>
// Master Roles & Permissions Data from Backend
var ALL_ROLES_DATA = <?= json_encode($allRoles) ?>;
var GROUPED_PERMS_DATA = <?= json_encode($groupedPermissions) ?>;
var CURRENT_SESSION_USER_ID = <?= (int) $adminUser['id'] ?>;

// 1. Recalculate and pre-check permissions based on selected roles
function recalculateFormPermissions(formId) {
    var form = document.getElementById(formId);
    if (!form) return;

    var roleCheckboxes = form.querySelectorAll('input[name="roles[]"]:checked');
    var isSuperSelected = false;
    var targetPermSet = {};

    roleCheckboxes.forEach(function(rc) {
        try {
            var perms = JSON.parse(rc.getAttribute('data-perms') || '[]');
            perms.forEach(function(code) {
                targetPermSet[code] = true;
            });
            if (rc.value == "1" || perms.length >= 40) {
                isSuperSelected = true;
            }
        } catch (e) {}
    });

    // Check matching checkboxes in matrix
    var permCheckboxes = form.querySelectorAll('input[name="custom_permissions[]"]');
    permCheckboxes.forEach(function(pc) {
        if (targetPermSet[pc.value] || isSuperSelected) {
            pc.checked = true;
        } else if (roleCheckboxes.length > 0) {
            // Only auto-uncheck if at least one role is checked
            pc.checked = false;
        }
    });

    updateSummaryCounter(formId);
}

// 2. Clear all role selections
function clearAllFormRoles(formId) {
    var form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('input[name="roles[]"]').forEach(function(rc) {
        rc.checked = false;
    });
    form.querySelectorAll('input[name="custom_permissions[]"]').forEach(function(pc) {
        pc.checked = false;
    });
    updateSummaryCounter(formId);
}

// 3. Toggle all checkboxes in matrix
function toggleAllMatrixPerms(formId, checkAll) {
    var form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('input[name="custom_permissions[]"]').forEach(function(pc) {
        pc.checked = checkAll;
    });
    updateSummaryCounter(formId);
}

// 4. Update live permission count and module summary
function updateSummaryCounter(formId) {
    var form = document.getElementById(formId);
    if (!form) return;

    var checked = form.querySelectorAll('input[name="custom_permissions[]"]:checked');
    var count = checked.length;
    var modules = {};

    checked.forEach(function(c) {
        var parts = c.value.split('.');
        if (parts[0]) modules[parts[0]] = true;
    });

    var summaryBox = form.querySelector('.summary-count');
    var moduleBox  = form.querySelector('.module-count');
    if (summaryBox) summaryBox.innerText = count;
    if (moduleBox)  moduleBox.innerText  = Object.keys(modules).length;
}

// 5. Open Edit Personnel Modal
function openEditPersonnelModal(userData) {
    document.getElementById('editUserId').value    = userData.id;
    document.getElementById('editUserName').value  = userData.name || '';
    document.getElementById('editUserEmail').value = userData.email || '';

    // Normalize phone for input (strip +91 prefix)
    var rawPhone = userData.phone || '';
    var cleanPhone = rawPhone.replace(/^\+91\s*/, '').trim();
    document.getElementById('editUserPhone').value = cleanPhone;

    var deptSelect = document.getElementById('editUserDept');
    if (deptSelect) {
        var deptVal = userData.department || 'General Operations';
        var hasMatch = false;
        for (var i = 0; i < deptSelect.options.length; i++) {
            if (deptSelect.options[i].value === deptVal) {
                hasMatch = true;
                break;
            }
        }
        if (hasMatch) {
            deptSelect.value = deptVal;
            deptSelect.removeAttribute('data-other-value-init');
        } else {
            deptSelect.value = 'other';
            deptSelect.setAttribute('data-other-value-init', deptVal);
        }
        if (window.DynamicOther) {
            window.DynamicOther.bind(deptSelect);
        }
        deptSelect.dispatchEvent(new Event('change'));
    }

    var form = document.getElementById('editPersonnelForm');

    // Pre-check roles
    var userRoleIds = (userData.role_ids || []).map(Number);
    form.querySelectorAll('.edit-role-checkbox').forEach(function(rc) {
        rc.checked = userRoleIds.includes(Number(rc.value));
    });

    // Pre-check permissions
    var rawPerms = userData.perms || [];
    var userPerms = Array.isArray(rawPerms) ? rawPerms : Object.keys(rawPerms || {});
    form.querySelectorAll('.edit-perm-checkbox').forEach(function(pc) {
        pc.checked = userPerms.includes(pc.value) || userData.is_super;
    });

    // Safeguard notice if editing self
    var selfNotice = document.getElementById('editSelfNotice');
    if (userData.id === CURRENT_SESSION_USER_ID) {
        selfNotice.style.display = 'inline';
    } else {
        selfNotice.style.display = 'none';
    }

    updateSummaryCounter('editPersonnelForm');

    var modal = document.getElementById('editPersonnelModal');
    modal.classList.add('open');
    modal.style.display = 'flex';
}

function closeEditPersonnelModal() {
    var modal = document.getElementById('editPersonnelModal');
    modal.classList.remove('open');
    modal.style.display = 'none';
}

// 6. Open Password Reset Modal
function openPasswordModal(id, name) {
    document.getElementById('passwordUserId').value = id;
    document.getElementById('passwordTargetName').innerText = name;
    document.getElementById('resetPass1').value = '';
    document.getElementById('resetPass2').value = '';

    var modal = document.getElementById('resetPasswordModal');
    modal.classList.add('open');
    modal.style.display = 'flex';
}

function closePasswordModal() {
    var modal = document.getElementById('resetPasswordModal');
    modal.classList.remove('open');
    modal.style.display = 'none';
}

function validatePasswordReset(e) {
    var p1 = document.getElementById('resetPass1').value;
    var p2 = document.getElementById('resetPass2').value;
    if (p1.length < 8) {
        e.preventDefault();
        AdminToast.warning('Password must be at least 8 characters long.', 'Password Policy');
        return false;
    }
    if (p1 !== p2) {
        e.preventDefault();
        AdminToast.warning('Password confirmation does not match.', 'Password Mismatch');
        return false;
    }
    return true;
}

// 7. Open Permissions Inspect Modal
function openPermissionsInspectModal(name, perms) {
    document.getElementById('viewPermsTargetName').innerText = 'Effective authorizations for ' + name;
    var container = document.getElementById('viewPermsContent');

    var html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">';
    for (var mod in GROUPED_PERMS_DATA) {
        var modPerms = GROUPED_PERMS_DATA[mod];
        html += '<div style="background: #181822; border: 1px solid rgba(255,255,255,0.07); border-radius: 6px; padding: 0.75rem;">';
        html += '<strong style="font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37); display: block; margin-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 3px;">' + mod + '</strong>';
        html += '<div style="display:flex; flex-direction:column; gap:4px;">';
        modPerms.forEach(function(p) {
            var isGranted = perms.includes(p.code);
            html += '<div style="font-size:0.75rem; display:flex; align-items:center; justify-content:space-between; color:' + (isGranted ? '#34D399' : '#6B7280') + ';">';
            html += '<span>' + p.name + '</span>';
            html += '<span>' + (isGranted ? '&#10003; Granted' : '&#10007; Restricted') + '</span>';
            html += '</div>';
        });
        html += '</div></div>';
    }
    html += '</div>';

    container.innerHTML = html;
    var modal = document.getElementById('viewPermissionsModal');
    modal.classList.add('open');
    modal.style.display = 'flex';
}

function closePermissionsInspectModal() {
    var modal = document.getElementById('viewPermissionsModal');
    modal.classList.remove('open');
    modal.style.display = 'none';
}

// 8. Custom Role Modal
function openCustomRoleModal() {
    var modal = document.getElementById('customRoleModal');
    modal.classList.add('open');
    modal.style.display = 'flex';
}

function closeCustomRoleModal() {
    var modal = document.getElementById('customRoleModal');
    modal.classList.remove('open');
    modal.style.display = 'none';
}

// 9. Premium Custom Status Toggle Confirmation (Zero Native Dialogs)
async function confirmToggleStatus(userId, userName, currentStatus) {
    var willSuspend = (currentStatus === 'active');
    var confirmed = await AdminModal.confirm({
        title: willSuspend ? 'Suspend Employee?' : 'Reactivate Employee?',
        message: willSuspend
            ? 'Are you sure you want to suspend this employee? They will immediately lose access to the Admin Panel.'
            : 'This employee will be able to log in again after activation.',
        itemName: userName,
        isDestructive: willSuspend,
        confirmText: willSuspend ? 'Yes, Suspend' : 'Activate Account'
    });

    if (!confirmed) return;

    var formData = new FormData();
    formData.append('action', 'toggle_status');
    formData.append('user_id', userId);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    try {
        var res = await fetch('<?= url("admin/users.php") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        var data = await res.json();
        if (data.success) {
            // Update status badge in table
            var cell = document.getElementById('status-cell-' + userId);
            if (cell) {
                cell.innerHTML = '<span class="badge ' + (data.new_status === 'active' ? 'badge-success' : 'badge-danger') + '" style="font-size: 0.75rem; padding: 4px 10px;">' + data.label + '</span>';
            }

            // Update button
            var btn = document.getElementById('toggle-btn-' + userId);
            if (btn) {
                var nextAction = (data.new_status === 'active') ? 'Suspend' : 'Activate';
                btn.innerText = nextAction;
                btn.setAttribute('onclick', "confirmToggleStatus(" + userId + ", '" + userName.replace(/'/g, "\\'") + "', '" + data.new_status + "')");
                if (data.new_status === 'active') {
                    btn.className = 'btn btn-xs btn-outline';
                    btn.style.color = 'var(--color-crimson, #EF4444)';
                    btn.style.borderColor = 'rgba(239, 68, 68, 0.4)';
                } else {
                    btn.className = 'btn btn-xs btn-secondary';
                    btn.style.color = '#34D399';
                    btn.style.borderColor = 'rgba(52, 211, 153, 0.4)';
                }
            }

            AdminToast.success(data.message || (willSuspend ? 'Employee suspended successfully.' : 'Employee activated successfully.'));
        } else {
            AdminToast.error(data.error || 'Failed to update account status.');
        }
    } catch (err) {
        AdminToast.error('Network or server error updating account status.');
    }
}

// 10. Phone & Email Input Validation Helpers
function validatePhoneInput(input, errorElementId) {
    var raw = input.value.trim().replace(/\D+/g, '');
    if (raw.length === 12 && raw.startsWith('91')) raw = raw.substring(2);
    else if (raw.length === 11 && raw.startsWith('0')) raw = raw.substring(1);

    var errEl = document.getElementById(errorElementId);
    var isValid = (raw === '' || (raw.length === 10 && /^[6-9]\d{9}$/.test(raw)));

    if (errEl) {
        errEl.style.display = (isValid || raw === '') ? 'none' : 'block';
    }
    input.style.borderColor = (isValid || raw === '') ? '' : '#EF4444';
    return isValid;
}

function validateEmailInput(input, errorElementId) {
    var val = input.value.trim();
    var errEl = document.getElementById(errorElementId);
    var isValid = (val === '' || /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val));

    if (errEl) {
        errEl.style.display = (isValid || val === '') ? 'none' : 'block';
    }
    input.style.borderColor = (isValid || val === '') ? '' : '#EF4444';
    return isValid;
}

function validatePasswordMatch() {
    var p1 = document.getElementById('newPass1');
    var p2 = document.getElementById('newPass2');
    var errEl = document.getElementById('passMatchError');
    if (!p1 || !p2 || !errEl) return;

    if (p2.value !== '' && p1.value !== p2.value) {
        errEl.style.display = 'block';
        p2.style.borderColor = '#EF4444';
    } else {
        errEl.style.display = 'none';
        p2.style.borderColor = '';
    }
}

function togglePasswordVisibility(inputId, btn) {
    var input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '&#x1F576;';
    } else {
        input.type = 'password';
        btn.innerHTML = '&#x1F441;';
    }
}

function validateCreatePersonnelForm(e) {
    var phoneInput = document.getElementById('createPhoneInput');
    if (phoneInput && phoneInput.value.trim() !== '') {
        if (!validatePhoneInput(phoneInput, 'createPhoneError')) {
            e.preventDefault();
            AdminToast.error('Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.', 'Validation Error');
            phoneInput.focus();
            return false;
        }
    }

    var emailInput = document.getElementById('createEmailInput');
    if (emailInput && !validateEmailInput(emailInput, 'createEmailError')) {
        e.preventDefault();
        AdminToast.error('Please enter a valid corporate email address.', 'Validation Error');
        emailInput.focus();
        return false;
    }

    var p1 = document.getElementById('newPass1');
    var p2 = document.getElementById('newPass2');
    if (p1 && p2 && p1.value !== p2.value) {
        e.preventDefault();
        AdminToast.error('Password confirmation does not match.', 'Validation Error');
        p2.focus();
        return false;
    }

    var btn = document.getElementById('createPersonnelSubmitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="admin-spinner"></span> Provisioning Account...';
    }
    return true;
}

function copyVerificationLink(linkUrl) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(linkUrl).then(function() {
            AdminToast.success('Verification link copied to clipboard for testing.', 'Link Copied');
        }).catch(function() {
            AdminToast.info('Verification link: ' + linkUrl, 'Verification Link', 10000);
        });
    } else {
        AdminToast.info('Verification link: ' + linkUrl, 'Verification Link', 10000);
    }
}

// 10. Close modals when clicking backdrop or ESC key
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        closeEditPersonnelModal();
        closePasswordModal();
        closePermissionsInspectModal();
        closeCustomRoleModal();
    }
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditPersonnelModal();
        closePasswordModal();
        closePermissionsInspectModal();
        closeCustomRoleModal();
    }
});

// Initialize form counters on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSummaryCounter('createPersonnelForm');
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
