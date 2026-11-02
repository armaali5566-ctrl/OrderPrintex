<?php
/**
 * OrderPrintex - Admin Website & Studio Settings
 */

$adminTitle = "Website & Business Settings";
require_once __DIR__ . '/header.php';

// Handle Saving Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_settings') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/settings.php'));
        exit;
    }

    $settingsMap = [
        // Studio & Contact
        'site_name'        => 'general',
        'site_tagline'     => 'general',
        'phone'            => 'contact',
        'phone_raw'        => 'contact',
        'whatsapp'         => 'contact',
        'whatsapp_url'     => 'contact',
        'email'            => 'contact',
        'address'          => 'contact',
        'google_maps_url'  => 'contact',
        'operating_hours'  => 'contact',

        // Social Media
        'instagram_url'    => 'social',
        'youtube_url'      => 'social',
        'facebook_url'     => 'social',

        // Commerce & Quotation Defaults
        'default_gst_rate' => 'commerce',
        'default_delivery' => 'commerce',
        'quote_validity'   => 'commerce'
    ];

    foreach ($settingsMap as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim($_POST[$key]), $group);
        }
    }

    // If phone raw is not provided, sanitize from phone
    if (!empty($_POST['phone'])) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $_POST['phone']);
        set_setting('phone_raw', $cleanPhone, 'contact');
    }

    if (!empty($_POST['whatsapp'])) {
        $cleanWa = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);
        set_setting('whatsapp_url', 'https://wa.me/' . $cleanWa, 'contact');
    }

    log_activity($adminUser['id'], 'update_settings', "Updated studio contact and business settings");
    set_flash('success', "Website settings updated successfully.");
    header('Location: ' . url('admin/settings.php'));
    exit;
}
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Website & Studio Settings</h1>
        <p class="admin-page-subtitle">Configure verified business contact channels, Delhi studio address, WhatsApp integration, and commercial parameters.</p>
    </div>
</div>

<form method="POST" action="<?= url('admin/settings.php') ?>">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="action" value="save_settings">

    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <!-- 1. Studio Identification & Contact -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">1. Verified Studio Contact Details</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label required">Business / Brand Name</label>
                        <input type="text" name="site_name" class="form-input" required 
                               value="<?= e(get_setting('site_name', 'OrderPrintex')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Brand Tagline</label>
                        <input type="text" name="site_tagline" class="form-input" 
                               value="<?= e(get_setting('site_tagline', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India')) ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label required">Primary Telephone</label>
                        <input type="text" name="phone" class="form-input" required 
                               value="<?= e(get_setting('phone', '+91 93540 28199')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label required">WhatsApp Direct Number</label>
                        <input type="text" name="whatsapp" class="form-input" required 
                               value="<?= e(get_setting('whatsapp', '+91 93540 28199')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Sales Email Address</label>
                        <input type="email" name="email" class="form-input" required 
                               value="<?= e(get_setting('email', 'sales@orderprintex.com')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Official Studio Workshop Address</label>
                    <textarea name="address" class="form-input" rows="2" required><?= e(get_setting('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059')) ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Google Maps Location Link</label>
                        <input type="url" name="google_maps_url" class="form-input" 
                               value="<?= e(get_setting('google_maps_url', 'https://share.google/MWOVYne70asGLsYXn')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Operating Workshop Hours</label>
                        <input type="text" name="operating_hours" class="form-input" 
                               value="<?= e(get_setting('operating_hours', 'Mon – Sat: 10:00 AM – 7:30 PM (Sunday Closed)')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Commercial Defaults -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">2. Commercial & Quotation Defaults</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Standard GST Rate (%)</label>
                        <input type="number" step="0.1" name="default_gst_rate" class="form-input" 
                               value="<?= e(get_setting('default_gst_rate', '18.0')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Default Shipping Fee (₹)</label>
                        <input type="number" step="0.01" name="default_delivery" class="form-input" 
                               value="<?= e(get_setting('default_delivery', '0.00')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quotation Validity Period (Days)</label>
                        <input type="number" name="quote_validity" class="form-input" 
                               value="<?= e(get_setting('quote_validity', '15')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Social Media & Channels -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">3. Official Social Channels</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Instagram Profile URL</label>
                        <input type="url" name="instagram_url" class="form-input" 
                               value="<?= e(get_setting('instagram_url', 'https://www.instagram.com/order_printex/?hl=en')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" class="form-input" 
                               value="<?= e(get_setting('youtube_url', 'https://www.youtube.com/@PRINTEX_Delhi')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 1rem; font-weight: 700;">
                💾 Save Studio Settings
            </button>
        </div>

    </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
