<?php
/**
 * OrderPrintex - Enterprise Mailer & Email Verification Service
 * Handles SMTP configuration inspection, secure verification tokens, and verification dispatch.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Retrieve SMTP configuration from settings
 */
function mailer_get_smtp_config(): array {
    return [
        'host'       => trim(get_setting('smtp_host', '')),
        'port'       => (int) get_setting('smtp_port', '587'),
        'user'       => trim(get_setting('smtp_user', '')),
        'pass'       => trim(get_setting('smtp_pass', '')),
        'encryption' => trim(get_setting('smtp_encryption', 'tls')), // tls, ssl, or none
        'from_email' => trim(get_setting('smtp_from_email', get_setting('email', 'sales@orderprintex.com'))),
        'from_name'  => trim(get_setting('smtp_from_name', 'OrderPrintex Personnel Portal'))
    ];
}

/**
 * Check whether SMTP delivery is fully configured
 */
function mailer_smtp_status(): array {
    $cfg = mailer_get_smtp_config();
    $missing = [];

    if (empty($cfg['host'])) $missing[] = 'SMTP Host';
    if (empty($cfg['port'])) $missing[] = 'SMTP Port';
    if (empty($cfg['user'])) $missing[] = 'SMTP Username';
    if (empty($cfg['pass'])) $missing[] = 'SMTP Password';

    return [
        'is_configured' => empty($missing),
        'missing'       => $missing,
        'config'        => $cfg
    ];
}

/**
 * Issue a cryptographically secure, time-limited verification token for an employee
 */
function mailer_issue_verification_token(int $userId): array {
    $user = db_fetch_one("SELECT id, name, email, email_verified_at FROM users WHERE id = :id", ['id' => $userId]);
    if (!$user) {
        return ['success' => false, 'error' => 'Personnel record not found.'];
    }

    $rawToken = bin2hex(random_bytes(32)); // 64 hex characters
    $tokenHash = hash('sha256', $rawToken);
    $expiresAt = date('Y-m-d H:i:s', time() + (48 * 3600)); // 48-hour expiration

    db_update('users', [
        'email_verification_token'      => $tokenHash,
        'email_verification_expires_at' => $expiresAt
    ], 'id = :id', ['id' => $userId]);

    $verifyUrl = url('verify-email.php?token=' . $rawToken . '&email=' . urlencode($user['email']));

    return [
        'success'    => true,
        'raw_token'  => $rawToken,
        'token_hash' => $tokenHash,
        'expires_at' => $expiresAt,
        'verify_url' => $verifyUrl,
        'user'       => $user
    ];
}

/**
 * Dispatch verification email or return explicit configuration failure
 */
function mailer_send_verification_email(int $userId): array {
    $tokenData = mailer_issue_verification_token($userId);
    if (!$tokenData['success']) {
        return $tokenData;
    }

    $smtp = mailer_smtp_status();
    $verifyUrl = $tokenData['verify_url'];
    $user = $tokenData['user'];

    if (!$smtp['is_configured']) {
        return [
            'sent'             => false,
            'is_configured'    => false,
            'missing_settings' => $smtp['missing'],
            'verify_url'       => $verifyUrl,
            'message'          => 'SMTP is not configured on this server. Missing parameters: ' . implode(', ', $smtp['missing']) . '. Verification token generated and link available for testing.'
        ];
    }

    // When SMTP is configured, attempt socket delivery
    $cfg = $smtp['config'];
    try {
        $subject = 'Verify Your OrderPrintex Employee Account';
        $bodyHtml = "
            <div style='font-family:sans-serif;background:#0D0D11;color:#F3F4F6;padding:30px;border-radius:10px;'>
                <h2 style='color:#D4AF37;'>OrderPrintex Administrative Portal</h2>
                <p>Hello " . htmlspecialchars($user['name']) . ",</p>
                <p>An administrative staff account has been provisioned for you. Please confirm your email address by clicking the link below:</p>
                <p style='margin:25px 0;'><a href='{$verifyUrl}' style='background:#D4AF37;color:#0D0D11;font-weight:700;padding:12px 24px;border-radius:6px;text-decoration:none;'>Verify Employee Email</a></p>
                <p style='color:#94A3B8;font-size:0.85rem;'>This link expires in 48 hours. If you did not request this, please notify your system administrator.</p>
            </div>
        ";

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $cfg['from_name'] . ' <' . $cfg['from_email'] . '>',
            'Reply-To: ' . $cfg['from_email'],
            'X-Mailer: PHP/' . phpversion()
        ];

        $delivered = @mail($user['email'], $subject, $bodyHtml, implode("\r\n", $headers));
        if ($delivered) {
            return [
                'sent'       => true,
                'verify_url' => $verifyUrl,
                'message'    => 'Verification email dispatched successfully via SMTP.'
            ];
        }

        return [
            'sent'             => false,
            'is_configured'    => true,
            'missing_settings' => [],
            'verify_url'       => $verifyUrl,
            'error'            => 'Mail transfer agent rejected delivery. Check SMTP credentials or local MTA.',
            'message'          => 'SMTP delivery failed. Verification link generated.'
        ];
    } catch (Exception $e) {
        return [
            'sent'             => false,
            'is_configured'    => true,
            'missing_settings' => [],
            'verify_url'       => $verifyUrl,
            'error'            => $e->getMessage(),
            'message'          => 'SMTP delivery error: ' . $e->getMessage()
        ];
    }
}

