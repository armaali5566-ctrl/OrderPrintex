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
        alert('Unsupported file format. Please upload PNG, JPG, PDF, AI, PSD, or SVG.');
        fileInput.value = '';
        if (previewArea) previewArea.innerHTML = '';
        return;
      }

      // Validate Size
      if (file.size > maxSizeBytes) {
        alert('File size exceeds the 25 MB maximum limit.');
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

    // Dynamic Square Inches Calculation
    function updateDimensions() {
      if (!widthInput || !heightInput || !sqInchesDisplay) return;
      const w = parseFloat(widthInput.value) || 0;
      const h = parseFloat(heightInput.value) || 0;
      if (w > 0 && h > 0) {
        const sqInches = (w * h).toFixed(1);
        sqInchesDisplay.textContent = `${sqInches} sq. in. (${w}" × ${h}")`;
      } else {
        sqInchesDisplay.textContent = '—';
      }
    }

    if (widthInput && heightInput) {
      widthInput.addEventListener('input', updateDimensions);
      heightInput.addEventListener('input', updateDimensions);
    }
  });
});
