/**
 * ORDERPRINTEX — Admin Customer Artwork Full-Screen Viewer with Pan & Zoom
 * Supports Zoom In/Out, 1:1, Fit to Screen, Mouse Wheel, Drag Pan, Touch Pinch, Prev/Next Gallery Navigation
 */

(function (window, document) {
    'use strict';

    let viewerEl = null;
    let imgEl = null;
    let titleEl = null;
    let counterEl = null;
    let zoomTextEl = null;
    let prevBtn = null;
    let nextBtn = null;
    let downloadBtn = null;
    let viewportEl = null;
    let stageEl = null;

    // Viewer State
    let images = [];
    let currentIndex = 0;
    let scale = 1.0;
    let panX = 0;
    let panY = 0;
    let isDragging = false;
    let startX = 0;
    let startY = 0;
    let initialTouchDistance = 0;
    let initialScale = 1.0;

    const MIN_SCALE = 0.25;
    const MAX_SCALE = 5.0;
    const SCALE_STEP = 0.25;

    function createViewerDOM() {
        if (viewerEl) return;

        viewerEl = document.createElement('div');
        viewerEl.id = 'adminArtworkViewer';
        viewerEl.className = 'artwork-viewer-overlay';
        viewerEl.setAttribute('role', 'dialog');
        viewerEl.setAttribute('aria-modal', 'true');
        viewerEl.setAttribute('aria-label', 'Artwork Full Screen Inspection Viewer');

        viewerEl.innerHTML = `
            <div class="artwork-viewer-header">
                <div class="artwork-viewer-meta">
                    <span class="artwork-viewer-icon">🎨</span>
                    <span class="artwork-viewer-title" id="artworkViewerTitle">Customer Artwork</span>
                    <span class="artwork-viewer-counter" id="artworkViewerCounter">1 / 1</span>
                </div>

                <div class="artwork-viewer-toolbar">
                    <button type="button" class="viewer-btn" id="viewerZoomOut" title="Zoom Out (-)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                    </button>
                    <span class="viewer-zoom-level" id="viewerZoomLevel">100%</span>
                    <button type="button" class="viewer-btn" id="viewerZoomIn" title="Zoom In (+)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                    </button>
                    <button type="button" class="viewer-btn viewer-btn-text" id="viewerReset" title="Reset Zoom (1:1)">
                        100%
                    </button>
                    <button type="button" class="viewer-btn viewer-btn-text" id="viewerFit" title="Fit to Screen">
                        Fit
                    </button>
                    <button type="button" class="viewer-btn viewer-btn-text" id="viewerOriginal" title="Natural Resolution">
                        Actual Size
                    </button>
                    <a href="#" class="viewer-btn viewer-btn-download" id="viewerDownload" download title="Download Original Artwork">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Download</span>
                    </a>
                </div>

                <div class="artwork-viewer-actions">
                    <button type="button" class="viewer-btn-close" id="viewerClose" title="Close Viewer (Esc)">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>

            <div class="artwork-viewer-viewport" id="artworkViewerViewport">
                <button type="button" class="viewer-nav-btn viewer-nav-prev" id="viewerPrev" title="Previous Artwork (Left Arrow)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>

                <div class="artwork-viewer-stage" id="artworkViewerStage">
                    <img src="" alt="" class="artwork-viewer-img" id="artworkViewerImg" draggable="false">
                </div>

                <button type="button" class="viewer-nav-btn viewer-nav-next" id="viewerNext" title="Next Artwork (Right Arrow)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>

            <div class="artwork-viewer-footer">
                <span class="artwork-viewer-hint">Scroll to Zoom &bull; Drag to Pan when zoomed &bull; Esc to close &bull; Arrow keys for next/previous</span>
            </div>
        `;

        document.body.appendChild(viewerEl);

        // Bind DOM references
        imgEl        = document.getElementById('artworkViewerImg');
        titleEl      = document.getElementById('artworkViewerTitle');
        counterEl    = document.getElementById('artworkViewerCounter');
        zoomTextEl   = document.getElementById('viewerZoomLevel');
        prevBtn      = document.getElementById('viewerPrev');
        nextBtn      = document.getElementById('viewerNext');
        downloadBtn  = document.getElementById('viewerDownload');
        viewportEl   = document.getElementById('artworkViewerViewport');
        stageEl      = document.getElementById('artworkViewerStage');

        // Toolbar events
        document.getElementById('viewerZoomIn').addEventListener('click', () => zoomBy(SCALE_STEP));
        document.getElementById('viewerZoomOut').addEventListener('click', () => zoomBy(-SCALE_STEP));
        document.getElementById('viewerReset').addEventListener('click', resetZoom);
        document.getElementById('viewerFit').addEventListener('click', fitToScreen);
        document.getElementById('viewerOriginal').addEventListener('click', actualSize);
        document.getElementById('viewerClose').addEventListener('click', closeViewer);

        // Navigation
        prevBtn.addEventListener('click', showPrev);
        nextBtn.addEventListener('click', showNext);

        // Click on backdrop (outside stage & header) to close
        viewportEl.addEventListener('click', (e) => {
            if (e.target === viewportEl) {
                closeViewer();
            }
        });

        // Mouse Wheel Zoom
        viewportEl.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY < 0 ? SCALE_STEP : -SCALE_STEP;
            zoomBy(delta);
        }, { passive: false });

        // Pan / Drag handlers
        stageEl.addEventListener('mousedown', onMouseDown);
        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mouseup', onMouseUp);

        // Touch handling (pinch-to-zoom & touch drag)
        stageEl.addEventListener('touchstart', onTouchStart, { passive: false });
        window.addEventListener('touchmove', onTouchMove, { passive: false });
        window.addEventListener('touchend', onTouchEnd);

        // Keyboard navigation
        window.addEventListener('keydown', (e) => {
            if (!viewerEl || !viewerEl.classList.contains('is-open')) return;

            if (e.key === 'Escape') {
                closeViewer();
            } else if (e.key === 'ArrowLeft') {
                showPrev();
            } else if (e.key === 'ArrowRight') {
                showNext();
            } else if (e.key === '+' || e.key === '=') {
                zoomBy(SCALE_STEP);
            } else if (e.key === '-' || e.key === '_') {
                zoomBy(-SCALE_STEP);
            } else if (e.key === '0') {
                resetZoom();
            }
        });
    }

    function applyTransform() {
        if (!stageEl) return;
        stageEl.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
        if (zoomTextEl) {
            zoomTextEl.textContent = `${Math.round(scale * 100)}%`;
        }
        if (scale > 1.0) {
            stageEl.style.cursor = isDragging ? 'grabbing' : 'grab';
        } else {
            stageEl.style.cursor = 'default';
        }
    }

    function zoomBy(step) {
        let newScale = scale + step;
        newScale = Math.max(MIN_SCALE, Math.min(MAX_SCALE, newScale));
        scale = parseFloat(newScale.toFixed(2));
        if (scale <= 1.0) {
            panX = 0;
            panY = 0;
        }
        applyTransform();
    }

    function resetZoom() {
        scale = 1.0;
        panX = 0;
        panY = 0;
        applyTransform();
    }

    function fitToScreen() {
        if (!imgEl || !viewportEl) return;
        const vW = viewportEl.clientWidth * 0.9;
        const vH = viewportEl.clientHeight * 0.82;
        const nW = imgEl.naturalWidth || 800;
        const nH = imgEl.naturalHeight || 600;

        const scaleW = vW / nW;
        const scaleH = vH / nH;
        scale = Math.min(1.0, Math.min(scaleW, scaleH));
        scale = Math.max(MIN_SCALE, parseFloat(scale.toFixed(2)));
        panX = 0;
        panY = 0;
        applyTransform();
    }

    function actualSize() {
        scale = 1.0;
        panX = 0;
        panY = 0;
        applyTransform();
    }

    function onMouseDown(e) {
        if (e.button !== 0) return; // Primary click only
        isDragging = true;
        startX = e.clientX - panX;
        startY = e.clientY - panY;
        stageEl.style.cursor = 'grabbing';
        e.preventDefault();
    }

    function onMouseMove(e) {
        if (!isDragging) return;
        panX = e.clientX - startX;
        panY = e.clientY - startY;
        applyTransform();
    }

    function onMouseUp() {
        if (!isDragging) return;
        isDragging = false;
        applyTransform();
    }

    function getTouchDistance(touches) {
        return Math.hypot(
            touches[0].clientX - touches[1].clientX,
            touches[0].clientY - touches[1].clientY
        );
    }

    function onTouchStart(e) {
        if (e.touches.length === 2) {
            initialTouchDistance = getTouchDistance(e.touches);
            initialScale = scale;
            e.preventDefault();
        } else if (e.touches.length === 1 && scale > 1.0) {
            isDragging = true;
            startX = e.touches[0].clientX - panX;
            startY = e.touches[0].clientY - panY;
        }
    }

    function onTouchMove(e) {
        if (e.touches.length === 2 && initialTouchDistance > 0) {
            e.preventDefault();
            const currentDistance = getTouchDistance(e.touches);
            const ratio = currentDistance / initialTouchDistance;
            let newScale = initialScale * ratio;
            newScale = Math.max(MIN_SCALE, Math.min(MAX_SCALE, newScale));
            scale = parseFloat(newScale.toFixed(2));
            applyTransform();
        } else if (e.touches.length === 1 && isDragging) {
            e.preventDefault();
            panX = e.touches[0].clientX - startX;
            panY = e.touches[0].clientY - startY;
            applyTransform();
        }
    }

    function onTouchEnd(e) {
        if (e.touches.length < 2) {
            initialTouchDistance = 0;
        }
        if (e.touches.length === 0) {
            isDragging = false;
        }
    }

    function updateImageDisplay() {
        if (!images.length || currentIndex < 0 || currentIndex >= images.length) return;

        const current = images[currentIndex];
        imgEl.src = current.src;
        titleEl.textContent = current.title || 'Customer Artwork';
        counterEl.textContent = `${currentIndex + 1} / ${images.length}`;

        if (downloadBtn) {
            downloadBtn.href = current.downloadUrl || current.src;
            downloadBtn.setAttribute('download', current.filename || 'artwork.png');
        }

        // Show/hide navigation arrows
        if (images.length > 1) {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        } else {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }

        resetZoom();
    }

    function showPrev() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateImageDisplay();
    }

    function showNext() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex + 1) % images.length;
        updateImageDisplay();
    }

    function openViewer(imageList, initialIdx = 0) {
        createViewerDOM();
        if (!Array.isArray(imageList) || !imageList.length) return;

        images = imageList;
        currentIndex = Math.max(0, Math.min(images.length - 1, initialIdx));

        updateImageDisplay();
        viewerEl.classList.add('is-open');
        document.body.classList.add('viewer-scroll-locked');
    }

    function closeViewer() {
        if (!viewerEl) return;
        viewerEl.classList.remove('is-open');
        document.body.classList.remove('viewer-scroll-locked');
        resetZoom();
        if (imgEl) imgEl.src = '';
    }

    // Initialize auto-binding for any thumbnails tagged with data-artwork-thumb
    function initArtworkThumbnails(root = document) {
        createViewerDOM();
        const galleryRoots = root.querySelectorAll('[data-artwork-gallery]');

        galleryRoots.forEach(gallery => {
            const thumbs = gallery.querySelectorAll('[data-artwork-src]');
            const galleryList = [];

            thumbs.forEach((thumb, idx) => {
                const src = thumb.getAttribute('data-artwork-src');
                const title = thumb.getAttribute('data-artwork-title') || 'Artwork';
                const filename = thumb.getAttribute('data-artwork-filename') || 'artwork';
                const downloadUrl = thumb.getAttribute('data-artwork-download') || src;

                galleryList.push({ src, title, filename, downloadUrl });

                if (!thumb._artworkViewerBound) {
                    thumb._artworkViewerBound = true;
                    thumb.addEventListener('click', (e) => {
                        e.preventDefault();
                        openViewer(galleryList, idx);
                    });
                }
            });
        });
    }

    // Expose API
    window.AdminArtworkViewer = {
        open: openViewer,
        close: closeViewer,
        init: initArtworkThumbnails
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => initArtworkThumbnails());
    } else {
        initArtworkThumbnails();
    }

})(window, document);

