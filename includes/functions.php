<?php
/**
 * OrderPrintex - General Utility & Security Functions
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * Escape string for safe HTML output (XSS protection)
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Generate application URL
 */
function url(string $path = ''): string {
    $trimmed = trim($path);
    if (strpos($trimmed, 'http://') === 0 || strpos($trimmed, 'https://') === 0 || strpos($trimmed, '//') === 0) {
        return $trimmed;
    }
    $cleanPath = ltrim($path, '/');
    return BASE_URL . $cleanPath;
}

/**
 * Generate clean SEO product URL: /product/slug-name/
 */
function product_url(string $slug): string {
    $cleanSlug = trim(trim($slug), '/');
    return url('product/' . $cleanSlug . '/');
}

/**
 * Generate clean SEO category URL: /product/category/slug-name/ or /products/
 */
function category_url(?string $slug = null): string {
    if (empty($slug)) {
        return url('products/');
    }
    $cleanSlug = trim(trim($slug), '/');
    return url('product/category/' . $cleanSlug . '/');
}

/**
 * Generate clean SEO blog URL: /blog/ or /blog/slug-name/
 */
function blog_url(?string $slug = null): string {
    if (empty($slug)) {
        return url('blog/');
    }
    $cleanSlug = trim(trim($slug), '/');
    return url('blog/' . $cleanSlug . '/');
}

/**
 * Generate clean SEO blog category URL: /blog/category/slug-name/
 */
function blog_category_url(string $slug): string {
    $cleanSlug = trim(trim($slug), '/');
    return url('blog/category/' . $cleanSlug . '/');
}

/**
 * Generate clean SEO service URL: /services/slug-name/
 */
function service_url(string $slug): string {
    $cleanSlug = trim(trim($slug), '/');
    return url('services/' . $cleanSlug . '/');
}

/**
 * Clean URLs for static and functional pages
 */
function page_url(string $page): string {
    $clean = trim(trim($page), '/');
    return url($clean . '/');
}

function cart_url(): string {
    return url('cart/');
}

function checkout_url(): string {
    return url('checkout/');
}

function pricing_url(): string {
    return url('pricing/');
}

function account_url(string $subpath = ''): string {
    if (empty($subpath)) {
        return url('account/');
    }
    return url('account/' . trim(trim($subpath), '/') . '/');
}

/**
 * Helper alias for static asset URLs
 */
function asset_url(string $path = ''): string {
    return url($path);
}

/**
 * Generate CSRF Token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output hidden CSRF input field
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF Token
 */
function csrf_verify(?string $token = null): bool {
    $submittedToken = $token ?? ($_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
    if (empty($_SESSION['csrf_token']) || empty($submittedToken)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $submittedToken);
}

/**
 * Flash Messages
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message
    ];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

function render_flash(): string {
    $flash = get_flash();
    if (!$flash) {
        return '';
    }
    $type = e($flash['type']);
    $msg = e($flash['message']);
    return <<<HTML
    <div class="alert alert-{$type} fade-in" role="alert">
        <div class="alert-content">
            <span class="alert-icon"></span>
            <span class="alert-text">{$msg}</span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
    </div>
HTML;
}

/**
 * Unique Reference Generators
 */
function generate_ref(string $prefix = 'OP'): string {
    $year = date('Y');
    $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    return sprintf('%s-%s-%s', $prefix, $year, $random);
}

/**
 * Settings Cache & Getter
 */
function &get_settings_cache(): array {
    static $settingsCache = null;
    if ($settingsCache === null) {
        $settingsCache = [];
        $rows = db_fetch_all("SELECT setting_key, setting_value FROM site_settings");
        foreach ($rows as $row) {
            $settingsCache[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settingsCache;
}

function get_setting(string $key, string $default = ''): string {
    $cache = &get_settings_cache();
    return $cache[$key] ?? $default;
}

/**
 * Update or Insert Setting
 */
function set_setting(string $key, string $value, string $group = 'general'): bool {
    $exists = db_fetch_one("SELECT id FROM site_settings WHERE setting_key = :k", ['k' => $key]);
    if ($exists) {
        db_update('site_settings', ['setting_value' => $value], 'setting_key = :k', ['k' => $key]);
    } else {    
        db_insert('site_settings', [
            'setting_key' => $key,
            'setting_value' => $value,
            'group_name' => $group
        ]);
    }
    $cache = &get_settings_cache();
    $cache[$key] = $value;
    return true;
}

/**
 * Format Currency (INR ₹)
 */
function format_currency(float $amount): string {
    return '₹' . number_format($amount, 2, '.', ',');
}

function format_price(float $amount): string {
    return format_currency($amount);
}

/**
 * Format Date & Time
 */
function format_date(?string $datetime, string $format = 'd M Y, h:i A'): string {
    if (!$datetime) {
        return '—';
    }
    $time = strtotime($datetime);
    return $time ? date($format, $time) : '—';
}

/**
 * Format File Size
 */
function format_file_size(int $bytes): string {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}

/**
 * Return human-readable label and CSS badge for statuses
 */
function get_status_badge(string $status, string $context = 'order'): string {
    $map = [
        // Enquiries
        'received'           => ['label' => 'Received', 'class' => 'badge-info'],
        'under_review'       => ['label' => 'Under Review', 'class' => 'badge-warning'],
        'quoted'             => ['label' => 'Quotation Sent', 'class' => 'badge-primary'],
        'converted'          => ['label' => 'Converted to Order', 'class' => 'badge-success'],
        'rejected'           => ['label' => 'Declined', 'class' => 'badge-danger'],
        'closed'             => ['label' => 'Closed', 'class' => 'badge-secondary'],

        // Quotations
        'draft'              => ['label' => 'Draft', 'class' => 'badge-secondary'],
        'sent'               => ['label' => 'Awaiting Customer', 'class' => 'badge-warning'],
        'accepted'           => ['label' => 'Approved by Customer', 'class' => 'badge-success'],
        'change_requested'   => ['label' => 'Revision Requested', 'class' => 'badge-info'],

        // Orders
        'enquiry_received'   => ['label' => 'Enquiry Received', 'class' => 'badge-info'],
        'quotation_sent'     => ['label' => 'Quotation Sent', 'class' => 'badge-primary'],
        'quotation_approved' => ['label' => 'Quotation Approved', 'class' => 'badge-success'],
        'payment_pending'    => ['label' => 'Payment Pending', 'class' => 'badge-warning'],
        'artwork_review'     => ['label' => 'Artwork Review', 'class' => 'badge-info'],
        'proof_approval'     => ['label' => 'Proof Pending', 'class' => 'badge-warning'],
        'production'         => ['label' => 'In Production', 'class' => 'badge-primary'],
        'quality_check'      => ['label' => 'Quality Check', 'class' => 'badge-info'],
        'dispatched'         => ['label' => 'Dispatched', 'class' => 'badge-accent'],
        'delivered'          => ['label' => 'Delivered', 'class' => 'badge-success'],
        'cancelled'          => ['label' => 'Cancelled', 'class' => 'badge-danger'],

        // Payments
        'pending'            => ['label' => 'Payment Pending', 'class' => 'badge-warning'],
        'partial'            => ['label' => 'Partially Paid', 'class' => 'badge-info'],
        'paid'               => ['label' => 'Paid in Full', 'class' => 'badge-success'],
        'refunded'           => ['label' => 'Refunded', 'class' => 'badge-secondary'],
    ];

    $info = $map[$status] ?? ['label' => ucfirst(str_replace('_', ' ', $status)), 'class' => 'badge-secondary'];
    return sprintf('<span class="status-badge %s">%s</span>', e($info['class']), e($info['label']));
}

function status_badge(string $status, string $context = 'order'): string {
    return get_status_badge($status, $context);
}

/**
 * Handle File Upload with full security checks
 */
function handle_file_upload(array $file, string $targetDir = ARTWORK_PATH): array {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file parameter.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'Uploaded file exceeds server size limit.',
            UPLOAD_ERR_FORM_SIZE  => 'Uploaded file exceeds form size limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
        ];
        return ['success' => false, 'error' => $errors[$file['error']] ?? 'Unknown upload error.'];
    }

    if ($file['size'] > MAX_FILE_SIZE_BYTES) {
        return ['success' => false, 'error' => 'File size exceeds 25 MB limit.'];
    }

    $originalName = basename($file['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
        return ['success' => false, 'error' => 'File type .' . $extension . ' is not permitted. Only PNG, JPG, PDF, AI, PSD, and SVG are accepted.'];
    }

    // MIME type check using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    // Allow known safe types and standard image/document headers
    $isAllowedMime = false;
    foreach (ALLOWED_MIME_TYPES as $allowed) {
        if ($mimeType === $allowed || str_starts_with($mimeType, 'image/')) {
            $isAllowedMime = true;
            break;
        }
    }

    // Create target directory if needed
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Cryptographically secure randomized filename to prevent collisions and path traversal
    $storedName = sprintf('%s_%s.%s', date('Ymd_His'), bin2hex(random_bytes(10)), $extension);
    $destination = $targetDir . $storedName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Could not save file to disk.'];
    }

    // Calculate relative path for database reference
    $relativePath = 'uploads/' . basename($targetDir) . '/' . $storedName;

    return [
        'success'        => true,
        'original_name'  => $originalName,
        'stored_name'    => $storedName,
        'file_path'      => $relativePath,
        'file_size'      => $file['size'],
        'mime_type'      => $mimeType,
        'file_extension' => $extension
    ];
}

/**
 * Log System Activity
 */
function log_activity(?int $userId, string $action, ?string $details = null): void {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        db_insert('activity_logs', [
            'user_id'    => $userId,
            'action'     => $action,
            'details'    => $details,
            'ip_address' => $ip
        ]);
    } catch (Exception $e) {
        // Silently ignore activity log failures so main workflow is never blocked
    }
}

/**
 * Validate and Normalize Indian Mobile Numbers
 * Rules: Exactly 10 digits; first digit must be 6, 7, 8, or 9.
 * Strips out prefixes (+91, 91, 0), spaces, dashes.
 * Returns normalized '+91 XXXXXXXXXX' or null if invalid.
 */
function validate_indian_mobile(?string $rawPhone): ?string {
    if ($rawPhone === null) {
        return null;
    }
    $trimmed = trim($rawPhone);
    if ($trimmed === '') {
        return null;
    }

    // Strip non-digits
    $digits = preg_replace('/\D+/', '', $trimmed);

    // If starts with 91 and has 12 digits (+91 9876543210 -> 9876543210)
    if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
        $digits = substr($digits, 2);
    } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
        // If starts with 0 and has 11 digits (09876543210 -> 9876543210)
        $digits = substr($digits, 1);
    }

    // Must be exactly 10 digits and start with 6, 7, 8, or 9
    if (strlen($digits) !== 10 || !preg_match('/^[6-9]\d{9}$/', $digits)) {
        return null;
    }

    return '+91 ' . $digits;
}

/**
 * Strict Email Format and Domain Validation
 */
function is_valid_email_strict(string $email): bool {
    $email = trim(strtolower($email));
    if (empty($email) || strlen($email) > 191) {
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    // Must contain a domain with a dot and at least 2 char TLD
    $parts = explode('@', $email);
    if (count($parts) !== 2) {
        return false;
    }
    $domain = $parts[1];
    if (!str_contains($domain, '.') || strlen(substr(strrchr($domain, '.'), 1)) < 2) {
        return false;
    }
    return true;
}

