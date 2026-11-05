<?php
/**
 * OrderPrintex - Admin Master Dashboard
 * Single-column stacked tables, premium 8-KPI responsive grid, Black & Gold luxury console
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_panel();
$adminUser = auth_user();

// 1. PRIMARY METRICS (Dynamic Real-Time Aggregations)
$totalEnquiries       = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries")['cnt'] ?? 0);
$newEnquiries         = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE status = 'pending'")['cnt'] ?? 0);
$underReviewEnquiries = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM enquiries WHERE status = 'under_review'")['cnt'] ?? 0);

$pendingQuotes        = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status IN ('draft', 'sent')")['cnt'] ?? 0);
$approvedQuotes       = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status = 'accepted'")['cnt'] ?? 0);
$rejectedQuotes       = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM quotations WHERE status IN ('rejected', 'declined', 'expired')")['cnt'] ?? 0);

$totalActiveProducts  = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM products WHERE is_active = 1")['cnt'] ?? 0);
$totalPortfolioItems  = (int) (db_fetch_one("SELECT COUNT(*) as cnt FROM gallery")['cnt'] ?? 0);

// 2. RECENT ENTITIES
// Recent Customer Enquiries
$recentEnquiries = db_fetch_all("
    SELECT e.*, q.id as quotation_id, q.quote_number, q.status as quote_status
    FROM enquiries e 
    LEFT JOIN quotations q ON q.enquiry_id = e.id
    ORDER BY e.created_at DESC LIMIT 8
");

// Recent Quotations
$recentQuotations = db_fetch_all("
    SELECT q.*, e.ref_number as enquiry_ref 
    FROM quotations q 
    LEFT JOIN enquiries e ON q.enquiry_id = e.id 
    ORDER BY q.created_at DESC LIMIT 8
");

// Recently Added Active Products
$recentProducts = db_fetch_all("
    SELECT p.id, p.title, p.slug, p.sku, p.regular_price, p.sale_price, p.starting_price, p.is_active, p.image_url, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.is_active = 1
    ORDER BY p.id DESC LIMIT 4
");

$adminTitle = "Dashboard Overview";
require_once __DIR__ . '/header.php';
?>

<!-- Quick Actions Banner -->
<div class="panel-card" style="margin-bottom: 1.75rem; background: var(--admin-bg-card); border-left: 4px solid var(--admin-gold);">
  <div style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h2 style="font-size: 1.15rem; font-weight: 700; margin: 0 0 0.25rem; color: var(--admin-text-main);">
        Bespoke Printing Commercial Desk
      </h2>
      <p style="margin: 0; font-size: 0.825rem; color: var(--admin-text-muted);">
        Unified workflow oversight: review customer print specifications, issue formal quotations, and curate portfolio projects.
      </p>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
      <a href="<?= url('admin/enquiries.php') ?>" class="btn btn-sm btn-primary">Review Enquiries</a>
      <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-sm btn-outline">+ Create Quotation</a>
      <a href="<?= url('admin/products.php?action=new') ?>" class="btn btn-sm btn-outline">+ Add Product</a>
      <a href="<?= url('admin/gallery.php') ?>" class="btn btn-sm btn-outline">Manage Portfolio</a>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- ENHANCED 8 KPI CARDS GRID (RESPONSIVE: 4 DESKTOP / 2 TABLET / 1-2 MOBILE) -->
<!-- ========================================================================= -->
<div class="metrics-grid">
  
  <!-- 1. Total Enquiries -->
  <div class="metric-card">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label">Total Enquiries</div>
        <div class="metric-value" style="color:var(--admin-text-main);"><?= number_format($totalEnquiries) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem; line-height:1.35;">
        All-time customer requests
      </div>
    </div>
    <div class="metric-icon-box" title="Total Enquiries Received">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
        <line x1="16" y1="13" x2="8" y2="13"></line>
        <line x1="16" y1="17" x2="8" y2="17"></line>
      </svg>
    </div>
  </div>

  <!-- 2. New Enquiries -->
  <div class="metric-card" style="border-color: <?= $newEnquiries > 0 ? 'rgba(245,158,11,0.35)' : 'var(--admin-border-card)' ?>;">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label" style="color: <?= $newEnquiries > 0 ? '#F59E0B' : 'var(--admin-text-muted)' ?>;">New Enquiries</div>
        <div class="metric-value" style="color: #F59E0B;"><?= number_format($newEnquiries) ?></div>
      </div>
      <div style="font-size:0.75rem; color: <?= $newEnquiries > 0 ? '#FCD34D' : 'var(--admin-text-muted)' ?>; font-weight:600; margin-top:0.35rem; line-height:1.35;">
        <?= $newEnquiries > 0 ? '● Awaiting initial review' : 'Queue up to date' ?>
      </div>
    </div>
    <div class="metric-icon-box" style="background:rgba(245,158,11,0.1); border-color:rgba(245,158,11,0.3); color:#F59E0B;" title="New Inquiries">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
    </div>
  </div>

  <!-- 3. Under Review -->
  <div class="metric-card" style="border-color: <?= $underReviewEnquiries > 0 ? 'rgba(96,165,250,0.35)' : 'var(--admin-border-card)' ?>;">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label">Under Review</div>
        <div class="metric-value" style="color: #60A5FA;"><?= number_format($underReviewEnquiries) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem; line-height:1.35;">
        Processing specifications
      </div>
    </div>
    <div class="metric-icon-box" style="background:rgba(96,165,250,0.1); border-color:rgba(96,165,250,0.3); color:#60A5FA;" title="Under Prepress Review">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <circle cx="12" cy="14" r="3"></circle>
        <line x1="14.5" y1="16.5" x2="17" y2="19"></line>
      </svg>
    </div>
  </div>

  <!-- 4. Pending Quotations -->
  <div class="metric-card" style="border-color: <?= $pendingQuotes > 0 ? 'rgba(212,175,55,0.4)' : 'var(--admin-border-card)' ?>;">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label" style="color:var(--admin-gold);">Pending Quotations</div>
        <div class="metric-value" style="color:#FFD76A;"><?= number_format($pendingQuotes) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-gold); font-weight:600; margin-top:0.35rem; line-height:1.35;">
        Awaiting client response
      </div>
    </div>
    <div class="metric-icon-box" style="background:rgba(212,175,55,0.12); border-color:rgba(212,175,55,0.35); color:#FFD76A;" title="Pending Quotations">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
        <line x1="2" y1="10" x2="22" y2="10"></line>
        <line x1="7" y1="15" x2="7.01" y2="15"></line>
        <line x1="12" y1="15" x2="17" y2="15"></line>
      </svg>
    </div>
  </div>

  <!-- 5. Approved Quotations -->
  <div class="metric-card" style="border-color: <?= $approvedQuotes > 0 ? 'rgba(52,211,153,0.35)' : 'var(--admin-border-card)' ?>;">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label" style="color:#34D399;">Approved Quotations</div>
        <div class="metric-value" style="color:#34D399;"><?= number_format($approvedQuotes) ?></div>
      </div>
      <div style="font-size:0.75rem; color:#34D399; font-weight:600; margin-top:0.35rem; line-height:1.35;">
        Confirmed by clients
      </div>
    </div>
    <div class="metric-icon-box" style="background:rgba(52,211,153,0.1); border-color:rgba(52,211,153,0.3); color:#34D399;" title="Approved Quotations">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
    </div>
  </div>

  <!-- 6. Rejected / Expired -->
  <div class="metric-card" style="border-color: <?= $rejectedQuotes > 0 ? 'rgba(248,113,113,0.3)' : 'var(--admin-border-card)' ?>;">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label">Rejected / Expired</div>
        <div class="metric-value" style="color:#F87171;"><?= number_format($rejectedQuotes) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem; line-height:1.35;">
        Declined or past validity
      </div>
    </div>
    <div class="metric-icon-box" style="background:rgba(248,113,113,0.1); border-color:rgba(248,113,113,0.3); color:#F87171;" title="Declined / Expired">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
      </svg>
    </div>
  </div>

  <!-- 7. Total Products -->
  <div class="metric-card">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label">Total Products</div>
        <div class="metric-value" style="color:var(--admin-text-main);"><?= number_format($totalActiveProducts) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem; line-height:1.35;">
        Active catalogue items
      </div>
    </div>
    <div class="metric-icon-box" title="Active Products">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
        <line x1="12" y1="22.08" x2="12" y2="12"></line>
      </svg>
    </div>
  </div>

  <!-- 8. Portfolio Projects -->
  <div class="metric-card">
    <div style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
      <div>
        <div class="metric-label">Portfolio Projects</div>
        <div class="metric-value" style="color:var(--admin-gold);"><?= number_format($totalPortfolioItems) ?></div>
      </div>
      <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:0.35rem; line-height:1.35;">
        Published studio showcase
      </div>
    </div>
    <div class="metric-icon-box" title="Portfolio Projects">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
        <circle cx="8.5" cy="8.5" r="1.5"></circle>
        <polyline points="21 15 16 10 5 21"></polyline>
      </svg>
    </div>
  </div>

</div>

<!-- ========================================================================= -->
<!-- SECTION 1: RECENT CUSTOMER ENQUIRIES (FULL AVAILABLE CONTENT WIDTH) -->
<!-- ========================================================================= -->
<div class="panel-card" style="width:100%; margin-bottom:2rem;">
  <div class="panel-header" style="border-bottom:1px solid var(--admin-border-card); padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
    <div>
      <h2 class="panel-title" style="margin:0 0 0.2rem; font-size:1.15rem;">Recent Customer Enquiries</h2>
      <p style="margin:0; font-size:0.8rem; color:var(--admin-text-muted);">
        Incoming print specifications submitted through the client portal and website
      </p>
    </div>
    <div class="panel-actions">
      <a href="<?= url('admin/enquiries.php') ?>" class="btn btn-xs btn-outline">
        View All Enquiries &rarr;
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.88rem;">
      <thead>
        <tr>
          <th style="min-width:140px;">Enquiry Ref #</th>
          <th style="min-width:150px;">Submission Date</th>
          <th style="min-width:190px;">Customer Information</th>
          <th style="min-width:210px;">Service / Specifications</th>
          <th style="text-align:center; min-width:80px;">Quantity</th>
          <th style="min-width:110px;">Status</th>
          <th style="text-align:right; min-width:140px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentEnquiries)): ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:3rem 1.5rem; color:var(--admin-text-muted);">
              <div style="font-size:1.05rem; color:var(--admin-text-main); font-weight:600; margin-bottom:0.25rem;">No customer enquiries received yet</div>
              <p style="margin:0; font-size:0.85rem;">Enquiries submitted from client forms will appear here automatically.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentEnquiries as $enq): ?>
            <tr>
              <td class="ref-cell" style="font-family:var(--font-mono); font-weight:700; color:var(--admin-gold);">
                <?= e($enq['ref_number']) ?>
              </td>
              <td style="font-size:0.82rem; color:var(--admin-text-muted); white-space:nowrap;">
                <?= !empty($enq['created_at']) ? format_date($enq['created_at'], 'd M Y, h:i A') : '—' ?>
              </td>
              <td>
                <strong style="color:var(--admin-text-main); font-size:0.9rem;"><?= e($enq['customer_name']) ?></strong><br>
                <small style="color:var(--admin-text-muted);"><?= e($enq['customer_email']) ?></small>
                <?php if (!empty($enq['customer_phone'])): ?>
                  <br><small style="color:#8A8275;">Tel: <?= e($enq['customer_phone']) ?></small>
                <?php endif; ?>
              </td>
              <td style="font-size:0.85rem;">
                <strong style="color:var(--admin-text-main);"><?= e($enq['service_name'] ?? 'Bespoke Print') ?></strong>
                <?php if (!empty($enq['width_inches']) && !empty($enq['height_inches'])): ?>
                  <span style="color:var(--admin-gold); font-size:0.8rem;"> &bull; <?= e($enq['width_inches']) ?>&Prime; &times; <?= e($enq['height_inches']) ?>&Prime;</span>
                <?php endif; ?>
                <?php if (!empty($enq['material_options'])): ?>
                  <div style="color:var(--admin-text-muted); font-size:0.75rem; margin-top:2px; line-height:1.35;"><?= e($enq['material_options']) ?></div>
                <?php endif; ?>
              </td>
              <td style="text-align:center;">
                <span class="status-badge" style="background:rgba(212,175,55,0.1); color:var(--admin-gold); border:1px solid rgba(212,175,55,0.25); font-weight:700; font-family:var(--font-mono);">
                  <?= intval($enq['quantity']) ?>
                </span>
              </td>
              <td><?= status_badge($enq['status']) ?></td>
              <td style="text-align:right; white-space:nowrap;">
                <div style="display:inline-flex; gap:6px; align-items:center;">
                  <a href="<?= url('admin/enquiries.php?enquiry_id=' . $enq['id']) ?>" class="btn btn-xs btn-outline" style="padding:0.35rem 0.65rem;">
                    Inspect
                  </a>
                  <?php if (empty($enq['quotation_id'])): ?>
                    <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $enq['id']) ?>" class="btn btn-xs btn-primary" style="padding:0.35rem 0.65rem;">
                      + Quote
                    </a>
                  <?php else: ?>
                    <a href="<?= url('admin/quotations.php?quote_id=' . $enq['quotation_id']) ?>" class="btn btn-xs btn-outline" style="color:var(--admin-gold); border-color:rgba(212,175,55,0.4); padding:0.35rem 0.65rem;">
                      Quote #<?= e($enq['quote_number']) ?>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION 2: ITEMIZED QUOTATION DESK (FULL WIDTH - STACKED BELOW ENQUIRIES) -->
<!-- ========================================================================= -->
<div class="panel-card" style="width:100%; margin-bottom:2rem;">
  <div class="panel-header" style="border-bottom:1px solid var(--admin-border-card); padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
    <div>
      <h2 class="panel-title" style="margin:0 0 0.2rem; font-size:1.15rem;">Itemized Quotation Desk</h2>
      <p style="margin:0; font-size:0.8rem; color:var(--admin-text-muted);">
        Commercial proposals and prepress pricing issued to clients
      </p>
    </div>
    <div class="panel-actions">
      <a href="<?= url('admin/quotations.php') ?>" class="btn btn-xs btn-outline">
        View Quotations &rarr;
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.88rem;">
      <thead>
        <tr>
          <th style="min-width:140px;">Quote #</th>
          <th style="min-width:130px;">Date Issued</th>
          <th style="min-width:190px;">Customer</th>
          <th style="min-width:140px;">Linked Enquiry</th>
          <th style="min-width:130px;">Grand Total</th>
          <th style="min-width:110px;">Status</th>
          <th style="text-align:right; min-width:110px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentQuotations)): ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:3rem 1.5rem; color:var(--admin-text-muted);">
              <div style="font-size:1.05rem; color:var(--admin-text-main); font-weight:600; margin-bottom:0.25rem;">No quotations created yet</div>
              <p style="margin:0 0 1rem; font-size:0.85rem;">Click <strong>+ Create Quotation</strong> above to generate your first client proposal.</p>
              <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-xs btn-primary">+ Create Quotation</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentQuotations as $qt): ?>
            <tr>
              <td class="ref-cell" style="font-family:var(--font-mono); font-weight:700; color:#FFD76A;">
                <?= e($qt['quote_number']) ?>
              </td>
              <td style="font-size:0.82rem; color:var(--admin-text-muted); white-space:nowrap;">
                <?= !empty($qt['created_at']) ? format_date($qt['created_at'], 'd M Y') : '—' ?>
              </td>
              <td>
                <strong style="color:var(--admin-text-main); font-size:0.9rem;"><?= e($qt['customer_name']) ?></strong><br>
                <small style="color:var(--admin-text-muted);"><?= e($qt['customer_email']) ?></small>
              </td>
              <td style="font-size:0.82rem; color:var(--admin-text-muted);">
                <?= !empty($qt['enquiry_ref']) ? e($qt['enquiry_ref']) : '<span style="font-style:italic;">Direct Proposal</span>' ?>
              </td>
              <td style="font-weight:800; color:var(--admin-gold); font-family:var(--font-mono); font-size:0.95rem;">
                <?= format_price($qt['grand_total'] ?? 0) ?>
              </td>
              <td><?= status_badge($qt['status']) ?></td>
              <td style="text-align:right; white-space:nowrap;">
                <a href="<?= url('admin/quotations.php?quote_id=' . $qt['id']) ?>" class="btn btn-xs btn-outline" style="padding:0.35rem 0.75rem;">
                  View / Edit &rarr;
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION 3: ACTIVE PRODUCT CATALOGUE HIGHLIGHTS (FULL WIDTH) -->
<!-- ========================================================================= -->
<div class="panel-card" style="width:100%;">
  <div class="panel-header" style="border-bottom:1px solid var(--admin-border-card); padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
    <div>
      <h2 class="panel-title" style="margin:0 0 0.2rem; font-size:1.15rem;">Active Catalogue Highlights</h2>
      <p style="margin:0; font-size:0.8rem; color:var(--admin-text-muted);">Publicly published printing products and base pricing</p>
    </div>
    <div class="panel-actions">
      <a href="<?= url('admin/products.php') ?>" class="btn btn-xs btn-outline">Manage Product Catalogue &rarr;</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="data-table" style="width:100%; font-size:0.88rem;">
      <thead>
        <tr>
          <th>Product Name &amp; Base SKU</th>
          <th>Category</th>
          <th>Base Pricing</th>
          <th>Status</th>
          <th style="text-align:right;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentProducts as $prod): ?>
          <tr>
            <td>
              <div style="display:flex; align-items:center; gap:0.75rem;">
                <?php if (!empty($prod['image_url'])): ?>
                  <img src="<?= url($prod['image_url']) ?>" alt="" style="width:38px; height:38px; object-fit:cover; border-radius:6px; border:1px solid rgba(255,255,255,0.1);">
                <?php endif; ?>
                <div>
                  <strong style="color:var(--admin-text-main); font-size:0.9rem;"><?= e($prod['title']) ?></strong><br>
                  <small class="ref-cell" style="font-family:var(--font-mono); color:var(--admin-gold);"><?= e($prod['sku'] ?: ('OP-P' . $prod['id'])) ?></small>
                </div>
              </div>
            </td>
            <td style="font-size:0.82rem; color:var(--admin-text-muted);"><?= e($prod['category_name'] ?? 'General') ?></td>
            <td style="font-weight:700; color:var(--admin-text-main); font-family:var(--font-mono);">
              <?= format_price($prod['sale_price'] ?? $prod['regular_price'] ?? $prod['starting_price'] ?? 0) ?>
            </td>
            <td>
              <span class="status-badge <?= $prod['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                <?= $prod['is_active'] ? 'Active' : 'Draft' ?>
              </span>
            </td>
            <td style="text-align:right;">
              <a href="<?= url('admin/products.php?action=edit&id=' . $prod['id']) ?>" class="btn btn-xs btn-outline" style="padding:0.35rem 0.65rem;">
                Edit
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
