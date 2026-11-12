<?php
/**
 * OrderPrintex - Admin Enquiry & Customer Artwork Management
 * Features:
 *  - Responsive 2-Panel Desktop Split Layout (Zero horizontal table overflow)
 *  - Customer Uploaded Artwork Thumbnail Gallery with object-fit: contain & dark backdrop
 *  - Full-Screen Image Inspection Viewer with Pan & Zoom controls
 *  - Secure authorized artwork streaming
 *  - Complete fix for line 356 PHP warning & raw HTML spill
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_permission('enquiries.view');
$adminUser = auth_user();

// Handle Actions: Status Update & Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }

    $action = $_POST['action'];

    // 1. Update Pipeline Status
    if ($action === 'update_status') {
        $enquiryId = intval($_POST['enquiry_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? 'received');
        
        $validStatuses = ['received', 'under_review', 'quoted', 'converted', 'rejected', 'closed'];
        if (in_array($newStatus, $validStatuses, true)) {
            db_update('enquiries', ['status' => $newStatus], 'id = :id', ['id' => $enquiryId]);
            log_activity($adminUser['id'], 'update_enquiry', "Updated enquiry ID #{$enquiryId} status to {$newStatus}");
            set_flash('success', "Enquiry status updated to " . ucfirst(str_replace('_', ' ', $newStatus)));
        }
        header('Location: ' . url('admin/enquiries.php' . ($enquiryId ? '?enquiry_id=' . $enquiryId : '')));
        exit;
    }

    // 2. Delete Enquiry & Associated Files
    if ($action === 'delete_enquiry') {
        $enquiryId = intval($_POST['enquiry_id'] ?? 0);
        $enq = db_fetch_one("SELECT * FROM enquiries WHERE id = :id", ['id' => $enquiryId]);
        if ($enq) {
            // Safely unlink all uploaded artwork files from disk
            $files = db_fetch_all("SELECT * FROM enquiry_files WHERE enquiry_id = :id", ['id' => $enquiryId]);
            foreach ($files as $f) {
                $candidates = [
                    ROOT_PATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $f['file_path']),
                    ARTWORK_PATH . $f['stored_name'],
                    UPLOADS_PATH . 'artwork' . DIRECTORY_SEPARATOR . $f['stored_name'],
                    UPLOADS_PATH . $f['stored_name']
                ];
                foreach ($candidates as $cand) {
                    if (file_exists($cand) && is_file($cand)) {
                        @unlink($cand);
                    }
                }
            }
            db_delete('enquiry_files', 'enquiry_id = :id', ['id' => $enquiryId]);
            db_delete('enquiries', 'id = :id', ['id' => $enquiryId]);
            log_activity($adminUser['id'], 'delete_enquiry', "Deleted customer enquiry {$enq['ref_number']}");
            set_flash('success', "Customer enquiry {$enq['ref_number']} was deleted successfully.");
        }
        header('Location: ' . url('admin/enquiries.php'));
        exit;
    }
}

// Filters & Search
$statusFilter = trim($_GET['status'] ?? '');
$searchTerm = trim($_GET['search'] ?? '');
$selectedId = intval($_GET['enquiry_id'] ?? 0);

$sql = "SELECT e.*, u.name as user_account_name,
               (SELECT COUNT(*) FROM enquiry_files ef WHERE ef.enquiry_id = e.id) as files_count,
               q.quote_number, q.id as quote_id
        FROM enquiries e
        LEFT JOIN users u ON e.user_id = u.id
        LEFT JOIN quotations q ON q.enquiry_id = e.id
        WHERE 1=1";
$params = [];

if ($statusFilter) {
    $sql .= " AND e.status = :status";
    $params['status'] = $statusFilter;
}

if ($searchTerm) {
    $sql .= " AND (e.ref_number LIKE :s OR e.customer_name LIKE :s OR e.customer_email LIKE :s OR e.customer_phone LIKE :s OR e.company_name LIKE :s OR e.service_name LIKE :s)";
    $params['s'] = '%' . $searchTerm . '%';
}

$sql .= " ORDER BY e.created_at DESC";
$enquiries = db_fetch_all($sql, $params);

// Selected Enquiry Details & Artwork Resolution
$activeEnquiry = null;
$enquiryFiles = [];
$artworkGalleryData = [];

if ($selectedId) {
    $activeEnquiry = db_fetch_one("
        SELECT e.*, u.name as registered_user, u.email as user_email,
               q.id as existing_quote_id, q.quote_number, q.status as quote_status, q.grand_total as quote_amount
        FROM enquiries e
        LEFT JOIN users u ON e.user_id = u.id
        LEFT JOIN quotations q ON q.enquiry_id = e.id
        WHERE e.id = :id
    ", ['id' => $selectedId]);

    if ($activeEnquiry) {
        $rawFiles = db_fetch_all("SELECT * FROM enquiry_files WHERE enquiry_id = :eid ORDER BY id ASC", ['eid' => $selectedId]);
        
        foreach ($rawFiles as $file) {
            // Test candidate locations to check physical existence
            $candidates = [
                ROOT_PATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file['file_path']),
                ARTWORK_PATH . $file['stored_name'],
                UPLOADS_PATH . 'artwork' . DIRECTORY_SEPARATOR . $file['stored_name'],
                UPLOADS_PATH . $file['stored_name']
            ];
            $realDiskPath = null;
            foreach ($candidates as $cand) {
                if (file_exists($cand) && is_file($cand)) {
                    $realDiskPath = $cand;
                    break;
                }
            }

            $ext = strtolower($file['file_extension'] ?: pathinfo($file['original_name'], PATHINFO_EXTENSION));
            $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'], true);
            $isDoc = in_array($ext, ['pdf', 'ai', 'psd', 'eps'], true);

            // Authorized streaming URL (protects private files & bypasses server root path exposure)
            $streamUrl = url('admin/view_artwork.php?file_id=' . $file['id']);
            $downloadUrl = url('admin/view_artwork.php?file_id=' . $file['id'] . '&download=1');

            $resolvedFile = [
                'id'            => $file['id'],
                'original_name' => $file['original_name'] ?: 'artwork.' . $ext,
                'stored_name'   => $file['stored_name'],
                'file_size'     => (int)($file['file_size'] ?? 0),
                'file_extension'=> $ext,
                'is_image'      => $isImage,
                'is_doc'        => $isDoc,
                'exists'        => ($realDiskPath !== null),
                'stream_url'    => $streamUrl,
                'download_url'  => $downloadUrl
            ];

            $enquiryFiles[] = $resolvedFile;

            if ($isImage && $realDiskPath !== null) {
                $artworkGalleryData[] = [
                    'src'         => $streamUrl,
                    'title'       => $file['original_name'] . ' (' . format_file_size((int)$file['file_size']) . ')',
                    'filename'    => $file['original_name'],
                    'downloadUrl' => $downloadUrl
                ];
            }
        }
    }
}

$adminTitle = "Customer Enquiries & Artwork Review";
require_once __DIR__ . '/header.php';
?>

<!-- Topbar Header -->
<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Customer Enquiries &amp; Artwork</h1>
        <p class="admin-page-subtitle">Review customer print enquiries, inspect uploaded prepress designs, and prepare itemized quotations.</p>
    </div>
    <div class="admin-topbar-actions">
        <a href="<?= url('admin/quotations.php?action=create') ?>" class="btn btn-primary btn-sm">
            <span>+ Create Custom Quotation</span>
        </a>
    </div>
</div>

<!-- Quick Metric Tabs / Search Toolbar -->
<?php
$countAll       = db_fetch_one("SELECT COUNT(*) as c FROM enquiries")['c'] ?? 0;
$countReceived  = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'received'")['c'] ?? 0;
$countReview    = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'under_review'")['c'] ?? 0;
$countQuoted    = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'quoted'")['c'] ?? 0;
$countConverted = db_fetch_one("SELECT COUNT(*) as c FROM enquiries WHERE status = 'converted'")['c'] ?? 0;
?>

<div class="admin-filter-bar">
    <div class="admin-filter-tabs">
        <a href="<?= url('admin/enquiries.php') ?>" class="admin-tab-btn <?= empty($statusFilter) ? 'active' : '' ?>">
            All Enquiries (<?= $countAll ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=received') ?>" class="admin-tab-btn <?= $statusFilter === 'received' ? 'active' : '' ?>">
            Received (<?= $countReceived ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=under_review') ?>" class="admin-tab-btn <?= $statusFilter === 'under_review' ? 'active' : '' ?>">
            Under Review (<?= $countReview ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=quoted') ?>" class="admin-tab-btn <?= $statusFilter === 'quoted' ? 'active' : '' ?>">
            Quoted (<?= $countQuoted ?>)
        </a>
        <a href="<?= url('admin/enquiries.php?status=converted') ?>" class="admin-tab-btn <?= $statusFilter === 'converted' ? 'active' : '' ?>">
            Converted (<?= $countConverted ?>)
        </a>
    </div>

    <form method="GET" action="<?= url('admin/enquiries.php') ?>" class="admin-search-form">
        <?php if ($statusFilter): ?>
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
        <?php endif; ?>
        <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search ref, customer, email, service..." class="form-input form-input-sm">
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        <?php if ($searchTerm || $statusFilter): ?>
            <a href="<?= url('admin/enquiries.php') ?>" class="btn btn-outline btn-sm">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Main Enquiries Split View -->
<div class="<?= $activeEnquiry ? 'enquiries-split-layout' : '' ?>">
    
    <!-- LEFT PANEL: Enquiry Submissions List (Zero Horizontal Overflow) -->
    <div class="admin-card">
        <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="admin-card-title" style="margin:0;">
                Enquiry Submissions (<?= count($enquiries) ?>)
            </h2>
            <?php if ($activeEnquiry): ?>
                <span style="font-size: 0.75rem; color: var(--admin-gold, #D4AF37); font-weight: 600;">
                    Inspecting #<?= e($activeEnquiry['ref_number']) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="enquiries-table-wrap">
            <table class="enquiries-table">
                <thead>
                    <tr>
                        <th class="col-ref">Reference</th>
                        <th class="col-cust">Customer</th>
                        <th class="col-svc">Service / Specs</th>
                        <th class="col-qty">Qty</th>
                        <th class="col-status">Status</th>
                        <th class="col-date">Date</th>
                        <th class="col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enquiries)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--admin-text-muted, #94A3B8);">
                                No customer enquiries found matching the criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($enquiries as $enq): ?>
                            <?php $isSelected = ($activeEnquiry && $activeEnquiry['id'] === $enq['id']); ?>
                            <tr class="<?= $isSelected ? 'table-row-selected' : '' ?>" 
                                onclick="window.location.href='<?= url('admin/enquiries.php?enquiry_id=' . $enq['id'] . ($statusFilter ? '&status=' . urlencode($statusFilter) : '')) ?>'">
                                
                                <td class="col-ref" data-label="Reference">
                                    <div class="enquiry-ref-code"><?= e($enq['ref_number']) ?></div>
                                    <?php if (!empty($enq['files_count']) && $enq['files_count'] > 0): ?>
                                        <div style="font-size: 0.7rem; color: #10B981; margin-top: 3px; font-weight: 600;">
                                            📎 <?= $enq['files_count'] ?> Artwork<?= $enq['files_count'] > 1 ? 's' : '' ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($enq['quote_number'])): ?>
                                        <div style="font-size: 0.72rem; color: #60A5FA; margin-top: 2px;">
                                            Quote: <?= e($enq['quote_number']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="col-cust" data-label="Customer">
                                    <div class="enquiry-cust-name"><?= e($enq['customer_name']) ?></div>
                                    <div class="enquiry-cust-sub"><?= e($enq['customer_email']) ?></div>
                                    <div class="enquiry-cust-sub"><?= e($enq['customer_phone']) ?></div>
                                    <?php if (!empty($enq['company_name'])): ?>
                                        <div style="font-size: 0.72rem; color: var(--admin-gold, #D4AF37); font-weight: 600; margin-top: 2px;">
                                            🏢 <?= e($enq['company_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="col-svc" data-label="Service / Specs">
                                    <div class="enquiry-svc-title"><?= e($enq['service_name'] ?: 'Custom Print') ?></div>
                                    <?php if ($enq['width_inches'] && $enq['height_inches']): ?>
                                        <div class="enquiry-svc-specs"><?= $enq['width_inches'] ?>" &times; <?= $enq['height_inches'] ?>"</div>
                                    <?php endif; ?>
                                    <?php if ($enq['material_options']): ?>
                                        <div class="enquiry-svc-specs"><?= e($enq['material_options']) ?></div>
                                    <?php endif; ?>
                                </td>

                                <td class="col-qty" data-label="Quantity">
                                    <strong style="color: #F3F4F6;"><?= intval($enq['quantity']) ?></strong>
                                </td>

                                <td class="col-status" data-label="Status">
                                    <?= status_badge($enq['status']) ?>
                                </td>

                                <td class="col-date" data-label="Date">
                                    <?= date('d M Y', strtotime($enq['created_at'])) ?>
                                </td>

                                <td class="col-action" data-label="Action" onclick="event.stopPropagation();">
                                    <div style="display:flex; gap:4px; justify-content:flex-end;">
                                        <a href="<?= url('admin/enquiries.php?enquiry_id=' . $enq['id'] . ($statusFilter ? '&status=' . urlencode($statusFilter) : '')) ?>" 
                                           class="btn btn-outline btn-xs" 
                                           style="padding: 0.2rem 0.35rem; font-size: 0.72rem; <?= $isSelected ? 'border-color: var(--admin-gold, #D4AF37); color: var(--admin-gold, #D4AF37);' : '' ?>">
                                            View
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT PANEL: Selected Enquiry Details (7 Structured Sections) -->
    <?php if ($activeEnquiry): ?>
        <div class="admin-card" style="border: 2px solid var(--admin-gold, #D4AF37); box-shadow: 0 8px 30px rgba(0,0,0,0.6);">
            
            <!-- SECTION 1: Enquiry Reference & Received Date Header -->
            <div class="admin-card-header" style="background: var(--admin-bg-card-secondary, #171C2B); border-bottom: 1px solid rgba(212, 175, 55, 0.25); display: flex; justify-content: space-between; align-items: flex-start; padding: 16px 20px;">
                <div>
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--admin-gold, #D4AF37); letter-spacing: 0.05em; margin-bottom: 2px;">
                        Customer Print Enquiry Details
                    </div>
                    <h2 class="admin-card-title" style="margin: 0; font-size: 1.25rem; color: #FFFFFF;">
                        <?= e($activeEnquiry['ref_number']) ?>
                    </h2>
                    <span style="font-size: 0.78rem; color: var(--admin-text-muted, #94A3B8);">
                        Received on <?= date('d M Y, h:i A', strtotime($activeEnquiry['created_at'])) ?>
                    </span>
                </div>
                <a href="<?= url('admin/enquiries.php' . ($statusFilter ? '?status=' . urlencode($statusFilter) : '')) ?>" 
                   style="color: #94A3B8; font-size: 1.5rem; text-decoration: none; line-height: 1; padding: 2px 6px; border-radius: 4px;" 
                   title="Close Enquiry Details">&times;</a>
            </div>

            <div class="admin-card-body" style="padding: 20px; display: flex; flex-direction: column; gap: 20px;">

                <!-- SECTION 2: Pipeline Status -->
                <div style="background: #11141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px 16px;">
                    <form method="POST" action="<?= url('admin/enquiries.php') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="enquiry_id" value="<?= $activeEnquiry['id'] ?>">

                        <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                            <div>
                                <label style="font-size: 0.74rem; font-weight: 700; text-transform: uppercase; color: var(--admin-gold, #D4AF37); display: block; margin-bottom: 5px; letter-spacing: 0.04em;">
                                    Pipeline Status
                                </label>
                                <select name="status" class="form-select form-select-sm" style="min-width: 170px; padding: 6px 12px; font-weight: 600;">
                                    <option value="received" <?= $activeEnquiry['status'] === 'received' ? 'selected' : '' ?>>● Received</option>
                                    <option value="under_review" <?= $activeEnquiry['status'] === 'under_review' ? 'selected' : '' ?>>● Under Review</option>
                                    <option value="quoted" <?= $activeEnquiry['status'] === 'quoted' ? 'selected' : '' ?>>● Quoted</option>
                                    <option value="converted" <?= $activeEnquiry['status'] === 'converted' ? 'selected' : '' ?>>● Converted to Order</option>
                                    <option value="rejected" <?= $activeEnquiry['status'] === 'rejected' ? 'selected' : '' ?>>○ Rejected</option>
                                    <option value="closed" <?= $activeEnquiry['status'] === 'closed' ? 'selected' : '' ?>>○ Closed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.5rem 1rem; font-weight: 700;">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SECTION 3: Customer Details -->
                <div style="background: #11141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px 16px;">
                    <h3 style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-gold, #D4AF37); border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin: 0 0 12px 0;">
                        Customer Details
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; font-size: 0.85rem;">
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Customer Name</span>
                            <strong style="color: #FFFFFF; font-size: 0.95rem;"><?= e($activeEnquiry['customer_name']) ?></strong>
                            <?php if ($activeEnquiry['user_id']): ?>
                                <span class="badge" style="background: rgba(49, 89, 166, 0.25); color: #60A5FA; border: 1px solid rgba(49, 89, 166, 0.4); font-size: 0.65rem; margin-left: 4px;">Registered User</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Company / Brand</span>
                            <strong style="color: #F3F4F6;"><?= e($activeEnquiry['company_name'] ?: 'Individual / N/A') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Email Address</span>
                            <a href="mailto:<?= e($activeEnquiry['customer_email']) ?>" style="color: #60A5FA; word-break: break-all; font-weight: 500;">
                                <?= e($activeEnquiry['customer_email']) ?>
                            </a>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Phone / WhatsApp</span>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $activeEnquiry['customer_phone']) ?>?text=<?= urlencode('Hello ' . $activeEnquiry['customer_name'] . ', regarding your OrderPrintex enquiry ' . $activeEnquiry['ref_number']) ?>" target="_blank" style="color: #25D366; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                💬 <?= e($activeEnquiry['customer_phone']) ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Print Specifications -->
                <div style="background: #11141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px 16px;">
                    <h3 style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-gold, #D4AF37); border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin: 0 0 12px 0;">
                        Print Specifications
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; font-size: 0.85rem;">
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Selected Service</span>
                            <strong style="color: #FFFFFF; font-size: 0.95rem;"><?= e($activeEnquiry['service_name'] ?: 'Custom Print') ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Requested Quantity</span>
                            <strong style="color: var(--admin-gold, #D4AF37); font-size: 1rem;"><?= intval($activeEnquiry['quantity']) ?> Units / Meters</strong>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Dimensions</span>
                            <strong style="color: #F3F4F6;">
                                <?= ($activeEnquiry['width_inches'] && $activeEnquiry['height_inches']) ? ($activeEnquiry['width_inches'] . '" &times; ' . $activeEnquiry['height_inches'] . '"') : 'Custom / Gang Sheet' ?>
                            </strong>
                        </div>
                        <div>
                            <span style="color: var(--admin-text-muted, #94A3B8); display: block; font-size: 0.72rem; margin-bottom: 2px;">Material / Variant</span>
                            <strong style="color: #F3F4F6;"><?= e($activeEnquiry['material_options'] ?: 'Standard Production') ?></strong>
                        </div>
                    </div>

                    <?php if (!empty($activeEnquiry['customer_notes'])): ?>
                        <div style="margin-top: 12px; background: rgba(0,0,0,0.35); padding: 10px 12px; border-radius: 6px; font-size: 0.835rem; border-left: 3px solid var(--admin-gold, #D4AF37);">
                            <span style="font-size: 0.72rem; font-weight: 700; color: var(--admin-gold, #D4AF37); display: block; margin-bottom: 3px; text-transform: uppercase;">Customer Instructions / Notes:</span>
                            <p style="margin: 0; color: #E4E4E7; line-height: 1.45; white-space: pre-line;"><?= e($activeEnquiry['customer_notes']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 5: Customer Uploaded Artwork (Thumbnail Gallery & Full-Screen Viewer Integration) -->
                <div style="background: #11141E; border: 1px solid rgba(212, 175, 55, 0.25); border-radius: 8px; padding: 14px 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin-bottom: 12px;">
                        <h3 style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-gold, #D4AF37); margin: 0;">
                            Customer Uploaded Artwork
                        </h3>
                        <span style="font-size: 0.72rem; color: var(--admin-text-muted, #94A3B8);">
                            <?= count($enquiryFiles) ?> File<?= count($enquiryFiles) !== 1 ? 's' : '' ?> Attached
                        </span>
                    </div>

                    <?php if (empty($enquiryFiles)): ?>
                        <div class="no-artwork-notice">
                            No artwork uploaded for this enquiry.
                        </div>
                    <?php else: ?>
                        <!-- Horizontal Thumbnail Gallery -->
                        <div class="artwork-thumbnails-gallery" data-artwork-gallery="enquiry-<?= $activeEnquiry['id'] ?>">
                            <?php foreach ($enquiryFiles as $idx => $f): ?>
                                <div class="artwork-thumb-card">
                                    <?php if ($f['is_image']): ?>
                                        <?php if ($f['exists']): ?>
                                            <!-- Valid Image Thumbnail -->
                                            <div class="artwork-thumb-box" 
                                                 data-artwork-thumb="true"
                                                 data-artwork-src="<?= e($f['stream_url']) ?>" 
                                                 data-artwork-title="<?= e($f['original_name']) ?> (<?= format_file_size($f['file_size']) ?>)"
                                                 data-artwork-filename="<?= e($f['original_name']) ?>"
                                                 data-artwork-download="<?= e($f['download_url']) ?>"
                                                 title="Click to inspect artwork in full-screen viewer with zoom">
                                                <img src="<?= e($f['stream_url']) ?>" alt="<?= e($f['original_name']) ?>" class="artwork-thumb-img" loading="lazy">
                                                <div class="artwork-thumb-overlay">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                                                    <span>View Artwork</span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <!-- Missing File Warning (No Broken Image Tag) -->
                                            <div class="artwork-thumb-doc" style="border: 1px dashed #EF4444; color: #EF4444;" title="Uploaded file was not found on server disk">
                                                <span style="font-size: 1.3rem;">⚠️</span>
                                                <span style="font-size: 0.65rem; font-weight: 700;">File Missing</span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- PDF / Vector Document Artwork -->
                                        <div class="artwork-thumb-doc">
                                            <span style="font-size: 1.3rem;">📄</span>
                                            <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--admin-gold, #D4AF37); font-size: 0.65rem; font-weight: 700;">
                                                <?= strtoupper(e($f['file_extension'])) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Thumbnail Label & Action -->
                                    <div class="artwork-thumb-info">
                                        <span class="artwork-thumb-name" title="<?= e($f['original_name']) ?>">
                                            <?= e($f['original_name']) ?>
                                        </span>
                                        <span class="artwork-thumb-size">
                                            <?= format_file_size($f['file_size']) ?>
                                        </span>
                                        <?php if ($f['is_image'] && $f['exists']): ?>
                                            <span class="artwork-thumb-action" onclick="AdminArtworkViewer.open(<?= htmlspecialchars(json_encode($artworkGalleryData), ENT_QUOTES, 'UTF-8') ?>, <?= $idx ?>)">
                                                <span>🔍 View Artwork</span>
                                            </span>
                                        <?php elseif ($f['is_doc'] && $f['exists']): ?>
                                            <a href="<?= e($f['download_url']) ?>" class="artwork-thumb-action" download="<?= e($f['original_name']) ?>">
                                                <span>⬇️ Download File</span>
                                            </a>
                                        <?php else: ?>
                                            <span style="font-size: 0.65rem; color: #EF4444; font-weight: 600;">Unavailable</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 6: Quotation Pipeline -->
                <div style="background: #11141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px 16px;">
                    <h3 style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-gold, #D4AF37); border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin: 0 0 12px 0;">
                        Quotation Actions
                    </h3>
                    <?php if ($activeEnquiry['existing_quote_id']): ?>
                        <div style="background: rgba(49, 89, 166, 0.12); border: 1px solid rgba(96, 165, 250, 0.4); padding: 14px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #60A5FA;">Linked Commercial Quotation</span>
                                <div style="font-weight: 700; font-size: 1.15rem; color: #FFFFFF;"><?= e($activeEnquiry['quote_number']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--admin-text-muted, #94A3B8);">
                                    Total: <strong style="color: var(--admin-gold, #D4AF37);"><?= format_price($activeEnquiry['quote_amount']) ?></strong> &bull; Status: <?= status_badge($activeEnquiry['quote_status']) ?>
                                </div>
                            </div>
                            <a href="<?= url('admin/quotations.php?quote_id=' . $activeEnquiry['existing_quote_id']) ?>" class="btn btn-primary btn-sm" style="font-weight: 700;">
                                View / Edit Quote &rarr;
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('admin/quotations.php?action=create&enquiry_id=' . $activeEnquiry['id']) ?>" class="btn btn-primary btn-block" style="text-align: center; justify-content: center; font-weight: 700; padding: 0.75rem 1rem;">
                            ✨ Generate Formal Quotation for this Enquiry
                        </a>
                    <?php endif; ?>
                </div>

                <!-- SECTION 7: Enquiry Actions (Delete with custom modal confirmation) -->
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 12px; display: flex; justify-content: flex-end;">
                    <form method="POST" action="<?= url('admin/enquiries.php') ?>" style="margin: 0;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="delete_enquiry">
                        <input type="hidden" name="enquiry_id" value="<?= $activeEnquiry['id'] ?>">
                        <button type="button" 
                                data-confirm="Are you sure you want to permanently delete this customer enquiry and any uploaded artwork files?" 
                                data-confirm-title="Delete Customer Enquiry?" 
                                data-confirm-item="Enquiry #<?= e($activeEnquiry['ref_number']) ?> (<?= e($activeEnquiry['customer_name']) ?>)" 
                                data-destructive="true" 
                                data-confirm-btn="Yes, Delete Enquiry" 
                                class="btn btn-outline btn-xs" 
                                style="color: #EF4444; border-color: rgba(239, 68, 68, 0.4); padding: 0.45rem 0.9rem; font-weight: 700;">
                            🗑️ Delete Enquiry
                        </button>
                    </form>
                </div>

            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
