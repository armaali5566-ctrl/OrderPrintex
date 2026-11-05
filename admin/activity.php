<?php
/**
 * OrderPrintex - Administration Security & Activity Log
 * Full audit trails, administrator actions, enquiry events, quotation lifecycles & authentication logs
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();
$adminUser = auth_user();

// Filters & Pagination
$searchTerm = trim($_GET['search'] ?? '');
$eventType  = trim($_GET['event_type'] ?? '');
$dateFrom   = trim($_GET['date_from'] ?? '');
$dateTo     = trim($_GET['date_to'] ?? '');
$page       = max(1, intval($_GET['page'] ?? 1));
$perPage    = 25;
$offset     = ($page - 1) * $perPage;

$where = ["a.action NOT IN ('seo_sync')"];
$params = [];

if ($searchTerm !== '') {
    $where[] = "(a.details LIKE :search OR u.name LIKE :search2 OR u.email LIKE :search3 OR a.ip_address LIKE :search4)";
    $params['search']  = "%{$searchTerm}%";
    $params['search2'] = "%{$searchTerm}%";
    $params['search3'] = "%{$searchTerm}%";
    $params['search4'] = "%{$searchTerm}%";
}

if ($eventType !== '') {
    $where[] = "a.action = :action";
    $params['action'] = $eventType;
}

if ($dateFrom !== '') {
    $where[] = "DATE(a.created_at) >= :date_from";
    $params['date_from'] = $dateFrom;
}

if ($dateTo !== '') {
    $where[] = "DATE(a.created_at) <= :date_to";
    $params['date_to'] = $dateTo;
}

$whereClause = implode(' AND ', $where);

// Count Total Records
$totalCount = (int) (db_fetch_one("
    SELECT COUNT(*) as cnt 
    FROM activity_logs a 
    LEFT JOIN users u ON a.user_id = u.id 
    WHERE {$whereClause}
", $params)['cnt'] ?? 0);

$totalPages = max(1, ceil($totalCount / $perPage));

// Fetch Paginated Logs
$logs = db_fetch_all("
    SELECT a.*, u.name as user_name, u.email as user_email, u.role as user_role
    FROM activity_logs a
    LEFT JOIN users u ON a.user_id = u.id
    WHERE {$whereClause}
    ORDER BY a.created_at DESC
    LIMIT {$perPage} OFFSET {$offset}
", $params);

// Distinct event types for filter dropdown
$distinctEvents = db_fetch_all("
    SELECT DISTINCT action 
    FROM activity_logs 
    WHERE action NOT IN ('seo_sync')
    ORDER BY action ASC
");

$adminTitle = "Security & Activity Log";
require_once __DIR__ . '/header.php';
?>

<div class="admin-topbar">
  <div>
    <h1 class="admin-page-title">Security &amp; Activity Log</h1>
    <p class="admin-page-subtitle">Complete chronological audit trail of administrator actions, client interactions, authentication events and system changes.</p>
  </div>
</div>

<!-- Filter Bar -->
<div class="admin-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
  <form method="GET" action="<?= url('admin/activity.php') ?>" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:flex-end;">
    <div style="flex: 2; min-width: 220px;">
      <label class="form-label" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--admin-text-muted);">Search Details / User / IP</label>
      <input type="text" name="search" value="<?= e($searchTerm) ?>" placeholder="Search keyword, email, name, IP..." class="form-input form-input-sm">
    </div>

    <div style="flex: 1.2; min-width: 180px;">
      <label class="form-label" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--admin-text-muted);">Event Type</label>
      <select name="event_type" class="form-select form-select-sm">
        <option value="">All Event Types</option>
        <?php foreach ($distinctEvents as $evt): ?>
          <option value="<?= e($evt['action']) ?>" <?= ($eventType === $evt['action']) ? 'selected' : '' ?>>
            <?= e(ucwords(str_replace('_', ' ', $evt['action']))) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="flex: 1; min-width: 130px;">
      <label class="form-label" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--admin-text-muted);">Date From</label>
      <input type="date" name="date_from" value="<?= e($dateFrom) ?>" class="form-input form-input-sm">
    </div>

    <div style="flex: 1; min-width: 130px;">
      <label class="form-label" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--admin-text-muted);">Date To</label>
      <input type="date" name="date_to" value="<?= e($dateTo) ?>" class="form-input form-input-sm">
    </div>

    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-secondary btn-sm" style="height:36px;">Apply Filter</button>
      <?php if ($searchTerm !== '' || $eventType !== '' || $dateFrom !== '' || $dateTo !== ''): ?>
        <a href="<?= url('admin/activity.php') ?>" class="btn btn-outline btn-sm" style="height:36px; display:inline-flex; align-items:center;">Clear</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Logs Table Card -->
<div class="admin-card">
  <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2 class="admin-card-title">Activity Trail (<?= number_format($totalCount) ?> Records)</h2>
    <span style="font-size:0.8rem; color:var(--admin-text-muted);">
      Page <?= $page ?> of <?= $totalPages ?>
    </span>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:170px;">Timestamp</th>
          <th style="width:180px;">User / Identity</th>
          <th style="width:160px;">Event Type</th>
          <th>Action Details</th>
          <th style="width:130px;">IP Address</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($logs)): ?>
          <tr>
            <td colspan="5" style="text-align:center; padding:3rem; color:var(--admin-text-muted);">
              No activity logs found matching the selected filters.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($logs as $log): ?>
            <?php
              $act = $log['action'];
              $badgeClass = 'badge-secondary';
              if (str_contains($act, 'login') || str_contains($act, 'create') || str_contains($act, 'add')) {
                  $badgeClass = 'badge-success';
              } elseif (str_contains($act, 'update') || str_contains($act, 'edit')) {
                  $badgeClass = 'badge-primary';
              } elseif (str_contains($act, 'delete') || str_contains($act, 'remove') || str_contains($act, 'reject')) {
                  $badgeClass = 'badge-danger';
              } elseif (str_contains($act, 'enquiry') || str_contains($act, 'quote')) {
                  $badgeClass = 'badge-warning';
              }
            ?>
            <tr>
              <td style="color:var(--admin-text-muted); font-size:0.8rem; white-space:nowrap;">
                <?= format_date($log['created_at'], 'd M Y, h:i A') ?>
              </td>
              <td>
                <div style="font-weight:600; color:var(--admin-text-main);">
                  <?= e($log['user_name'] ?: ($log['user_email'] ?: 'System')) ?>
                </div>
                <?php if ($log['user_email']): ?>
                  <div style="font-size:0.75rem; color:var(--admin-text-muted);"><?= e($log['user_email']) ?></div>
                <?php endif; ?>
                <?php if ($log['user_role']): ?>
                  <span style="font-size:0.68rem; color:var(--admin-gold); text-transform:uppercase; font-weight:600;"><?= e($log['user_role']) ?></span>
                <?php endif; ?>
              </td>
              <td>
                <span class="status-badge <?= $badgeClass ?>" style="font-size:0.75rem;">
                  <?= e(ucwords(str_replace('_', ' ', $log['action']))) ?>
                </span>
              </td>
              <td style="font-size:0.85rem; line-height:1.45; color:var(--admin-text-main);">
                <?= e($log['details'] ?: '—') ?>
              </td>
              <td style="font-family:monospace; font-size:0.78rem; color:var(--admin-text-muted); white-space:nowrap;">
                <?= e($log['ip_address'] ?: '—') ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination Controls -->
  <?php if ($totalPages > 1): ?>
    <div style="padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.06); flex-wrap:wrap; gap:0.5rem;">
      <div style="font-size:0.8rem; color:var(--admin-text-muted);">
        Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalCount) ?> of <?= $totalCount ?> entries
      </div>
      <div style="display:flex; gap:0.35rem;">
        <?php if ($page > 1): ?>
          <a href="<?= url('admin/activity.php?page=' . ($page - 1) . ($searchTerm ? '&search=' . urlencode($searchTerm) : '') . ($eventType ? '&event_type=' . urlencode($eventType) : '') . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" class="btn btn-outline btn-xs">&larr; Previous</a>
        <?php endif; ?>

        <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
          <a href="<?= url('admin/activity.php?page=' . $p . ($searchTerm ? '&search=' . urlencode($searchTerm) : '') . ($eventType ? '&event_type=' . urlencode($eventType) : '') . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" class="btn btn-xs <?= $p === $page ? 'btn-primary' : 'btn-outline' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="<?= url('admin/activity.php?page=' . ($page + 1) . ($searchTerm ? '&search=' . urlencode($searchTerm) : '') . ($eventType ? '&event_type=' . urlencode($eventType) : '') . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" class="btn btn-outline btn-xs">Next &rarr;</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

