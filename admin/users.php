<?php
/**
 * OrderPrintex - Admin Users & Access Permissions
 */

$adminTitle = "Manage Admin Users & Roles";
require_once __DIR__ . '/header.php';

// Handle Actions (Add, Edit, Toggle Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    $action = $_POST['action'];

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
            set_flash('error', 'Admin password must be at least 8 characters long.');
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

        log_activity($adminUser['id'], 'create_admin_user', "Created new {$role} account for {$email}");
        set_flash('success', "New {$role} user '{$name}' created successfully.");
        header('Location: ' . url('admin/users.php'));
        exit;
    }

    if ($action === 'toggle_status') {
        $targetId = intval($_POST['user_id'] ?? 0);
        if ($targetId === $adminUser['id']) {
            set_flash('error', 'You cannot deactivate your own administrative account.');
            header('Location: ' . url('admin/users.php'));
            exit;
        }

        $target = db_fetch_one("SELECT id, status, email FROM users WHERE id = :id AND role IN ('admin', 'staff')", ['id' => $targetId]);
        if ($target) {
            $newStatus = ($target['status'] === 'active') ? 'suspended' : 'active';
            db_update('users', ['status' => $newStatus], 'id = :id', ['id' => $targetId]);
            log_activity($adminUser['id'], 'toggle_admin_status', "Changed status of {$target['email']} to {$newStatus}");
            set_flash('success', "User account status updated to " . ucfirst($newStatus));
        }
        header('Location: ' . url('admin/users.php'));
        exit;
    }
}

// Fetch all staff and admin users
$adminStaffUsers = db_fetch_all("
    SELECT id, name, email, phone, role, status, created_at,
           (SELECT MAX(created_at) FROM activity_logs WHERE user_id = users.id) as last_active
    FROM users 
    WHERE role IN ('admin', 'staff')
    ORDER BY role ASC, id ASC
");
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Admin Users & Permissions</h1>
        <p class="admin-page-subtitle">Manage administrative staff, role assignments, production permissions, and security credentials.</p>
    </div>
</div>

<div class="admin-grid-layout" style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; align-items: start;">
    
    <!-- Add New Staff Form -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">+ Add New Staff / Admin</h2>
        </div>
        <div class="admin-card-body" style="padding: 20px;">
            <form method="POST" action="<?= url('admin/users.php') ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="create_admin">

                <div class="form-group">
                    <label class="form-label required">Full Name</label>
                    <input type="text" name="name" class="form-input" required placeholder="Staff Member Name">
                </div>

                <div class="form-group">
                    <label class="form-label required">Email Address</label>
                    <input type="email" name="email" class="form-input" required placeholder="staff@orderprintex.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" placeholder="+91 98765 43210">
                </div>

                <div class="form-group">
                    <label class="form-label required">Role & Permissions</label>
                    <select name="role" class="form-select" required>
                        <option value="staff">Production Staff (Orders, Quotes, Artwork)</option>
                        <option value="admin">Super Administrator (Full System Control)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Initial Password</label>
                    <input type="password" name="password" class="form-input" required minlength="8" placeholder="Min. 8 characters">
                    <small style="font-size: 0.75rem; color: var(--color-text-muted); display: block; margin-top: 4px;">
                        Will be hashed securely with BCrypt.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; justify-content: center; margin-top: 10px;">
                    ✨ Create Staff Account
                </button>
            </form>
        </div>
    </div>

    <!-- Admin Users Table -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Authorized Personnel (<?= count($adminStaffUsers) ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adminStaffUsers as $u): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--color-navy);"><?= e($u['name']) ?></strong>
                                <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= e($u['email']) ?></div>
                                <?php if ($u['id'] === $adminUser['id']): ?>
                                    <span style="font-size: 0.7rem; color: var(--color-gold); font-weight: 700;">(You)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $u['role'] === 'admin' ? 'badge-primary' : 'badge-info' ?>" style="font-size: 0.75rem;">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td style="font-size: 0.8rem;"><?= e($u['phone'] ?: '—') ?></td>
                            <td>
                                <span class="badge <?= $u['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= ucfirst($u['status']) ?>
                                </span>
                            </td>
                            <td style="font-size: 0.75rem; color: var(--color-text-muted);">
                                <?= $u['last_active'] ? date('d M, h:i A', strtotime($u['last_active'])) : '—' ?>
                            </td>
                            <td>
                                <?php if ($u['id'] !== $adminUser['id']): ?>
                                    <form method="POST" action="<?= url('admin/users.php') ?>" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-xs <?= $u['status'] === 'active' ? 'btn-outline' : 'btn-secondary' ?>" style="<?= $u['status'] === 'active' ? 'color: var(--color-crimson); border-color: rgba(168, 62, 60, 0.4);' : '' ?>">
                                            <?= $u['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted);">Active Session</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
