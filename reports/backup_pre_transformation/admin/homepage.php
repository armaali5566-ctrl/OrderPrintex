<?php
/**
 * OrderPrintex - Admin Homepage CMS
 * Manage Homepage Hero, Banners, Guarantees & Editorial Sections
 */

$adminTitle = "Homepage CMS & Layout Control";
require_once __DIR__ . '/header.php';

// Handle POST actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_homepage') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/homepage.php'));
        exit;
    }

    $fields = [
        // Hero Section
        'hero_badge'            => ['group' => 'homepage'],
        'hero_heading'          => ['group' => 'homepage'],
        'hero_description'      => ['group' => 'homepage'],
        'hero_cta_primary_text' => ['group' => 'homepage'],
        'hero_cta_primary_url'  => ['group' => 'homepage'],
        'hero_cta_sec_text'     => ['group' => 'homepage'],
        'hero_cta_sec_url'      => ['group' => 'homepage'],

        // Guarantees
        'guarantee_1_title'     => ['group' => 'homepage'],
        'guarantee_1_desc'      => ['group' => 'homepage'],
        'guarantee_2_title'     => ['group' => 'homepage'],
        'guarantee_2_desc'      => ['group' => 'homepage'],
        'guarantee_3_title'     => ['group' => 'homepage'],
        'guarantee_3_desc'      => ['group' => 'homepage'],

        // Products Section
        'home_products_label'   => ['group' => 'homepage'],
        'home_products_heading' => ['group' => 'homepage'],
        'home_products_sub'     => ['group' => 'homepage'],

        // Services Section
        'home_services_label'   => ['group' => 'homepage'],
        'home_services_heading' => ['group' => 'homepage'],
        'home_services_sub'     => ['group' => 'homepage'],

        // Why Choose Us 4 Pillars
        'why_pillar_1_title'    => ['group' => 'homepage'],
        'why_pillar_1_desc'     => ['group' => 'homepage'],
        'why_pillar_2_title'    => ['group' => 'homepage'],
        'why_pillar_2_desc'     => ['group' => 'homepage'],
        'why_pillar_3_title'    => ['group' => 'homepage'],
        'why_pillar_3_desc'     => ['group' => 'homepage'],
        'why_pillar_4_title'    => ['group' => 'homepage'],
        'why_pillar_4_desc'     => ['group' => 'homepage'],

        // Footer Copy
        'footer_about'          => ['group' => 'general']
    ];

    foreach ($fields as $key => $meta) {
        if (isset($_POST[$key])) {
            set_setting($key, trim($_POST[$key]), $meta['group']);
        }
    }

    log_activity($adminUser['id'], 'update_homepage_cms', "Updated homepage layout and hero content");
    set_flash('success', "Homepage CMS settings saved successfully. Changes are live on the storefront.");
    header('Location: ' . url('admin/homepage.php'));
    exit;
}
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Homepage CMS &amp; Layout
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Update homepage hero copy, editorial banners, guarantee badges, and section headings.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('') ?>" target="_blank" class="btn btn-outline btn-sm">
      👁 View Live Homepage &rarr;
    </a>
  </div>
</div>

<form method="POST" action="<?= url('admin/homepage.php') ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="save_homepage">

  <div style="display:flex; flex-direction:column; gap:1.75rem;">

    <!-- 1. Above the Fold Hero Section -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">01.</span> Homepage Hero Section (Above the Fold)
      </h3>

      <div style="display:flex; flex-direction:column; gap:1rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Eyebrow / Badge Label</label>
          <input type="text" name="hero_badge" class="form-control" value="<?= e(get_setting('hero_badge', "India's Direct-to-Film & UV DTF Specialists")) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Main Editorial Headline</label>
          <input type="text" name="hero_heading" class="form-control" style="font-size:1.1rem; font-weight:700;" value="<?= e(get_setting('hero_heading', 'Precision Print Transfers for Garments & Hard Surfaces')) ?>">
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Hero Subtitle / Description</label>
          <textarea name="hero_description" rows="3" class="form-control"><?= e(get_setting('hero_description', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India. We engineer press-ready transfers with vivid color gamut, durable washability, and rapid turnaround for apparel brands, promotional studios, and print businesses.')) ?></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Primary CTA Button Text</label>
            <input type="text" name="hero_cta_primary_text" class="form-control" value="<?= e(get_setting('hero_cta_primary_text', 'Upload Artwork & Order')) ?>">
          </div>
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Primary CTA Destination URL</label>
            <input type="text" name="hero_cta_primary_url" class="form-control" value="<?= e(get_setting('hero_cta_primary_url', 'products/')) ?>">
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Secondary CTA Button Text</label>
            <input type="text" name="hero_cta_sec_text" class="form-control" value="<?= e(get_setting('hero_cta_sec_text', 'Explore Services')) ?>">
          </div>
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy); margin-bottom:0.35rem;">Secondary CTA Destination URL</label>
            <input type="text" name="hero_cta_sec_url" class="form-control" value="<?= e(get_setting('hero_cta_sec_url', 'services/')) ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Technical Guarantees Bar -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">02.</span> Prepress Guarantees (3 Feature Pillars)
      </h3>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.25rem;">
        <div style="background:var(--ivory); padding:1rem; border-radius:6px; border:1px solid var(--border-light);">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 1 Title</label>
          <input type="text" name="guarantee_1_title" class="form-control" style="margin-bottom:0.5rem;" value="<?= e(get_setting('guarantee_1_title', '300 DPI Prepress Audit')) ?>">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 1 Description</label>
          <input type="text" name="guarantee_1_desc" class="form-control" value="<?= e(get_setting('guarantee_1_desc', 'Every vector and raster graphic audited by technicians prior to ink deposit.')) ?>">
        </div>

        <div style="background:var(--ivory); padding:1rem; border-radius:6px; border:1px solid var(--border-light);">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 2 Title</label>
          <input type="text" name="guarantee_2_title" class="form-control" style="margin-bottom:0.5rem;" value="<?= e(get_setting('guarantee_2_title', '50+ Commercial Wash Durability')) ?>">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 2 Description</label>
          <input type="text" name="guarantee_2_desc" class="form-control" value="<?= e(get_setting('guarantee_2_desc', 'Engineered with industrial polyurethane TPU adhesive and stretch polymers.')) ?>">
        </div>

        <div style="background:var(--ivory); padding:1rem; border-radius:6px; border:1px solid var(--border-light);">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 3 Title</label>
          <input type="text" name="guarantee_3_title" class="form-control" style="margin-bottom:0.5rem;" value="<?= e(get_setting('guarantee_3_title', '24-48H Fast Dispatch')) ?>">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Pillar 3 Description</label>
          <input type="text" name="guarantee_3_desc" class="form-control" value="<?= e(get_setting('guarantee_3_desc', 'Fast-turnaround dispatch across Delhi NCR and PAN-India courier networks.')) ?>">
        </div>
      </div>
    </div>

    <!-- 3. Section Headings -->
    <div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
      <h3 style="font-size:1.15rem; color:var(--navy); font-weight:800; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.65rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:var(--royal);">03.</span> Featured Section Titles
      </h3>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
        <div>
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--royal); margin-bottom:0.75rem;">Products Section</h4>
          <div style="margin-bottom:0.75rem;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Eyebrow</label>
            <input type="text" name="home_products_label" class="form-control" value="<?= e(get_setting('home_products_label', 'Direct Delhi Manufacturer')) ?>">
          </div>
          <div style="margin-bottom:0.75rem;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Heading</label>
            <input type="text" name="home_products_heading" class="form-control" value="<?= e(get_setting('home_products_heading', 'Popular Printing Products')) ?>">
          </div>
        </div>

        <div>
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--royal); margin-bottom:0.75rem;">Services Section</h4>
          <div style="margin-bottom:0.75rem;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Eyebrow</label>
            <input type="text" name="home_services_label" class="form-control" value="<?= e(get_setting('home_services_label', 'Comprehensive Print Capabilities')) ?>">
          </div>
          <div style="margin-bottom:0.75rem;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Heading</label>
            <input type="text" name="home_services_heading" class="form-control" value="<?= e(get_setting('home_services_heading', 'Contract Printing Services')) ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Button Bar -->
    <div style="position:sticky; bottom:1rem; z-index:100; background:var(--bg-surface); border:1px solid var(--border-color); border-radius:8px; padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 8px 24px rgba(15, 25, 45, 0.15);">
      <span style="font-size:0.85rem; color:var(--slate);">Publish updates to live website immediately upon saving.</span>
      <button type="submit" class="btn btn-primary btn-md">
        <span>Save Homepage Changes</span> &rarr;
      </button>
    </div>

  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
