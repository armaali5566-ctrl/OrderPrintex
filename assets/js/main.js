/**
 * ORDERPRINTEX — Core Frontend Interactions (Vanilla JS)
 * No jQuery, No CDN dependencies, No Frameworks
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Navigation Bar (Normal document flow, non-sticky)
  // Sticky scroll listener removed to maintain pure document flow behavior.

  // 2. Mobile Menu Off-Canvas Toggle
  const mobileToggle = document.querySelector('.mobile-toggle');
  const navMenu = document.querySelector('.nav-menu');
  if (mobileToggle && navMenu) {
    const closeMobileMenu = function () {
      navMenu.classList.remove('open');
      mobileToggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      document.body.classList.remove('mobile-menu-open');
    };

    mobileToggle.addEventListener('click', function () {
      const isOpen = navMenu.classList.toggle('open');
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.style.overflow = isOpen ? 'hidden' : '';
      document.body.classList.toggle('mobile-menu-open', isOpen);
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target) && navMenu.classList.contains('open')) {
        closeMobileMenu();
      }
    });

    // Close menu when clicking regular navigation links inside drawer
    navMenu.querySelectorAll('a:not(.dropdown-toggle)').forEach(link => {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 1120) {
          closeMobileMenu();
        }
      });
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

  // 7. Light / Dark Theme Switcher (Public Storefront Only)
  (function initThemeSwitcher() {
    // Admin Panel maintains one permanent luxury dark theme
    if (window.location.pathname.includes('/admin/') || document.querySelector('.dashboard-layout')) {
      document.documentElement.setAttribute('data-theme', 'dark');
      return;
    }

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
  // 8. DECOMMISSIONED E-COMMERCE UTILITIES (Wishlist & Compare)
  // OrderPrintex operates strictly as a bespoke Enquiry & Quotation platform.
  // ==============================================================
  try {
    localStorage.removeItem('orderprintex_wishlist');
    localStorage.removeItem('orderprintex_compare');
  } catch(e) {}

  window.toggleWishlist = function() {};
  window.toggleCompareProduct = function() {};
  window.removeCompareItem = function() {};
  window.clearCompareList = function() {};
  window.openCompareModal = function() {};
  window.closeCompareModal = function() {};
});

