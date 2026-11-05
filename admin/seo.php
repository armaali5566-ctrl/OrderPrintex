<?php
/**
 * OrderPrintex - SEO, Sitemap & LLMs.txt Management Console
 * Centralized automation for search engine indexation and semantic AI index files
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/seo_helper.php';

require_admin();

// SEO & Sync Management interface decommissioned; synchronization runs automatically via centralized seo_helper.
header('Location: ' . url('admin/dashboard.php'));
exit;

// Handle Form Submissions (Processed before header output to eliminate "headers already sent" errors)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security session expired. Please refresh and try again.');
        header('Location: ' . url('admin/seo.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'regenerate_all') {
        $result = seo_sync_all();
        if ($result['success']) {
            set_flash('success', $result['message']);
        } else {
            set_flash('error', 'SEO generation failed: ' . ($result['error'] ?? 'Unknown error'));
        }
        header('Location: ' . url('admin/seo.php'));
        exit;
    }
}

// Fetch SEO File Status & Metadata
$status = seo_get_status();

// Read preview of sitemap.xml
$sitemapPreview = [];
if ($status['sitemap_exists']) {
    $xmlContent = @file_get_contents($status['sitemap_path']);
    if ($xmlContent) {
        $xmlObj = @simplexml_load_string($xmlContent);
        if ($xmlObj && isset($xmlObj->url)) {
            $count = 0;
            foreach ($xmlObj->url as $u) {
                if ($count++ >= 15) break;
                $sitemapPreview[] = [
                    'loc' => (string) $u->loc,
                    'lastmod' => (string) $u->lastmod,
                    'changefreq' => (string) $u->changefreq,
                    'priority' => (string) $u->priority
                ];
            }
        }
    }
}

// Read sample of llms.txt
$llmsPreview = '';
if ($status['llms_exists']) {
    $llmsContent = @file_get_contents($status['llms_path']);
    if ($llmsContent) {
        $lines = explode("\n", $llmsContent);
        $llmsPreview = implode("\n", array_slice($lines, 0, 30));
    }
}

$adminTitle = "SEO & Content Synchronization";
require_once __DIR__ . '/header.php';
?>

<div class="panel-header" style="margin-bottom: 1.5rem; background: var(--bg-surface); padding: 1.25rem 1.75rem; border-radius: var(--radius-md); border: var(--border-card);">
  <div>
    <h1 style="font-size: 1.4rem; font-weight: 700; color: var(--text-primary); margin: 0;">SEO, Sitemap &amp; LLMs Index Automation</h1>
    <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">
      Automated synchronization between published products, categories, blog articles, and public discovery indexes.
    </p>
  </div>
  <div class="panel-actions">
    <form method="POST" action="<?= url('admin/seo.php') ?>" style="display:inline;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="regenerate_all">
      <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem;">
        <svg style="width:16px;height:16px;" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
        </svg>
        <span>Regenerate SEO Files Now</span>
      </button>
    </form>
  </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
  <div class="metric-card">
    <div>
      <div class="metric-label">Indexable URLs</div>
      <div class="metric-value"><?= $status['url_count'] ?></div>
      <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.35rem;">Published in sitemap.xml</div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">sitemap.xml Status</div>
      <div class="metric-value" style="font-size:1.35rem; color: <?= $status['sitemap_exists'] ? 'var(--bright-gold)' : 'var(--crimson)' ?>;">
        <?= $status['sitemap_exists'] ? 'Active &amp; Valid' : 'Missing' ?>
      </div>
      <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.35rem;">
        Size: <?= format_file_size($status['sitemap_size']) ?>
      </div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">llms.txt AI Context</div>
      <div class="metric-value" style="font-size:1.35rem; color: <?= $status['llms_exists'] ? 'var(--bright-gold)' : 'var(--crimson)' ?>;">
        <?= $status['llms_exists'] ? 'Synchronized' : 'Missing' ?>
      </div>
      <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.35rem;">
        Size: <?= format_file_size($status['llms_size']) ?>
      </div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
      </svg>
    </div>
  </div>

  <div class="metric-card">
    <div>
      <div class="metric-label">Last Synchronization</div>
      <div class="metric-value" style="font-size:1.15rem;"><?= !empty($status['last_generated']) && $status['last_generated'] !== 'Never' ? date('d M, h:i A', strtotime($status['last_generated'])) : 'Never' ?></div>
      <div style="font-size:0.75rem; color: <?= $status['last_status'] === 'success' ? '#10B981' : '#F59E0B' ?>; margin-top:0.35rem;">
        Status: <?= ucfirst($status['last_status']) ?>
      </div>
    </div>
    <div class="metric-icon-box">
      <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
    </div>
  </div>
</div>

<!-- Tabs / Sections -->
<div class="admin-split-grid" style="margin-bottom: 2rem;">
  
  <!-- Sitemap Preview -->
  <div class="panel-card" style="margin-bottom:0;">
    <div class="panel-header">
      <div class="panel-title" style="display:flex; align-items:center; gap:0.5rem;">
        <span>sitemap.xml Entries</span>
        <span class="status-badge badge-primary"><?= $status['url_count'] ?> URLs</span>
      </div>
      <div class="panel-actions">
        <a href="<?= url('sitemap.xml') ?>" target="_blank" class="btn btn-sm btn-outline">
          View Raw XML &rarr;
        </a>
      </div>
    </div>
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Canonical URL</th>
            <th>Last Modified</th>
            <th>Freq</th>
            <th>Priority</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($sitemapPreview)): ?>
            <tr>
              <td colspan="4" style="text-align:center; padding:2rem; color:var(--text-secondary);">
                No sitemap entries found. Click <strong>Regenerate SEO Files Now</strong> above.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($sitemapPreview as $entry): ?>
              <tr>
                <td style="font-family:var(--font-mono); font-size:0.78rem;">
                  <a href="<?= e($entry['loc']) ?>" target="_blank" style="color:var(--bright-gold); text-decoration:none;">
                    <?= e(str_replace(url(''), '/', $entry['loc'])) ?>
                  </a>
                </td>
                <td style="font-size:0.78rem; color:var(--text-secondary);"><?= e($entry['lastmod']) ?></td>
                <td style="font-size:0.75rem; text-transform:uppercase;"><?= e($entry['changefreq']) ?></td>
                <td><span class="status-badge badge-secondary"><?= e($entry['priority']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if ($status['url_count'] > 15): ?>
      <div style="padding:0.75rem 1.25rem; background:var(--bg-secondary); font-size:0.78rem; color:var(--text-secondary); text-align:center; border-top:1px solid var(--border-color);">
        Showing first 15 of <?= $status['url_count'] ?> total URLs. All URLs are live in <a href="<?= url('sitemap.xml') ?>" target="_blank" style="color:var(--bright-gold);">sitemap.xml</a>.
      </div>
    <?php endif; ?>
  </div>

  <!-- LLMs.txt Preview -->
  <div class="panel-card" style="margin-bottom:0;">
    <div class="panel-header">
      <div class="panel-title" style="display:flex; align-items:center; gap:0.5rem;">
        <span>llms.txt AI Context Index</span>
        <span class="status-badge badge-success">Markdown</span>
      </div>
      <div class="panel-actions">
        <a href="<?= url('llms.txt') ?>" target="_blank" class="btn btn-sm btn-outline">
          View Raw File &rarr;
        </a>
      </div>
    </div>
    <div style="padding:1.25rem; background:var(--bg-surface); max-height:480px; overflow-y:auto; font-family:var(--font-mono); font-size:0.8rem; line-height:1.5; color:var(--text-primary); white-space:pre-wrap; border-radius:0 0 var(--radius-md) var(--radius-md);">
<?= e($llmsPreview) ?>
    </div>
  </div>
</div>

<!-- Structured Data / JSON-LD Information -->
<div class="panel-card">
  <div class="panel-header">
    <div class="panel-title">
      Schema.org Structured Data (JSON-LD) Overview
    </div>
    <span class="status-badge badge-info">Automated Injectors Active</span>
  </div>
  <div style="padding: 1.5rem;">
    <p style="font-size:0.88rem; color:var(--text-secondary); margin-top:0;">
      OrderPrintex dynamically injects validated Schema.org structured data across every public page using standard JSON-LD standards.
    </p>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr)); gap: 1.25rem;">
      <div style="padding:1.25rem; background:var(--bg-secondary); border-radius:var(--radius-sm); border:1px solid var(--border-color);">
        <div style="font-weight:700; color:var(--bright-gold); margin-bottom:0.35rem;">Organization Schema</div>
        <p style="font-size:0.825rem; color:var(--text-secondary); margin:0;">
          Outputs official business entity information, verified facility address in New Delhi, contact channels, and branding logo across site footers.
        </p>
      </div>

      <div style="padding:1.25rem; background:var(--bg-secondary); border-radius:var(--radius-sm); border:1px solid var(--border-color);">
        <div style="font-weight:700; color:var(--bright-gold); margin-bottom:0.35rem;">Product &amp; Offer Schema</div>
        <p style="font-size:0.825rem; color:var(--text-secondary); margin:0;">
          Embedded on every product detail page (e.g. <code>/product/custom-dtf-gang-sheet/</code>), exposing live regular/sale price, INR currency, SKU identifiers, and availability.
        </p>
      </div>

      <div style="padding:1.25rem; background:var(--bg-secondary); border-radius:var(--radius-sm); border:1px solid var(--border-color);">
        <div style="font-weight:700; color:var(--bright-gold); margin-bottom:0.35rem;">BlogPosting Schema</div>
        <p style="font-size:0.825rem; color:var(--text-secondary); margin:0;">
          Output on all published educational and technical articles under <code>/blog/</code>, declaring authorship, publication date, modification timestamp, and headline.
        </p>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

