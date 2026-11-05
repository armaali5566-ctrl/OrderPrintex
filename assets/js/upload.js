/**
 * ORDERPRINTEX — Artwork Uploader & Quotation Calculator
 * Handles Drag-and-Drop, File Type Validation, Client Preview & Size Estimator
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  const uploadComponents = document.querySelectorAll('.artwork-upload-component');

  uploadComponents.forEach(component => {
    const dropzone = component.querySelector('.dropzone-container');
    const fileInput = component.querySelector('.file-input');
    const previewArea = component.querySelector('.file-preview-area');
    const widthInput = component.querySelector('input[name="width_inches"]');
    const heightInput = component.querySelector('input[name="height_inches"]');
    const sqInchesDisplay = component.querySelector('.sq-inches-display');

    if (!dropzone || !fileInput) return;

    const allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'ai', 'psd', 'svg'];
    const maxSizeBytes = 25 * 1024 * 1024; // 25 MB

    // Trigger file dialog on dropzone click (if not already clicking browse button)
    dropzone.addEventListener('click', (e) => {
      if (e.target.closest('button')) return;
      fileInput.click();
    });

    // Drag and Drop Events
    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('dragover');
      });
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('dragover');
      });
    });

    dropzone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length > 0) {
        fileInput.files = files;
        handleFileSelection(files[0]);
      }
    });

    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        handleFileSelection(fileInput.files[0]);
      }
    });

    function formatBytes(bytes) {
      if (bytes < 1024) return bytes + ' bytes';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function handleFileSelection(file) {
      const ext = file.name.split('.').pop().toLowerCase();

      // Validate Extension
      if (!allowedExtensions.includes(ext)) {
        if (window.AdminToast) {
          AdminToast.error('Unsupported file format. Please upload PNG, JPG, PDF, AI, PSD, or SVG.', 'Invalid File');
        } else {
          alert('Unsupported file format. Please upload PNG, JPG, PDF, AI, PSD, or SVG.');
        }
        fileInput.value = '';
        if (previewArea) previewArea.innerHTML = '';
        return;
      }

      // Validate Size
      if (file.size > maxSizeBytes) {
        if (window.AdminToast) {
          AdminToast.error('File size exceeds the 25 MB maximum limit.', 'File Too Large');
        } else {
          alert('File size exceeds the 25 MB maximum limit.');
        }
        fileInput.value = '';
        if (previewArea) previewArea.innerHTML = '';
        return;
      }

      if (!previewArea) return;

      // Construct file card
      const isImage = ['png', 'jpg', 'jpeg', 'svg'].includes(ext);
      let thumbHtml = '';

      if (isImage) {
        thumbHtml = `<img class="file-preview-thumb" src="" alt="Thumbnail Preview" id="thumb_${Date.now()}">`;
      } else {
        // Document / Vector Badge
        const badgeColor = ext === 'pdf' ? '#E11D48' : ext === 'ai' ? '#F97316' : '#2563EB';
        thumbHtml = `
          <div class="file-preview-thumb" style="display:flex;align-items:center;justify-content:center;background:${badgeColor};color:#fff;font-weight:700;font-size:0.75rem;">
            ${ext.toUpperCase()}
          </div>`;
      }

      previewArea.innerHTML = `
        <div class="file-preview-card fade-in">
          <div class="file-preview-info">
            ${thumbHtml}
            <div>
              <div class="file-name" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</div>
              <div class="file-meta">${formatBytes(file.size)} &bull; .${ext.toUpperCase()}</div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:0.5rem;">
            <button type="button" class="btn btn-sm btn-outline replace-file-btn">Replace</button>
            <button type="button" class="file-remove-btn" title="Remove File">&times; Remove</button>
          </div>
        </div>
      `;

      // Read image preview if image
      if (isImage) {
        const reader = new FileReader();
        const imgEl = previewArea.querySelector('.file-preview-thumb');
        reader.onload = (e) => {
          if (imgEl) imgEl.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }

      // Attach remove handler
      previewArea.querySelector('.file-remove-btn').addEventListener('click', () => {
        fileInput.value = '';
        previewArea.innerHTML = '';
      });

      // Attach replace handler
      previewArea.querySelector('.replace-file-btn').addEventListener('click', () => {
        fileInput.click();
      });
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Dynamic Calculations: Square Inches & Estimated Price Range
    const qtyInput = component.querySelector('input[name="quantity"]');
    const serviceInput = component.querySelector('select[name="service_name"]');
    const priceDisplay = component.querySelector('.price-range-display');

    function updateCalculations() {
      const w = parseFloat(widthInput ? widthInput.value : 0) || 0;
      const h = parseFloat(heightInput ? heightInput.value : 0) || 0;
      const qty = Math.max(1, parseInt(qtyInput ? qtyInput.value : 1, 10) || 1);
      const svc = serviceInput ? serviceInput.value.toLowerCase() : '';

      // Update sq inches display
      if (sqInchesDisplay) {
        if (w > 0 && h > 0) {
          const sqInches = (w * h).toFixed(1);
          sqInchesDisplay.textContent = `${sqInches} sq. in. (${w}" × ${h}")`;
        } else {
          sqInchesDisplay.textContent = '—';
        }
      }

      // Update dynamic Estimated Price Range
      if (priceDisplay) {
        let baseLow = 249;
        let baseHigh = 349;
        const totalSqInches = (w > 0 && h > 0) ? (w * h) : 0;

        if (svc.includes('uv')) {
          // UV DTF Sticker / Decal Sheets
          if (totalSqInches > 0) {
            baseLow = Math.max(299, Math.round(totalSqInches * 0.14 * qty));
            baseHigh = Math.max(399, Math.round(totalSqInches * 0.20 * qty));
          } else {
            baseLow = 349 * Math.min(qty, 3);
            baseHigh = 449 * Math.min(qty, 3);
          }
        } else if (svc.includes('t-shirt') || svc.includes('tee') || svc.includes('apparel')) {
          // Custom T-Shirts
          baseLow = 389 * qty;
          baseHigh = 549 * qty;
        } else if (svc.includes('corporate') || svc.includes('gift')) {
          // Corporate merchandise
          baseLow = 450 * qty;
          baseHigh = 850 * qty;
        } else {
          // DTF Printing / Gang Sheets (default)
          if (totalSqInches > 0) {
            baseLow = Math.max(199, Math.round(totalSqInches * 0.08 * qty));
            baseHigh = Math.max(299, Math.round(totalSqInches * 0.12 * qty));
          } else {
            baseLow = 249 * Math.min(qty, 3);
            baseHigh = 349 * Math.min(qty, 3);
          }
        }

        // Apply wholesale tier discounting for higher quantities
        if (qty >= 20) {
          baseLow = Math.round(baseLow * 0.85);
          baseHigh = Math.round(baseHigh * 0.85);
        } else if (qty >= 10) {
          baseLow = Math.round(baseLow * 0.90);
          baseHigh = Math.round(baseHigh * 0.90);
        }

        priceDisplay.textContent = `₹${baseLow.toLocaleString('en-IN')} – ₹${baseHigh.toLocaleString('en-IN')}`;
      }
    }

    if (widthInput) widthInput.addEventListener('input', updateCalculations);
    if (heightInput) heightInput.addEventListener('input', updateCalculations);
    if (qtyInput) {
      qtyInput.addEventListener('input', updateCalculations);
      qtyInput.addEventListener('change', updateCalculations);
    }
    if (serviceInput) serviceInput.addEventListener('change', updateCalculations);

    // Initial run
    updateCalculations();
  });
});
