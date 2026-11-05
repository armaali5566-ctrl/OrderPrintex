<?php
/**
 * OrderPrintex - Admin Media Library
 * Centralized Image Uploader, Storage Browser & Asset Manager
 */

$adminTitle = "Media Library & Image Manager";
require_once __DIR__ . '/header.php';

$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

// Handle Upload
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        set_flash('error', 'Security token expired. Please try again.');
        header('Location: ' . url('admin/media.php'));
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'upload_media') {
        if (!empty($_FILES['media_files']['name'][0])) {
            $uploadedCount = 0;
            $totalFiles = count($_FILES['media_files']['name']);

            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['media_files']['error'][$i] === UPLOAD_ERR_OK) {
                    $singleFile = [
                        'name'     => $_FILES['media_files']['name'][$i],
                        'type'     => $_FILES['media_files']['type'][$i],
                        'tmp_name' => $_FILES['media_files']['tmp_name'][$i],
                        'error'    => $_FILES['media_files']['error'][$i],
                        'size'     => $_FILES['media_files']['size'][$i]
                    ];
                    $res = handle_file_upload($singleFile, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                    if ($res['success']) {
                        $uploadedCount++;
                    }
                }
            }

            if ($uploadedCount > 0) {
                log_activity($adminUser['id'], 'upload_media', "Uploaded {$uploadedCount} media files");
                set_flash('success', "Successfully uploaded {$uploadedCount} image(s) to media library.");
            } else {
                set_flash('error', "No valid images could be uploaded. Ensure format is JPG, PNG, WEBP or SVG.");
            }
        }
        header('Location: ' . url('admin/media.php'));
        exit;
    }

    if ($action === 'delete_media') {
        $filename = basename(trim($_POST['filename'] ?? ''));
        $targetPath = $uploadDir . '/' . $filename;
        if (file_exists($targetPath) && is_file($targetPath)) {
            @unlink($targetPath);
            log_activity($adminUser['id'], 'delete_media', "Deleted media file: {$filename}");
            set_flash('success', "File '{$filename}' deleted from storage.");
        }
        header('Location: ' . url('admin/media.php'));
        exit;
    }
}

// Scan files in uploads directory
$files = [];
if (is_dir($uploadDir)) {
    $scanned = scandir($uploadDir);
    foreach ($scanned as $f) {
        if ($f !== '.' && $f !== '..' && $f !== '.gitkeep') {
            $fullPath = $uploadDir . '/' . $f;
            if (is_file($fullPath)) {
                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                    $files[] = [
                        'name'     => $f,
                        'url'      => 'uploads/' . $f,
                        'full_url' => url('uploads/' . $f),
                        'size'     => filesize($fullPath),
                        'mtime'    => filemtime($fullPath)
                    ];
                }
            }
        }
    }
}

// Also include key assets/images files for quick reference
$assetDir = __DIR__ . '/../assets/images';
$assetFiles = [];
if (is_dir($assetDir)) {
    $scannedAssets = scandir($assetDir);
    foreach ($scannedAssets as $af) {
        if ($af !== '.' && $af !== '..' && is_file($assetDir . '/' . $af)) {
            $ext = strtolower(pathinfo($af, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                $assetFiles[] = [
                    'name'     => $af,
                    'url'      => 'assets/images/' . $af,
                    'full_url' => url('assets/images/' . $af),
                    'size'     => filesize($assetDir . '/' . $af),
                    'mtime'    => filemtime($assetDir . '/' . $af)
                ];
            }
        }
    }
}

// Sort newest first
usort($files, fn($a, $b) => $b['mtime'] <=> $a['mtime']);
?>

<div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 class="admin-page-title" style="font-size:1.6rem; color:var(--navy); font-weight:800; margin-bottom:0.25rem;">
      Media Library
    </h1>
    <p class="admin-page-subtitle" style="color:var(--slate); margin:0; font-size:0.9rem;">
      Upload high-resolution product photos, variation swatches, and marketing banners.
    </p>
  </div>
  <div class="admin-topbar-actions" style="display:flex; gap:0.75rem;">
    <a href="<?= url('admin/products.php') ?>" class="btn btn-navy btn-sm">
      &larr; Products Catalog
    </a>
  </div>
</div>

<!-- Upload Dropzone Card -->
<div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card); margin-bottom:2rem;">
  <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin-bottom:1rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.5rem;">
    Upload New Images
  </h3>

  <form method="POST" action="<?= url('admin/media.php') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="upload_media">

    <div style="border:2px dashed var(--border-light); border-radius:8px; padding:2rem; text-align:center; background:var(--ivory); cursor:pointer;" onclick="document.getElementById('mediaFileInput').click()">
      <div style="font-size:2rem; color:var(--royal); margin-bottom:0.5rem;">📁</div>
      <div style="font-weight:700; color:var(--navy); font-size:1.05rem;">Click to Select Images or Drop Files Here</div>
      <div style="color:var(--slate); font-size:0.825rem; margin-top:0.25rem;">Supported formats: PNG, JPG, JPEG, WEBP, SVG (Max 15MB each)</div>
      <input type="file" id="mediaFileInput" name="media_files[]" multiple accept="image/*" style="display:none;" onchange="this.form.submit()">
    </div>
  </form>
</div>

<!-- Tabs: Uploaded Media vs System Assets -->
<div class="admin-card" style="background:var(--bg-surface); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.75rem; box-shadow: var(--shadow-card);">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid rgba(20,35,60,0.08); padding-bottom:0.75rem;">
    <h3 style="font-size:1.1rem; color:var(--navy); font-weight:700; margin:0;">
      Uploaded Media (<?= count($files) ?> files)
    </h3>
    <small style="color:var(--text-muted); font-size:0.8rem;">Click "Copy Path" on any image to use in products or variations</small>
  </div>

  <?php if (empty($files)): ?>
    <div style="text-align:center; padding:3rem; background:var(--ivory); border-radius:8px; color:var(--text-muted);">
      <p style="margin:0 0 0.5rem; font-size:1rem; font-weight:600; color:var(--navy);">No user uploads yet</p>
      <p style="margin:0; font-size:0.85rem;">Uploaded files will appear here with instant path copying.</p>
    </div>
  <?php else: ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:1.25rem;">
      <?php foreach ($files as $file): ?>
        <div style="border:1px solid rgba(20,35,60,0.10); border-radius:8px; overflow:hidden; background:var(--bg-surface); display:flex; flex-direction:column; box-shadow:0 2px 6px rgba(15,25,45,0.05); transition:transform 0.2s ease, box-shadow 0.2s ease;">
          <div style="height:140px; background:#F8FAFC; display:flex; align-items:center; justify-content:center; padding:0.5rem; border-bottom:1px solid var(--border-light); overflow:hidden;">
            <img src="<?= url(e($file['url'])) ?>" alt="" style="max-height:100%; max-width:100%; object-fit:contain;">
          </div>
          <div style="padding:0.75rem; flex:1; display:flex; flex-direction:column; justify-content:space-between;">
            <div>
              <div style="font-weight:700; font-size:0.8rem; color:var(--navy); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= e($file['name']) ?>">
                <?= e($file['name']) ?>
              </div>
              <div style="font-size:0.725rem; color:var(--text-muted); margin-top:2px;">
                <?= round($file['size'] / 1024, 1) ?> KB &bull; <?= date('M d, Y', $file['mtime']) ?>
              </div>
            </div>

            <div style="margin-top:0.75rem; display:flex; gap:0.4rem;">
              <button type="button" class="btn btn-xs btn-outline" style="flex:1;" onclick="copyPath('<?= e($file['url']) ?>', this)">
                Copy Path
              </button>
              <form method="POST" action="<?= url('admin/media.php') ?>" onsubmit="return confirm('Delete this image permanently?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete_media">
                <input type="hidden" name="filename" value="<?= e($file['name']) ?>">
                <button type="submit" class="btn btn-xs btn-danger">✕</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- System Stock Assets Reference -->
  <div style="margin-top:2.5rem; border-top:1px solid var(--border-light); padding-top:1.5rem;">
    <h4 style="font-size:1rem; color:var(--navy); font-weight:700; margin-bottom:1rem;">
      Default Catalog Images (<?= count($assetFiles) ?> files in assets/images/)
    </h4>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:1rem;">
      <?php foreach (array_slice($assetFiles, 0, 12) as $af): ?>
        <div style="border:1px solid var(--border-light); border-radius:6px; padding:0.5rem; background:var(--ivory); font-size:0.75rem;">
          <div style="height:90px; display:flex; align-items:center; justify-content:center; margin-bottom:0.4rem;">
            <img src="<?= url(e($af['url'])) ?>" alt="" style="max-height:100%; max-width:100%; object-fit:contain;">
          </div>
          <div style="font-weight:600; color:var(--navy); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= e($af['name']) ?></div>
          <button type="button" class="btn btn-xs btn-outline" style="width:100%; margin-top:0.4rem;" onclick="copyPath('<?= e($af['url']) ?>', this)">
            Copy Path
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<script>
function copyPath(path, btn) {
  navigator.clipboard.writeText(path).then(() => {
    const orig = btn.textContent;
    btn.textContent = 'Copied!';
    btn.style.background = 'var(--emerald)';
    btn.style.color = '#FFFFFF';
    setTimeout(() => {
      btn.textContent = orig;
      btn.style.background = '';
      btn.style.color = '';
    }, 1500);
  });
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
