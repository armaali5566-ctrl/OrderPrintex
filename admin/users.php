<?php
/**
 * OrderPrintex - Admin Personnel & Role Management
 * Secure Role-Based Access Control: Super Admin & Production Staff
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Strict Super Admin Access Guard - Staff members receive 403 Forbidden
require_admin();
$adminUser = auth_user();

// Handle Actions (Add, Edit, Reset Password, Toggle Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Create New Personnel Account
    if ($action === 'create_admin') {
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = in_array($_POST['role'] ?? '', ['admin', 'staff'], true) ? $_POST['role'] : 'staff';

        if (empty($name) || empty($email) || empty($password)) {
            set_flash('error', 'Name, email, and password are required.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        if (strlen($password) < 8) {
            set_flash('error', 'Password must be at least 8 characters long.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $exists = db_fetch_one("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($exists) {
            set_flash('error', 'A user with this email address already exists.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        db_insert('users', [
            'name'          => $name,
            'email'         => $email,
            'password_hash' => $hash,
            'phone'         => $phone,
            'role'          => $role,
            'status'        => 'active'
        ]);

        $roleLabel = ($role === 'admin') ? 'Super Administrator' : 'Staff Member';
        log_activity($adminUser['id'], 'create_personnel_user', "Created new {$role} account for {$email}");
        set_flash('success', "Personnel account ({$roleLabel}) '{$name}' created successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // 2. Edit Personnel Details & Change Role
    if ($action === 'edit_user') {
        $userId = intval($_POST['user_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $newRole = in_array($_POST['role'] ?? '', ['admin', 'staff'], true) ? $_POST['role'] : 'staff';

        if (empty($name) || empty($email)) {
            set_flash('error', 'Name and email address are required.');
            header('Location: ' . url('admin/users.php'));
            exit;
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
            'id' => $userId
        ]);
        if ($emailCheck) {
            set_flash('error', 'Another account is already using that email address.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Safeguard 1: Prevent self-demotion
        if ($userId === $adminUser['id'] && $newRole !== 'admin') {
            set_flash('error', 'You cannot revoke your own Super Administrator role.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        // Safeguard 2: Prevent demoting the last active administrator
        if ($target['role'] === 'admin' && $newRole === 'staff') {
            $activeAdminCount = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM users WHERE role = 'admin' AND status = 'active'")['cnt'] ?? 0);
            if ($activeAdminCount <= 1) {
                set_flash('error', 'Cannot demote the last remaining active Administrator.');
                header('Location: ' . url('admin/users.php'));
                exit;
            }
        }

        db_update('users', [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
            'role'  => $newRole
        ], 'id = :id', ['id' => $userId]);

        log_activity($adminUser['id'], 'edit_personnel_user', "Updated personnel details for {$email} (Role: {$newRole})");
        set_flash('success', "Personnel profile for '{$name}' updated successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // 3. Reset User Password
    if ($action === 'reset_password') {
        $userId = intval($_POST['user_id'] ?? 0);
        $newPassword = $_POST['new_password'] ?? '';

        if (strlen($newPassword) < 8) {
            set_flash('error', 'Password must be at least 8 characters long.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $target = db_fetch_one("SELECT id, name, email FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $userId]);
        if (!$target) {
            set_flash('error', 'Personnel account not found.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        db_update('users', ['password_hash' => $newHash], 'id = :id', ['id' => $userId]);

        log_activity($adminUser['id'], 'reset_user_password', "Reset password for personnel {$target['email']}");
        set_flash('success', "Credentials for '{$target['name']}' reset successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    // 4. Toggle Account Status (Active / Suspended)
    if ($action === 'toggle_status') {
        $targetId = intval($_POST['user_id'] ?? 0);
        if ($targetId === $adminUser['id']) {
            set_flash('error', 'You cannot deactivate your own administrative account.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $target = db_fetch_one("SELECT id, status, email, role FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $targetId]);
        if ($target) {
            // Safeguard: Prevent deactivating the last active administrator
            if ($target['role'] === 'admin' && $target['status'] === 'active') {
                $activeAdminCount = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM users WHERE role = 'admin' AND status = 'active'")['cnt'] ?? 0);
                if ($activeAdminCount <= 1) {
                    set_flash('error', 'Cannot suspend the last remaining active Administrator.');
                    header('Location: ' . url('admin/users.php'));
                    exit;
                }
            }

            $newStatus = ($target['status'] === 'active') ? 'suspended' : 'active';
            db_update('users', ['status' => $newStatus], 'id = :id', ['id' => $targetId]);
            log_activity($adminUser['id'], 'toggle_admin_status', "Changed status of {$target['email']} to {$newStatus}");
            set_flash('success', "Personnel account status updated to " . ucfirst($newStatus));
        }
        header('Location: ' . url('admin/users.php'));
        exit;
    }
}

// Fetch all staff and admin users — guarantee both variables are defined
$adminStaffUsers = db_fetch_all("
    SELECT id, name, email, phone, role, status, created_at,
           (SELECT MAX(created_at) FROM activity_logs WHERE user_id = users.id) as last_active
    FROM users 
    WHERE role IN ('admin', 'staff')
    ORDER BY role ASC, id ASC
") ?: [];
$admins = $adminStaffUsers;

$adminTitle = "Manage Admin Personnel & Roles";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Admin Personnel &amp; Access Controls</h1>
        <p class="admin-page-subtitle">Manage administrative personnel, role boundaries (Admin vs Staff), credentials, and account statuses.</p>
    </div>
</div>

<div class="admin-filter-bar" style="margin-bottom: 1.5rem;">
    <div class="admin-filter-tabs">
        <a href="<?= url('admin/users.php') ?>" class="admin-tab-btn active">Authorized Personnel (<?= count($admins) ?>)</a>
        <a href="<?= url('admin/activity.php') ?>" class="admin-tab-btn">Security &amp; Activity Log &rarr;</a>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 1. FULL-WIDTH ADD NEW PERSONNEL FORM (AT TOP) -->
<!-- ========================================================================= -->
<div class="admin-card" style="margin-bottom: 2rem;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.18)); padding: 1.25rem 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
        <h2 class="admin-card-title" style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <span>+ Add New Personnel Account</span>
        </h2>
        <span style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
            Provision Staff or Super Administrator credentials with BCrypt encryption
        </span>
    </div>
    <div class="admin-card-body" style="padding: 1.5rem;">
        <form method="POST" action="<?= url('admin/users.php') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create_admin">

            <!-- Responsive 3-Column Grid: Name, Email, Phone -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Full Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="Personnel Name" style="width: 100%;">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Email Address</label>
                    <input type="email" name="email" class="form-input" required placeholder="staff@orderprintex.com" style="width: 100%;">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Phone Number</label>
                    <input type="text" name="phone" class="form-input" placeholder="+44 7700 900000" style="width: 100%;">
                </div>
            </div>

            <!-- Responsive 2-Column Grid: Role & Password -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Assigned Role</label>
                    <select name="role" id="newRoleSelect" class="form-select" required style="width: 100%;">
                        <option value="staff" selected>Staff — Production, Sales &amp; Quotations (Restricted)</option>
                        <option value="admin">Admin — Super Administrator (Full System Control)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: var(--admin-text-main, #F3F4F6); margin-bottom: 0.4rem; display: block;">Initial Password</label>
                    <input type="password" name="password" class="form-input" required minlength="8" placeholder="Min. 8 characters" style="width: 100%;">
                    <small style="font-size: 0.75rem; color: var(--admin-text-muted, #94A3B8); display: block; margin-top: 4px;">
                        Will be hashed securely with PHP BCrypt (`password_hash`).
                    </small>
                </div>
            </div>

            <!-- Dynamic Role Description Box -->
            <div id="newRoleDescription" style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 8px; padding: 0.85rem 1.15rem; margin-bottom: 1.5rem; font-size: 0.82rem; line-height: 1.5; color: #BAE6FD;">
                <strong>Staff Permissions:</strong> Access to Customer Enquiries, Quotations Desk, Customer Accounts, and Product Catalog. Restricted from system settings, personnel management, website configuration, and security logs.
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <span>Create Personnel Account</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. FULL-WIDTH AUTHORIZED PERSONNEL TABLE CARD (BELOW FORM) -->
<!-- ========================================================================= -->
<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.18)); padding: 1.25rem 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
        <h2 class="admin-card-title" style="margin: 0; font-size: 1.15rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">
            Authorized Personnel Directory (<?= count($admins) ?>)
        </h2>
        <span style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
            Manage accounts, edit details, change roles, reset passwords, and toggle security status
        </span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">User / Personnel</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Assigned Role</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Contact Phone</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Account Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Last Active</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 0.78rem; text-transform: uppercase; color: var(--admin-gold, #D4AF37);">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $u): ?>
                    <tr>
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: <?= $u['role'] === 'admin' ? 'var(--admin-sidebar-active-bg, rgba(212, 175, 55, 0.18))' : 'rgba(56, 189, 248, 0.18)' ?>; border: 1.5px solid <?= $u['role'] === 'admin' ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; color: <?= $u['role'] === 'admin' ? 'var(--admin-gold, #D4AF37)' : '#38BDF8' ?>; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong style="color: var(--admin-text-main, #F3F4F6); font-size: 0.92rem;"><?= e($u['name']) ?></strong>
                                    <div style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);"><?= e($u['email']) ?></div>
                                    <?php if ($u['id'] === $adminUser['id']): ?>
                                        <span style="font-size: 0.7rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">(Current Session)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px 16px;">
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; border: 1px solid rgba(212, 175, 55, 0.4); font-size: 0.75rem; padding: 4px 10px; font-weight: 700;">
                                    Super Admin
                                </span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(56, 189, 248, 0.2); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.4); font-size: 0.75rem; padding: 4px 10px; font-weight: 700;">
                                    Staff
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; font-size: 0.85rem; color: var(--admin-text-main, #CBD5E1);"><?= e($u['phone'] ?: '—') ?></td>
                        <td style="padding: 12px 16px;">
                            <span class="badge <?= $u['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <?= ucfirst($u['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
                            <?= $u['last_active'] ? date('d M Y, h:i A', strtotime($u['last_active'])) : '—' ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end;">
                                <!-- Edit Details Button -->
                                <button type="button" class="btn btn-xs btn-outline" onclick="openEditModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)" style="padding: 0.35rem 0.65rem;">
                                    Edit
                                </button>

                                <!-- Reset Password Button -->
                                <button type="button" class="btn btn-xs btn-outline" onclick="openPasswordModal(<?= $u['id'] ?>, '<?= e(addslashes($u['name'])) ?>')" style="padding: 0.35rem 0.65rem; color: var(--admin-gold, #D4AF37); border-color: rgba(212, 175, 55, 0.4);">
                                    Password
                                </button>

                                <!-- Suspend / Activate Toggle -->
                                <?php if ($u['id'] !== $adminUser['id']): ?>
                                    <form method="POST" action="<?= url('admin/users.php') ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to <?= $u['status'] === 'active' ? 'suspend' : 'activate' ?> this account?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-xs <?= $u['status'] === 'active' ? 'btn-outline' : 'btn-secondary' ?>" style="<?= $u['status'] === 'active' ? 'color: var(--color-crimson, #EF4444); border-color: rgba(239, 68, 68, 0.4);' : 'color: #34D399; border-color: rgba(52, 211, 153, 0.4);' ?>">
                                            <?= $u['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.72rem; color: var(--admin-gold, #D4AF37); font-weight: 600; padding: 4px 6px;">Active Self</span>
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
<!-- EDIT PERSONNEL MODAL -->
<!-- ========================================================================= -->
<div id="editUserModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.3)); border-radius: 12px; width: 100%; max-width: 520px; box-shadow: 0 15px 40px rgba(0,0,0,0.7); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Edit Personnel Account</h3>
            <button type="button" onclick="closeEditModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.5rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/users.php') ?>" style="padding: 1.5rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="edit_user">
            <input type="hidden" name="user_id" id="editUserId">

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.35rem; display: block;">Full Name</label>
                <input type="text" name="name" id="editUserName" class="form-input" required style="width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.35rem; display: block;">Email Address</label>
                <input type="email" name="email" id="editUserEmail" class="form-input" required style="width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.35rem; display: block;">Phone Number</label>
                <input type="text" name="phone" id="editUserPhone" class="form-input" style="width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.35rem; display: block;">Assigned Role</label>
                <select name="role" id="editUserRole" class="form-select" required style="width: 100%;">
                    <option value="staff">Staff — Production, Sales &amp; Quotations</option>
                    <option value="admin">Admin — Super Administrator</option>
                </select>
                <small id="editSelfNotice" style="display: none; color: var(--admin-gold, #D4AF37); font-size: 0.75rem; margin-top: 4px;">
                    Note: You are editing your own current session. Your Super Admin role cannot be demoted.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- RESET PASSWORD MODAL -->
<!-- ========================================================================= -->
<div id="resetPasswordModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div class="modal-dialog" style="background: #141419; border: 1px solid var(--admin-border-card, rgba(212, 175, 55, 0.3)); border-radius: 12px; width: 100%; max-width: 460px; box-shadow: 0 15px 40px rgba(0,0,0,0.7); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--admin-gold, #D4AF37); font-weight: 700;">Reset Password</h3>
            <button type="button" onclick="closePasswordModal()" style="background: transparent; border: none; color: #94A3B8; font-size: 1.5rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/users.php') ?>" style="padding: 1.5rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="user_id" id="passwordUserId">

            <p style="color: #94A3B8; font-size: 0.85rem; margin: 0 0 1rem;">
                Setting a new password for <strong id="passwordTargetName" style="color: #F3F4F6;"></strong>.
            </p>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label required" style="font-size: 0.82rem; font-weight: 700; color: #F3F4F6; margin-bottom: 0.35rem; display: block;">New Password</label>
                <input type="password" name="new_password" class="form-input" required minlength="8" placeholder="Minimum 8 characters" style="width: 100%;">
                <small style="font-size: 0.75rem; color: #94A3B8; display: block; margin-top: 4px;">
                    Password will be encrypted using PHP BCrypt algorithm.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-outline" onclick="closePasswordModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
// Role Selection Dynamic Help Text
document.getElementById('newRoleSelect').addEventListener('change', function() {
    var desc = document.getElementById('newRoleDescription');
    if (this.value === 'admin') {
        desc.style.background = 'rgba(212, 175, 55, 0.08)';
        desc.style.borderColor = 'rgba(212, 175, 55, 0.25)';
        desc.style.color = '#FDE68A';
        desc.innerHTML = '<strong>Super Admin Permissions:</strong> Full unrestricted administrative control over the entire system, customer accounts, quotations, product catalog, categories, pricing, portfolio curation, website settings, personnel management, and security audit logs.';
    } else {
        desc.style.background = 'rgba(56, 189, 248, 0.08)';
        desc.style.borderColor = 'rgba(56, 189, 248, 0.25)';
        desc.style.color = '#BAE6FD';
        desc.innerHTML = '<strong>Staff Permissions:</strong> Access to Customer Enquiries, Quotations Desk, Customer Accounts, and Product Catalog. Restricted from system settings, personnel management, website configuration, and security logs.';
    }
});

// Edit Modal Handler
var currentSessionId = <?= (int) $adminUser['id'] ?>;
function openEditModal(user) {
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserName').value = user.name || '';
    document.getElementById('editUserEmail').value = user.email || '';
    document.getElementById('editUserPhone').value = user.phone || '';
    document.getElementById('editUserRole').value = user.role || 'staff';

    var selfNotice = document.getElementById('editSelfNotice');
    var roleSelect = document.getElementById('editUserRole');
    if (user.id === currentSessionId) {
        selfNotice.style.display = 'block';
        roleSelect.disabled = true;
        // ensure hidden field carries role if disabled
        var hiddenRole = document.getElementById('editHiddenRole');
        if (!hiddenRole) {
            hiddenRole = document.createElement('input');
            hiddenRole.type = 'hidden';
            hiddenRole.name = 'role';
            hiddenRole.id = 'editHiddenRole';
            roleSelect.parentNode.appendChild(hiddenRole);
        }
        hiddenRole.value = 'admin';
    } else {
        selfNotice.style.display = 'none';
        roleSelect.disabled = false;
        var hiddenRole = document.getElementById('editHiddenRole');
        if (hiddenRole) hiddenRole.remove();
    }

    var modal = document.getElementById('editUserModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

// Password Modal Handler
function openPasswordModal(id, name) {
    document.getElementById('passwordUserId').value = id;
    document.getElementById('passwordTargetName').innerText = name;
    var modal = document.getElementById('resetPasswordModal');
    modal.style.display = 'flex';
}

function closePasswordModal() {
    document.getElementById('resetPasswordModal').style.display = 'none';
}

// Close modals when clicking backdrop
window.addEventListener('click', function(e) {
    if (e.target.id === 'editUserModal') closeEditModal();
    if (e.target.id === 'resetPasswordModal') closePasswordModal();
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
