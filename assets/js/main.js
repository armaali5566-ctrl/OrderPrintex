/**
 * ORDERPRINTEX — Core Frontend Interactions (Vanilla JS)
 * No jQuery, No CDN dependencies, No Frameworks
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Sticky Header Transition on Scroll
  const header = document.querySelector('.site-header');
  if (header) {
    const handleScroll = () => {
      if (window.scrollY > 30) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  // 2. Mobile Menu Off-Canvas Toggle
  const mobileToggle = document.querySelector('.mobile-toggle');
  const navMenu = document.querySelector('.nav-menu');
  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function () {
      const isOpen = navMenu.classList.toggle('open');
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target) && navMenu.classList.contains('open')) {
        navMenu.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  }

  // 2b. Restored Printing Services Dropdown (Desktop Hover & Click + Mobile Tap-to-Open)
  const dropdownItems = document.querySelectorAll('.nav-item.has-dropdown');
  dropdownItems.forEach(item => {
    const toggle = item.querySelector('.dropdown-toggle, .nav-link');
    const menu = item.querySelector('.dropdown-menu');

    if (toggle) {
      // Click handler: on mobile, prevents navigation and toggles dropdown.
      // On desktop, click also toggles the open state smoothly.
      toggle.addEventListener('click', function (e) {
        const isMobile = window.innerWidth <= 1120;
        if (isMobile) {
          e.preventDefault();
          const isOpen = item.classList.toggle('dropdown-open');
          toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        } else {
          // On desktop, allow click to toggle open state
          e.preventDefault();
          const isOpen = item.classList.toggle('dropdown-open');
          toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
      });
    }

    // Desktop hover management for accessibility & smooth hover
    item.addEventListener('mouseenter', function () {
      if (window.innerWidth > 992 && toggle) {
        toggle.setAttribute('aria-expanded', 'true');
      }
    });

    item.addEventListener('mouseleave', function () {
      if (window.innerWidth > 992) {
        item.classList.remove('dropdown-open');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function (e) {
    dropdownItems.forEach(item => {
      if (!item.contains(e.target)) {
        item.classList.remove('dropdown-open');
        const toggle = item.querySelector('.dropdown-toggle, .nav-link');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Close dropdown on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dropdownItems.forEach(item => {
        item.classList.remove('dropdown-open');
        const toggle = item.querySelector('.dropdown-toggle, .nav-link');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
      });
    }
  });

  // Close mobile drawer when any navigation or dropdown link is clicked
  const allNavLinks = document.querySelectorAll('.dropdown-link, .nav-menu .nav-link:not(.dropdown-toggle)');
  allNavLinks.forEach(link => {
    link.addEventListener('click', function () {
      if (navMenu && navMenu.classList.contains('open')) {
        navMenu.classList.remove('open');
        if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  });

  // 2c. Account User Dropdown (Hover on Desktop, Tap on Mobile & Click Outside)
  const navUserDropdown = document.getElementById('navUserDropdown');
  const navUserBtn = document.getElementById('navUserBtn');
  if (navUserDropdown && navUserBtn) {
    navUserBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = navUserDropdown.classList.toggle('is-open');
      navUserBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
      if (!navUserDropdown.contains(e.target)) {
        navUserDropdown.classList.remove('is-open');
        navUserBtn.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        navUserDropdown.classList.remove('is-open');
        navUserBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // 3. Gallery Category Filter Tabs
  const filterButtons = document.querySelectorAll('.gallery-filter-btn');
  const galleryItems = document.querySelectorAll('.gallery-item');
  if (filterButtons.length && galleryItems.length) {
    filterButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.getAttribute('data-filter') || btn.getAttribute('data-category') || 'all';

        galleryItems.forEach(item => {
          const cat = item.getAttribute('data-category');
          if (filter === 'all' || cat === filter) {
            item.style.display = 'block';
            setTimeout(() => {
              item.style.opacity = '1';
              item.style.transform = 'scale(1)';
            }, 30);
          } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.92)';
            setTimeout(() => {
              item.style.display = 'none';
            }, 250);
          }
        });
      });
    });
  }

  // 5. Generic Modal Trigger & Backdrop handlers
  document.querySelectorAll('[data-modal]').forEach(btn => {
    btn.addEventListener('click', function () {
      const modalId = btn.getAttribute('data-modal');
      const targetModal = document.getElementById(modalId);
      if (targetModal) {
        targetModal.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', function (e) {
      if (e.target === modal || e.target.classList.contains('modal-close') || e.target.closest('.modal-close')) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  });

  // 6. Interactive FAQ Accordion, Category Filter & Live Search
  const faqItems = document.querySelectorAll('.faq-item');
  const faqTriggers = document.querySelectorAll('.faq-trigger, .faq-question-btn');
  const faqPills = document.querySelectorAll('.faq-pill-btn');
  const faqSearchInput = document.querySelector('.faq-search-input');
  const faqSearchClear = document.querySelector('.faq-search-clear');
  const faqNoResults = document.querySelector('.faq-no-results');

  if (faqTriggers.length > 0) {
    faqTriggers.forEach(trigger => {
      trigger.addEventListener('click', function () {
        const item = trigger.closest('.faq-item');
        const isActive = item.classList.contains('active');

        // Toggle current item
        item.classList.toggle('active', !isActive);
        trigger.setAttribute('aria-expanded', !isActive ? 'true' : 'false');
      });
    });

    let currentCategory = 'all';
    let currentSearchTerm = '';

    function filterFaqs() {
      let visibleCount = 0;

      faqItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category') || '';
        const questionText = (item.querySelector('.faq-question')?.textContent || '').toLowerCase();
        const answerText = (item.querySelector('.faq-body, .faq-answer')?.textContent || '').toLowerCase();

        const matchesCat = (currentCategory === 'all' || itemCategory === currentCategory);
        const matchesQuery = !currentSearchTerm || questionText.includes(currentSearchTerm) || answerText.includes(currentSearchTerm);

        if (matchesCat && matchesQuery) {
          item.style.display = 'block';
          visibleCount++;
        } else {
          item.style.display = 'none';
        }
      });

      if (faqNoResults) {
        faqNoResults.style.display = (visibleCount === 0) ? 'block' : 'none';
      }
    }

    if (faqPills.length > 0) {
      faqPills.forEach(pill => {
        pill.addEventListener('click', function () {
          faqPills.forEach(p => p.classList.remove('active'));
          pill.classList.add('active');
          currentCategory = pill.getAttribute('data-category') || 'all';
          filterFaqs();
        });
      });
    }

    if (faqSearchInput) {
      faqSearchInput.addEventListener('input', function () {
        currentSearchTerm = faqSearchInput.value.trim().toLowerCase();
        if (faqSearchClear) {
          faqSearchClear.style.display = currentSearchTerm.length > 0 ? 'block' : 'none';
        }
        filterFaqs();
      });

      if (faqSearchClear) {
        faqSearchClear.addEventListener('click', function () {
          faqSearchInput.value = '';
          currentSearchTerm = '';
          faqSearchClear.style.display = 'none';
          faqSearchInput.focus();
          filterFaqs();
        });
      }
    }
  }

  // Escape key closes modals and mobile navigation
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-backdrop.open').forEach(m => m.classList.remove('open'));
      document.body.style.overflow = '';
    }
  });

  // 6. Top Announcement Bar Slider & Dismissal
  const announcementBar = document.getElementById('topAnnouncementBar');
  if (announcementBar) {
    if (sessionStorage.getItem('op_announcement_dismissed') === '1') {
      announcementBar.style.display = 'none';
    } else {
      const slides = announcementBar.querySelectorAll('.announcement-slide');
      if (slides.length > 1) {
        let currentSlide = 0;
        setInterval(() => {
          slides[currentSlide].classList.remove('active');
          currentSlide = (currentSlide + 1) % slides.length;
          slides[currentSlide].classList.add('active');
        }, 4500);
      }
    }
  }

  window.dismissAnnouncement = function () {
    const bar = document.getElementById('topAnnouncementBar');
    if (bar) {
      bar.style.transition = 'opacity 0.25s ease, height 0.25s ease';
      bar.style.opacity = '0';
      setTimeout(() => {
        bar.style.display = 'none';
        sessionStorage.setItem('op_announcement_dismissed', '1');
      }, 250);
    }
  };

  // 7. Light / Dark Theme Switcher
  (function initThemeSwitcher() {
    function getTheme() {
      return document.documentElement.getAttribute('data-theme') || 'light';
    }

    function setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      try {
        localStorage.setItem('op_theme', theme);
      } catch (e) {}

      document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
        btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        btn.setAttribute('title', theme === 'dark' ? 'Switch to Light Theme' : 'Switch to Dark Theme');
      });
    }

    document.addEventListener('click', function(e) {
      const btn = e.target.closest('.theme-toggle-btn');
      if (btn) {
        e.preventDefault();
        const nextTheme = getTheme() === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme);
      }
    });

    setTheme(getTheme());
  })();

  // ==============================================================
  // 8. GLOBAL WISHLIST MANAGER (Phase 8)
  // ==============================================================
  window.toggleWishlist = async function(productId, btn) {
    if (!productId) return;
    productId = parseInt(productId, 10);

    let wishlisted = false;
    let saved = [];
    try {
      saved = JSON.parse(localStorage.getItem('orderprintex_wishlist') || '[]');
    } catch(e) {}

    if (saved.includes(productId)) {
      saved = saved.filter(id => id !== productId);
      wishlisted = false;
    } else {
      saved.push(productId);
      wishlisted = true;
    }
    localStorage.setItem('orderprintex_wishlist', JSON.stringify(saved));

    document.querySelectorAll(`.btn-wishlist[data-product-id="${productId}"], button[onclick*="toggleWishlist(${productId}"]`).forEach(b => {
      b.classList.toggle('is-active', wishlisted);
      b.setAttribute('data-wishlisted', wishlisted ? 'true' : 'false');
    });

    try {
      const formData = new FormData();
      formData.append('action', 'toggle');
      formData.append('product_id', productId);
      await fetch('/api/wishlist.php', { method: 'POST', body: formData });
    } catch(e) {}

    const wishItem = document.getElementById('wishItem_' + productId);
    if (wishItem && !wishlisted) {
      wishItem.remove();
      const container = document.getElementById('wishlistContainer');
      const emptyState = document.getElementById('wishlistEmptyState');
      if (container && container.children.length === 0 && emptyState) {
        emptyState.style.display = 'block';
      }
    }
  };

  try {
    const saved = JSON.parse(localStorage.getItem('orderprintex_wishlist') || '[]');
    saved.forEach(id => {
      document.querySelectorAll(`.btn-wishlist[data-product-id="${id}"], button[onclick*="toggleWishlist(${id}"]`).forEach(b => {
        b.classList.add('is-active');
        b.setAttribute('data-wishlisted', 'true');
      });
    });
  } catch(e) {}

  // ==============================================================
  // 9. PRODUCT COMPARISON MANAGER (Phase 5)
  // ==============================================================
  let compareList = [];
  try {
    compareList = JSON.parse(localStorage.getItem('orderprintex_compare') || '[]');
  } catch(e) {}

  window.toggleCompareProduct = function(productId, cb) {
    productId = parseInt(productId, 10);
    if (cb.checked) {
      if (compareList.length >= 3) {
        alert('You can compare a maximum of 3 products at a time.');
        cb.checked = false;
        return;
      }
      if (!compareList.includes(productId)) {
        compareList.push(productId);
      }
    } else {
      compareList = compareList.filter(id => id !== productId);
    }
    localStorage.setItem('orderprintex_compare', JSON.stringify(compareList));
    renderCompareTray();
  };

  window.removeCompareItem = function(productId) {
    compareList = compareList.filter(id => id !== productId);
    localStorage.setItem('orderprintex_compare', JSON.stringify(compareList));
    document.querySelectorAll(`.opx-compare-cb[data-product-id="${productId}"]`).forEach(cb => cb.checked = false);
    renderCompareTray();
    if (document.getElementById('compareModalBackdrop')?.classList.contains('is-active')) {
      openCompareModal();
    }
  };

  window.clearCompareList = function() {
    compareList = [];
    localStorage.setItem('orderprintex_compare', JSON.stringify([]));
    document.querySelectorAll('.opx-compare-cb').forEach(cb => cb.checked = false);
    renderCompareTray();
    closeCompareModal();
  };

  function renderCompareTray() {
    let tray = document.getElementById('compareFloatingTray');
    if (!tray) {
      tray = document.createElement('div');
      tray.id = 'compareFloatingTray';
      tray.className = 'compare-floating-tray';
      document.body.appendChild(tray);
    }

    if (compareList.length === 0) {
      tray.classList.remove('is-active');
      return;
    }

    tray.innerHTML = `
      <div style="display:flex; align-items:center; gap:0.75rem;">
        <span style="font-size:0.85rem; font-weight:700; color:#FFD76A;">Compare (${compareList.length}/3)</span>
        <button type="button" class="btn btn-primary btn-xs" onclick="openCompareModal()" style="padding:0.35rem 0.85rem;">View</button>
        <button type="button" class="btn btn-outline btn-xs" onclick="clearCompareList()" style="border-color:#40351E; color:#B8B1A3; padding:0.35rem 0.65rem;">Clear</button>
      </div>
    `;
    tray.classList.add('is-active');

    document.querySelectorAll('.opx-compare-cb').forEach(cb => {
      const pid = parseInt(cb.getAttribute('data-product-id'), 10);
      cb.checked = compareList.includes(pid);
    });
  }

  window.openCompareModal = async function() {
    if (compareList.length === 0) return;
    let modal = document.getElementById('compareModalBackdrop');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'compareModalBackdrop';
      modal.className = 'compare-modal-backdrop';
      modal.onclick = function(e) { if (e.target === modal) closeCompareModal(); };
      document.body.appendChild(modal);
    }

    modal.innerHTML = `
      <div class="compare-modal-box">
        <div class="compare-modal-header">
          <h3 style="margin:0; font-size:1.25rem; color:#FFD76A; font-weight:800;">Product Comparison (${compareList.length} Selected)</h3>
          <button type="button" class="lightbox-btn" onclick="closeCompareModal()" style="position:static; width:36px; height:36px; font-size:1rem;">✕</button>
        </div>
        <div class="compare-modal-body" id="compareModalContent">
          <div style="text-align:center; padding:3rem; color:#B8B1A3;">Loading product specifications...</div>
        </div>
      </div>
    `;
    modal.classList.add('is-active');
    document.body.style.overflow = 'hidden';

    try {
      const res = await fetch('/api/compare_products.php?ids=' + compareList.join(','));
      const data = await res.json();
      if (data.success && data.products.length > 0) {
        const prods = data.products;
        const content = document.getElementById('compareModalContent');
        content.innerHTML = `
          <table class="compare-table">
            <thead>
              <tr>
                <th style="width:160px;">Attribute</th>
                ${prods.map(p => `
                  <th style="text-align:center;">
                    <div style="position:relative; padding-top:10px;">
                      <button type="button" onclick="removeCompareItem(${p.id})" style="position:absolute; top:-6px; right:0; background:none; border:none; color:#f87171; cursor:pointer; font-size:1rem;" title="Remove">✕</button>
                      <img src="${p.image_url}" alt="${p.title}" style="height:110px; width:100%; object-fit:contain; margin-bottom:0.5rem;">
                      <div style="font-weight:700; color:#F5F1E8; font-size:0.95rem; margin-bottom:0.25rem;">${p.title}</div>
                      <span class="sku-badge" style="font-size:0.75rem;">SKU: ${p.sku}</span>
                    </div>
                  </th>
                `).join('')}
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Category</th>
                ${prods.map(p => `<td>${p.category_name}</td>`).join('')}
              </tr>
              <tr>
                <th>Base Price</th>
                ${prods.map(p => `<td style="color:#FFD76A; font-weight:800; font-size:1.1rem;">${p.starting_price} <span style="font-size:0.8rem; color:#B8B1A3; font-weight:400;">${p.price_unit}</span></td>`).join('')}
              </tr>
              <tr>
                <th>Min. Order</th>
                ${prods.map(p => `<td>${p.min_order}</td>`).join('')}
              </tr>
              <tr>
                <th>Specifications</th>
                ${prods.map(p => `<td style="font-size:0.85rem; line-height:1.5;">${p.specifications}</td>`).join('')}
              </tr>
              <tr>
                <th>Action</th>
                ${prods.map(p => `
                  <td>
                    <a href="${p.url}" class="btn btn-primary btn-sm" style="width:100%; text-align:center;">
                      View &amp; Order
                    </a>
                  </td>
                `).join('')}
              </tr>
            </tbody>
          </table>
        `;
      }
    } catch(e) {
      document.getElementById('compareModalContent').innerHTML = '<div style="text-align:center; padding:2rem; color:#f87171;">Failed to load comparison data.</div>';
    }
  };

  window.closeCompareModal = function() {
    const modal = document.getElementById('compareModalBackdrop');
    if (modal) {
      modal.classList.remove('is-active');
      document.body.style.overflow = '';
    }
  };

  renderCompareTray();
});

