<?php
/**
 * OrderPrintex - Admin Website & Studio Settings
 * Complete Control Over General, Contact, Sticky Top Social Bar & SEO Settings
 */

$adminTitle = "Website & Business Settings";
require_once __DIR__ . '/header.php';

// Handle Saving Settings
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_settings') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/settings.php'));
        exit;
    }

    $settingsMap = [
        // General Brand Settings
        'site_name'        => 'general',
        'site_tagline'     => 'general',
        'site_logo'        => 'general',
        'footer_about'     => 'general',

        // Studio Contact & Top Bar
        'top_bar_enabled'  => 'top_bar',
        'phone'            => 'contact',
        'phone_raw'        => 'contact',
        'whatsapp'         => 'contact',
        'whatsapp_raw'     => 'contact',
        'whatsapp_url'     => 'contact',
        'whatsapp_prefill' => 'contact',
        'email'            => 'contact',
        'address'          => 'contact',
        'google_maps_url'  => 'contact',
        'operating_hours'  => 'contact',
        'business_hours'   => 'contact',

        // Social Media Channels
        'social_instagram' => 'social',
        'instagram_url'    => 'social',
        'social_facebook'  => 'social',
        'facebook_url'     => 'social',
        'social_youtube'   => 'social',
        'youtube_url'      => 'social',
        'social_linkedin'  => 'social',
        'linkedin_url'     => 'social',

        // SEO Defaults
        'meta_title'       => 'seo',
        'meta_description' => 'seo',
        'meta_og_image'    => 'seo',

        // Commercial Defaults
        'default_gst_rate' => 'commerce',
        'default_delivery' => 'commerce',
        'quote_validity'   => 'commerce'
    ];

    foreach ($settingsMap as $key => $group) {
        if (isset($_POST[$key])) {
            set_setting($key, trim($_POST[$key]), $group);
        }
    }

    // Top bar checkbox toggle
    set_setting('top_bar_enabled', isset($_POST['top_bar_enabled']) ? '1' : '0', 'top_bar');

    // Phone & WhatsApp sanitization
    if (!empty($_POST['phone'])) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $_POST['phone']);
        set_setting('phone_raw', $cleanPhone, 'contact');
    }

    if (!empty($_POST['whatsapp'])) {
        $cleanWa = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);
        set_setting('whatsapp_raw', $cleanWa, 'contact');
        $prefill = !empty($_POST['whatsapp_prefill']) ? urlencode(trim($_POST['whatsapp_prefill'])) : 'Hi%20OrderPrintex,%20I%20am%20inquiry%20about%20printing%20services.';
        set_setting('whatsapp_url', 'https://wa.me/' . $cleanWa . '?text=' . $prefill, 'contact');
    }

    // Sync redundant social keys for complete template backward compatibility
    if (isset($_POST['social_instagram'])) set_setting('instagram_url', trim($_POST['social_instagram']), 'social');
    if (isset($_POST['social_facebook']))  set_setting('facebook_url', trim($_POST['social_facebook']), 'social');
    if (isset($_POST['social_youtube']))   set_setting('youtube_url', trim($_POST['social_youtube']), 'social');
    if (isset($_POST['social_linkedin']))  set_setting('linkedin_url', trim($_POST['social_linkedin']), 'social');

    log_activity($adminUser['id'], 'update_settings', "Updated studio contact, sticky top bar, social and SEO settings");
    set_flash('success', "Website settings updated successfully. Top contact bar and social links updated storefront-wide.");
    header('Location: ' . url('admin/settings.php'));
    exit;
}
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Website &amp; Business Settings
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Configure studio contact channels, sticky top bar, social media links, SEO defaults, and commercial rates.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('') ?>" target="_blank" class="btn btn-outline btn-sm">
      👁 View Live Storefront &rarr;
    </a>
  </div>
</div>

<form method="POST" action="<?= url('admin/settings.php') ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="save_settings">

  <div style="display:flex; flex-direction:column; gap:1.75rem;">

    <!-- 1. Sticky Top Contact & Social Bar Settings (Requirement 2 & 3) -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem;">
        <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin:0; display:flex; align-items:center; gap:0.5rem;">
          <span style="color:var(--royal);">01.</span> Sticky Top Contact &amp; Social Bar
        </h3>
        <label style="display:inline-flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.85rem; color:var(--navy);">
          <input type="checkbox" name="top_bar_enabled" value="1" <?= get_setting('top_bar_enabled', '1') == '1' ? 'checked' : '' ?>>
          <span>Enable Sticky Top Bar on Public Site</span>
        </label>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
            Sales / Customer Support Email <span style="color:var(--crimson);">*</span>
          </label>
          <input type="email" name="email" required class="form-control" value="<?= e(get_setting('email', 'sales@orderprintex.com')) ?>">
          <small style="color:var(--text-muted); font-size:0.75rem;">Displayed in top bar and clickable (mailto: link).</small>
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
            Primary Telephone / Support Line <span style="color:var(--crimson);">*</span>
          </label>
          <input type="text" name="phone" required class="form-control" value="<?= e(get_setting('phone', '+91 93540 28199')) ?>">
          <small style="color:var(--text-muted); font-size:0.75rem;">Displayed in top bar and clickable (tel: link).</small>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
            WhatsApp Support Number <span style="color:var(--crimson);">*</span>
          </label>
          <input type="text" name="whatsapp" required class="form-control" value="<?= e(get_setting('whatsapp', '+91 93540 28199')) ?>">
          <small style="color:var(--text-muted); font-size:0.75rem;">Used for direct WhatsApp chat icon in the sticky top bar.</small>
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">
            WhatsApp Pre-filled Greeting
          </label>
          <input type="text" name="whatsapp_prefill" class="form-control" placeholder="Hi OrderPrintex, I have an inquiry about print services." value="<?= e(get_setting('whatsapp_prefill', 'Hi OrderPrintex, I would like to inquire about printing services.')) ?>">
        </div>
      </div>

      <h4 style="font-size:0.95rem; font-weight:700; color:var(--navy); margin:1.25rem 0 0.75rem; border-top:1px dashed var(--border-light); padding-top:1rem;">
        Social Media URLs (Displayed as clean icons in top bar &amp; footer)
      </h4>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div>
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Instagram Profile URL</label>
          <input type="url" name="social_instagram" class="form-control" placeholder="https://instagram.com/..." value="<?= e(get_setting('social_instagram', get_setting('instagram_url', 'https://www.instagram.com/order_printex/?hl=en'))) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Facebook Page URL</label>
          <input type="url" name="social_facebook" class="form-control" placeholder="https://facebook.com/..." value="<?= e(get_setting('social_facebook', get_setting('facebook_url', 'https://www.facebook.com/OrderPrintexDelhi/'))) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">YouTube Channel URL</label>
          <input type="url" name="social_youtube" class="form-control" placeholder="https://youtube.com/..." value="<?= e(get_setting('social_youtube', get_setting('youtube_url', 'https://www.youtube.com/@PRINTEX_Delhi'))) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">LinkedIn Page URL</label>
          <input type="url" name="social_linkedin" class="form-control" placeholder="https://linkedin.com/company/..." value="<?= e(get_setting('social_linkedin', get_setting('linkedin_url', 'https://www.linkedin.com/company/orderprintex/'))) ?>">
        </div>
      </div>
    </div>

    <!-- 2. Studio Identification & Physical Location -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">02.</span> Business Identification &amp; Workshop Address
      </h3>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Brand / Business Name</label>
          <input type="text" name="site_name" class="form-control" value="<?= e(get_setting('site_name', 'OrderPrintex')) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Brand Tagline</label>
          <input type="text" name="site_tagline" class="form-control" value="<?= e(get_setting('site_tagline', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India')) ?>">
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Official Workshop Address</label>
        <textarea name="address" class="form-control" rows="2"><?= e(get_setting('address', 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059')) ?></textarea>
      </div>

      <div style="display:grid; grid-template-columns:1.5fr 1fr; gap:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Google Maps Location URL</label>
          <input type="url" name="google_maps_url" class="form-control" value="<?= e(get_setting('google_maps_url', 'https://share.google/MWOVYne70asGLsYXn')) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Operating Hours</label>
          <input type="text" name="operating_hours" class="form-control" value="<?= e(get_setting('operating_hours', get_setting('business_hours', 'Mon – Sat: 10:00 AM – 7:30 PM (Sunday Closed)'))) ?>">
        </div>
      </div>
    </div>

    <!-- 3. Storewide SEO Defaults -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">03.</span> Default SEO &amp; OpenGraph Metadata
      </h3>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Default Site Meta Title</label>
        <input type="text" name="meta_title" class="form-control" value="<?= e(get_setting('meta_title', 'OrderPrintex | Premier Industrial DTF & UV DTF Printing Manufacturer Delhi')) ?>">
      </div>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Default Meta Description</label>
        <textarea name="meta_description" rows="2" class="form-control"><?= e(get_setting('meta_description', 'OrderPrintex is India\'s premier direct manufacturer of industrial DTF and UV DTF heat transfers, custom stickers, apparel, and corporate merchandise.')) ?></textarea>
      </div>

      <div>
        <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Default Social Share Image URL</label>
        <input type="text" name="meta_og_image" class="form-control" placeholder="assets/images/og_banner.jpg" value="<?= e(get_setting('meta_og_image', 'assets/images/gallery_1.jpg')) ?>">
      </div>
    </div>

    <!-- 4. Commercial & Quotation Parameters -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">04.</span> Commercial &amp; Tax Defaults
      </h3>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Standard GST Rate (%)</label>
          <input type="number" step="0.1" name="default_gst_rate" class="form-control" value="<?= e(get_setting('default_gst_rate', '18.0')) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Default Base Shipping (₹)</label>
          <input type="number" step="0.01" name="default_delivery" class="form-control" value="<?= e(get_setting('default_delivery', '0.00')) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Quote Validity (Days)</label>
          <input type="number" name="quote_validity" class="form-control" value="<?= e(get_setting('quote_validity', '15')) ?>">
        </div>
      </div>
    </div>

    <!-- Sticky Save Button -->
    <div style="position:sticky; bottom:1rem; z-index:100; background:var(--bg-surface); border:1px solid var(--border-color); border-radius:8px; padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 8px 24px rgba(15, 25, 45, 0.15);">
      <span style="font-size:0.85rem; color:var(--slate);">All changes apply instantly to the sticky top bar and public website.</span>
      <button type="submit" class="btn btn-primary btn-md">
        <span>Save All Settings</span> &rarr;
      </button>
    </div>

  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
