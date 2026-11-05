<?php
/**
 * OrderPrintex - Centralized Role-Based Access Control (RBAC) & Permission Engine
 * Granular permissions, multi-role inheritance, custom role creation, and individual overrides.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Check if a user is a Super Administrator (unrestricted system access)
 */
function user_is_super_admin(?int $userId = null): bool {
    if ($userId === null) {
        if (!function_exists('auth_user')) {
            return false;
        }
        $user = auth_user();
        if (!$user) {
            return false;
        }
        $userId = (int) $user['id'];
        if (($user['role'] ?? '') === 'admin') {
            return true;
        }
    } else {
        $user = db_fetch_one("SELECT id, role FROM users WHERE id = :id", ['id' => $userId]);
        if ($user && ($user['role'] ?? '') === 'admin') {
            return true;
        }
    }

    // Also check if user has super_admin role linked in admin_user_roles
    static $superAdminCache = [];
    if (!isset($superAdminCache[$userId])) {
        $hasSuperRole = db_fetch_one("
            SELECT 1 FROM admin_user_roles ur
            JOIN admin_roles r ON ur.role_id = r.id
            WHERE ur.user_id = :uid AND r.slug = 'super_admin'
            LIMIT 1
        ", ['uid' => $userId]);
        $superAdminCache[$userId] = (bool) $hasSuperRole;
    }
    return $superAdminCache[$userId];
}

/**
 * Fetch all roles assigned to an employee
 */
function user_get_roles(int $userId): array {
    static $userRolesCache = [];
    if (!isset($userRolesCache[$userId])) {
        $roles = db_fetch_all("
            SELECT r.id, r.slug, r.name, r.department, r.description, r.is_system
            FROM admin_roles r
            JOIN admin_user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = :uid
            ORDER BY r.name ASC
        ", ['uid' => $userId]) ?: [];
        $userRolesCache[$userId] = $roles;
    }
    return $userRolesCache[$userId];
}

/**
 * Get effective permissions for a user (Role permissions + Custom Grants - Custom Revokes)
 */
function user_get_permissions(int $userId): array {
    static $effectivePermsCache = [];
    if (isset($effectivePermsCache[$userId])) {
        return $effectivePermsCache[$userId];
    }

    // Super Admin receives all available permissions automatically
    if (user_is_super_admin($userId)) {
        $all = db_fetch_all("SELECT code FROM admin_permissions");
        $effective = array_values(array_column($all, 'code'));
        $effectivePermsCache[$userId] = $effective;
        return $effectivePermsCache[$userId];
    }

    // 1. Collect permissions from all assigned roles
    $rolePerms = db_fetch_all("
        SELECT DISTINCT p.code
        FROM admin_permissions p
        JOIN admin_role_permissions rp ON p.id = rp.permission_id
        JOIN admin_user_roles ur ON rp.role_id = ur.role_id
        WHERE ur.user_id = :uid
    ", ['uid' => $userId]) ?: [];

    $permissions = [];
    foreach ($rolePerms as $rp) {
        $permissions[$rp['code']] = true;
    }

    // 2. Apply individual user overrides (Grants & Revokes)
    $overrides = db_fetch_all("
        SELECT p.code, up.is_granted
        FROM admin_user_permissions up
        JOIN admin_permissions p ON up.permission_id = p.id
        WHERE up.user_id = :uid
    ", ['uid' => $userId]) ?: [];

    foreach ($overrides as $ov) {
        if ((int)$ov['is_granted'] === 1) {
            $permissions[$ov['code']] = true;
        } else {
            unset($permissions[$ov['code']]);
        }
    }

    $effectivePermsCache[$userId] = array_keys($permissions);
    return $effectivePermsCache[$userId];
}

/**
 * Check if the currently logged-in user has a specific permission
 */
function has_permission(string $permissionCode, ?int $userId = null): bool {
    if ($userId === null) {
        $user = auth_user();
        if (!$user) {
            return false;
        }
        $userId = (int) $user['id'];
    }

    if (user_is_super_admin($userId)) {
        return true;
    }

    $perms = user_get_permissions($userId);
    return in_array($permissionCode, $perms, true);
}

/**
 * Check if user has ANY of the supplied permissions
 */
function user_has_any_permission(array $permissionCodes, ?int $userId = null): bool {
    if ($userId === null) {
        $user = auth_user();
        if (!$user) {
            return false;
        }
        $userId = (int) $user['id'];
    }

    if (user_is_super_admin($userId)) {
        return true;
    }

    $perms = user_get_permissions($userId);
    foreach ($permissionCodes as $code) {
        if (in_array($code, $perms, true)) {
            return true;
        }
    }
    return false;
}

/**
 * Enforce permission guard; sends HTTP 403 Access Denied if missing
 */
function require_permission(string $permissionCode, string $message = ''): void {
    require_admin_panel();
    $user = auth_user();
    if (!$user) {
        header('Location: ' . url('admin/login.php'));
        exit;
    }

    if (!has_permission($permissionCode, (int) $user['id'])) {
        $cleanMsg = $message ?: "Access restricted: Your assigned role does not grant permission [{$permissionCode}].";
        auth_access_denied($cleanMsg);
    }
}

/**
 * Get all available permissions grouped by functional module
 */
function get_all_grouped_permissions(): array {
    $rows = db_fetch_all("
        SELECT id, code, module, action, name, description
        FROM admin_permissions
        ORDER BY module ASC, id ASC
    ") ?: [];

    $grouped = [];
    foreach ($rows as $r) {
        $grouped[$r['module']][] = $r;
    }
    return $grouped;
}

/**
 * Get all roles with their assigned permission codes for frontend mapping
 */
function get_all_roles_with_permissions(): array {
    $roles = db_fetch_all("SELECT * FROM admin_roles ORDER BY is_system DESC, name ASC") ?: [];
    $result = [];

    foreach ($roles as $r) {
        $perms = db_fetch_all("
            SELECT p.code
            FROM admin_permissions p
            JOIN admin_role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = :rid
        ", ['rid' => $r['id']]) ?: [];

        $r['permission_codes'] = array_column($perms, 'code');
        $result[] = $r;
    }

    return $result;
}

/**
 * Sync roles, custom permission overrides, and department for an employee
 */
function sync_user_roles_and_permissions(int $userId, array $roleIds, array $customGrantedPermCodes = [], ?string $department = null): bool {
    // 1. Update department on users table if provided
    if ($department !== null) {
        db_update('users', ['department' => trim($department)], 'id = :id', ['id' => $userId]);
    }

    // 2. Sync admin_user_roles
    db_delete('admin_user_roles', 'user_id = :uid', ['uid' => $userId]);
    foreach ($roleIds as $rId) {
        $rId = (int) $rId;
        if ($rId > 0) {
            db_insert('admin_user_roles', [
                'user_id' => $userId,
                'role_id' => $rId
            ]);
        }
    }

    // 3. Sync admin_user_permissions (custom individual overrides)
    db_delete('admin_user_permissions', 'user_id = :uid', ['uid' => $userId]);

    // Fetch all permission IDs for granted codes
    if (!empty($customGrantedPermCodes)) {
        $inCodes = implode("','", array_map('addslashes', $customGrantedPermCodes));
        $permRecords = db_fetch_all("SELECT id, code FROM admin_permissions WHERE code IN ('{$inCodes}')") ?: [];
        
        // Also fetch permissions already granted by assigned roles
        $rolePermRecords = db_fetch_all("
            SELECT DISTINCT p.code
            FROM admin_permissions p
            JOIN admin_role_permissions rp ON p.id = rp.permission_id
            JOIN admin_user_roles ur ON rp.role_id = ur.role_id
            WHERE ur.user_id = :uid
        ", ['uid' => $userId]) ?: [];
        $rolePermCodes = array_column($rolePermRecords, 'code');

        foreach ($permRecords as $pr) {
            // Only add explicit override if not already provided by role
            if (!in_array($pr['code'], $rolePermCodes, true)) {
                db_insert('admin_user_permissions', [
                    'user_id'       => $userId,
                    'permission_id' => (int) $pr['id'],
                    'is_granted'    => 1
                ]);
            }
        }
    }

    return true;
}

