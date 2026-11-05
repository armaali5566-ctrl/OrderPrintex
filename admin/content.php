<?php
/**
 * OrderPrintex - Admin Website Content Management
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_permission('content.manage');
$adminUser = auth_user();

// Handle Saving Content Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_content') {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/content.php'));
        exit;
    }

    $fields = [
        // Hero Section
        'hero_badge'        => ['group' => 'homepage'],
        'hero_heading'      => ['group' => 'homepage'],
        'hero_description'  => ['group' => 'homepage'],

        // Prepress & Turnaround Badges
        'prepress_info'     => ['group' => 'technical'],
        'turnaround_info'   => ['group' => 'shipping'],

        // Why Choose Us Pillars
        'why_pillar_1_title' => ['group' => 'about'],
        'why_pillar_1_desc'  => ['group' => 'about'],
        'why_pillar_2_title' => ['group' => 'about'],
        'why_pillar_2_desc'  => ['group' => 'about'],
        'why_pillar_3_title' => ['group' => 'about'],
        'why_pillar_3_desc'  => ['group' => 'about'],
        'why_pillar_4_title' => ['group' => 'about'],
        'why_pillar_4_desc'  => ['group' => 'about'],

        // Footer Description
        'footer_about'      => ['group' => 'general']
    ];

    foreach ($fields as $key => $meta) {
        if (isset($_POST[$key])) {
            set_setting($key, trim($_POST[$key]), $meta['group']);
        }
    }

    log_activity($adminUser['id'], 'update_content', "Updated editorial and homepage website content");
    set_flash('success', "Website content updated successfully. Changes are live across storefront.");
    header('Location: ' . url('admin/content.php'));
    exit;
}

$adminTitle = "Manage Website Copy & Editorial Content";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Manage Website Editorial Content</h1>
        <p class="admin-page-subtitle">Update homepage headlines, studio guarantees, prepress badges, and editorial copy without modifying template code.</p>
    </div>
    <div class="admin-topbar-actions">
        <a href="<?= url() ?>" target="_blank" class="btn btn-outline btn-sm">
            👁 View Live Website &rarr;
        </a>
    </div>
</div>

<form method="POST" action="<?= url('admin/content.php') ?>">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="action" value="save_content">

    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <!-- 1. Hero Section Content -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">1. Homepage Hero Section (Above the Fold)</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Top Highlight Badge Label</label>
                    <input type="text" name="hero_badge" class="form-input" 
                           value="<?= e(get_setting('hero_badge', "India's Direct-to-Film & UV DTF Specialists")) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Main Editorial Headline</label>
                    <input type="text" name="hero_heading" class="form-input" style="font-size: 1.1rem; font-weight: 600;" 
                           value="<?= e(get_setting('hero_heading', 'Precision Print Transfers for Garments & Hard Surfaces')) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Hero Introduction Paragraph</label>
                    <textarea name="hero_description" class="form-input" rows="3"><?= e(get_setting('hero_description', 'Manufacturer & Bulk Supplier of DTF and UV DTF in India. We engineer press-ready transfers with vivid color gamut, durable washability, and rapid turnaround for apparel brands, promotional studios, and print businesses.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- 2. Technical Guarantees & Turnaround -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">2. Quality Assurance & Prepress Badges</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px;">
                <div class="admin-form-2col">
                    <div class="form-group">
                        <label class="form-label">Prepress Standards Note</label>
                        <input type="text" name="prepress_info" class="form-input" 
                               value="<?= e(get_setting('prepress_info', 'Pre-cured, color-calibrated ICC profiles, 300 DPI high-res output.')) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Production & Turnaround Guarantee</label>
                        <input type="text" name="turnaround_info" class="form-input" 
                               value="<?= e(get_setting('turnaround_info', '1–3 business days typical turnaround after proof approval.')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Why Choose OrderPrintex Pillars -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">3. "Why Choose OrderPrintex" Value Pillars</h2>
            </div>
            <div class="admin-card-body admin-split-grid" style="padding: 24px;">
                
                <!-- Pillar 1 -->
                <div style="background: var(--color-ivory); padding: 16px; border-radius: 6px; border: 1px solid var(--color-border);">
                    <div class="form-group">
                        <label class="form-label">Pillar 1 Title</label>
                        <input type="text" name="why_pillar_1_title" class="form-input" 
                               value="<?= e(get_setting('why_pillar_1_title', 'Dual-Head Industrial Inks')) ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Pillar 1 Explanation</label>
                        <textarea name="why_pillar_1_desc" class="form-input" rows="2"><?= e(get_setting('why_pillar_1_desc', 'Japanese micro-piezo printheads laying down high-opacity white ink foundations for rich color saturation on dark fabrics.')) ?></textarea>
                    </div>
                </div>

                <!-- Pillar 2 -->
                <div style="background: var(--color-ivory); padding: 16px; border-radius: 6px; border: 1px solid var(--color-border);">
                    <div class="form-group">
                        <label class="form-label">Pillar 2 Title</label>
                        <input type="text" name="why_pillar_2_title" class="form-input" 
                               value="<?= e(get_setting('why_pillar_2_title', 'Varnish-Enhanced UV Decals')) ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Pillar 2 Explanation</label>
                        <textarea name="why_pillar_2_desc" class="form-input" rows="2"><?= e(get_setting('why_pillar_2_desc', 'Gloss varnish layer delivers scratchproof, waterproof 3D tactile decals for glass, tumblers, wood, and acrylics.')) ?></textarea>
                    </div>
                </div>

                <!-- Pillar 3 -->
                <div style="background: var(--color-ivory); padding: 16px; border-radius: 6px; border: 1px solid var(--color-border);">
                    <div class="form-group">
                        <label class="form-label">Pillar 3 Title</label>
                        <input type="text" name="why_pillar_3_title" class="form-input" 
                               value="<?= e(get_setting('why_pillar_3_title', 'Direct Factory Manufacturer')) ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Pillar 3 Explanation</label>
                        <textarea name="why_pillar_3_desc" class="form-input" rows="2"><?= e(get_setting('why_pillar_3_desc', 'Delhi workshop equipped with continuous roll-to-roll printers and automatic powder shaker ovens for commercial bulk orders.')) ?></textarea>
                    </div>
                </div>

                <!-- Pillar 4 -->
                <div style="background: var(--color-ivory); padding: 16px; border-radius: 6px; border: 1px solid var(--color-border);">
                    <div class="form-group">
                        <label class="form-label">Pillar 4 Title</label>
                        <input type="text" name="why_pillar_4_title" class="form-input" 
                               value="<?= e(get_setting('why_pillar_4_title', 'Free Prepress Artwork Audit')) ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Pillar 4 Explanation</label>
                        <textarea name="why_pillar_4_desc" class="form-input" rows="2"><?= e(get_setting('why_pillar_4_desc', 'Every client upload is inspected for alpha transparency, CMYK balance, resolution, and line thickness prior to press.')) ?></textarea>
                    </div>
                </div>

            </div>
        </div>

        <!-- 4. Footer Editorial Copy -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">4. Footer Brand Overview</h2>
            </div>
            <div class="admin-card-body" style="padding: 24px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Footer Brand Bio</label>
                    <textarea name="footer_about" class="form-input" rows="3"><?= e(get_setting('footer_about', 'OrderPrintex is Delhi\'s premier manufacturing studio specializing in industrial Direct-to-Film (DTF) and UV DTF transfers. Supplying high-durability custom print solutions across India.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 1rem; font-weight: 700;">
                💾 Publish Website Content Updates
            </button>
        </div>

    </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
