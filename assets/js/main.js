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

  // ==============================================================
  // 9. UNIVERSAL STUDIO CONTACT POPUP & 5s AUTO-POPUP (SHOW ONCE)
  // Shared across public pages & Expanding Hover Gallery
  // ==============================================================
  (function initUniversalContactPopup() {
    // 1. Never execute on admin pages or dashboard layouts
    if (window.location.pathname.indexOf('/admin') !== -1 ||
        document.querySelector('.dashboard-layout') ||
        document.body.classList.contains('admin-body')) {
      return;
    }

    const modal = document.getElementById('galleryContactModal');
    const form = document.getElementById('galleryContactForm');
    if (!modal || !form) return;

    const modalServiceTitle = document.getElementById('galleryModalServiceTitle');
    const serviceSelect = document.getElementById('popupService');
    const fullNameInput = document.getElementById('popupFullName');
    const mobileInput = document.getElementById('popupMobile');
    const emailInput = document.getElementById('popupEmail');
    const messageInput = document.getElementById('popupMessage');

    const errorFullName = document.getElementById('errorFullName');
    const errorMobile = document.getElementById('errorMobile');
    const errorEmail = document.getElementById('errorEmail');
    const errorService = document.getElementById('errorService');

    // Remove legacy permanent localStorage restriction so popup triggers after every refresh/page load
    try {
      localStorage.removeItem('orderprintex_auto_popup_shown');
    } catch (e) {}

    // Page-load scoped timer and state (resets on every refresh or navigation)
    let autoPopupTriggeredThisPage = false;
    let autoPopupTimer = null;
    let lastActiveElement = null;
    let isUserTypingActive = false;
    let typingDebounceTimer = null;

    // Clear validation error messages & styles
    function clearErrors() {
      [errorFullName, errorMobile, errorEmail, errorService].forEach(function(el) {
        if (el) el.textContent = '';
      });
      [fullNameInput, mobileInput, emailInput, serviceSelect].forEach(function(el) {
        if (el) el.classList.remove('has-error');
      });
    }

    // Open modal function
    function openContactModal(appName, triggerEl, isAuto) {
      // Cancel pending auto timer if open triggered manually or automatically
      if (autoPopupTimer) {
        clearTimeout(autoPopupTimer);
        autoPopupTimer = null;
      }

      // Mark as triggered so auto popup never triggers multiple times during this page load
      autoPopupTriggeredThisPage = true;

      lastActiveElement = triggerEl || document.activeElement;
      clearErrors();

      // Configure service selection and title
      if (appName) {
        if (modalServiceTitle) modalServiceTitle.textContent = appName;
        if (serviceSelect) {
          let found = false;
          for (let i = 0; i < serviceSelect.options.length; i++) {
            if (serviceSelect.options[i].value.toLowerCase() === appName.toLowerCase()) {
              serviceSelect.selectedIndex = i;
              found = true;
              break;
            }
          }
          if (!found) {
            const opt = new Option(appName, appName, true, true);
            serviceSelect.add(opt);
          }
        }
      } else {
        // Auto-popup or generic inquiry: neutral default state
        if (modalServiceTitle) modalServiceTitle.textContent = 'Custom Printing';
        if (serviceSelect) {
          serviceSelect.value = '';
        }
      }

      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';

      // Focus first input for keyboard accessibility
      setTimeout(function() {
        if (fullNameInput) fullNameInput.focus();
      }, 70);
    }

    // Close modal function (does not alter localStorage flag)
    function closeContactModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';

      if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
        try { lastActiveElement.focus(); } catch (e) {}
      }
    }

    // Close buttons & backdrop click
    modal.querySelectorAll('[data-close-modal]').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        closeContactModal();
      });
    });

    // Keyboard navigation & Focus Trapping
    document.addEventListener('keydown', function(e) {
      if (!modal.classList.contains('is-open')) return;

      if (e.key === 'Escape' || e.code === 'Escape') {
        e.preventDefault();
        closeContactModal();
        return;
      }

      if (e.key === 'Tab' || e.code === 'Tab') {
        const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const focusableEls = Array.prototype.filter.call(focusable, function(el) {
          return !el.disabled && el.offsetParent !== null;
        });
        if (!focusableEls.length) return;

        const firstEl = focusableEls[0];
        const lastEl = focusableEls[focusableEls.length - 1];

        if (e.shiftKey) {
          if (document.activeElement === firstEl) {
            e.preventDefault();
            lastEl.focus();
          }
        } else {
          if (document.activeElement === lastEl) {
            e.preventDefault();
            firstEl.focus();
          }
        }
      }
    });

    // Delegated click listener for gallery links and external open buttons
    document.addEventListener('click', function(e) {
      const trigger = e.target.closest('.gallery-panel-link, [data-open-contact-modal]');
      if (trigger) {
        e.preventDefault();
        e.stopPropagation();
        const appName = trigger.getAttribute('data-app-name') ||
                        trigger.closest('.gallery-panel')?.querySelector('.gallery-panel-title')?.textContent.trim() ||
                        null;
        openContactModal(appName, trigger, false);
      }
    });

    // Update title when user changes service select
    if (serviceSelect && modalServiceTitle) {
      serviceSelect.addEventListener('change', function() {
        modalServiceTitle.textContent = serviceSelect.value || 'Custom Printing';
      });
    }

    // Clear field errors as user types
    if (fullNameInput) fullNameInput.addEventListener('input', function() {
      fullNameInput.classList.remove('has-error');
      if (errorFullName) errorFullName.textContent = '';
    });
    if (mobileInput) mobileInput.addEventListener('input', function() {
      mobileInput.classList.remove('has-error');
      if (errorMobile) errorMobile.textContent = '';
    });
    if (emailInput) emailInput.addEventListener('input', function() {
      emailInput.classList.remove('has-error');
      if (errorEmail) errorEmail.textContent = '';
    });
    if (serviceSelect) serviceSelect.addEventListener('change', function() {
      serviceSelect.classList.remove('has-error');
      if (errorService) errorService.textContent = '';
    });

    // Form submission & validation
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      clearErrors();

      const fullName = fullNameInput ? fullNameInput.value.trim() : '';
      const mobile = mobileInput ? mobileInput.value.trim() : '';
      const email = emailInput ? emailInput.value.trim() : '';
      const service = serviceSelect ? serviceSelect.value.trim() : '';
      const message = messageInput ? messageInput.value.trim() : '';

      let hasError = false;
      let firstErrorField = null;

      // Full Name validation
      if (!fullName || fullName.length < 2) {
        if (errorFullName) errorFullName.textContent = 'Please enter your full name.';
        if (fullNameInput) fullNameInput.classList.add('has-error');
        hasError = true;
        if (!firstErrorField) firstErrorField = fullNameInput;
      }

      // Mobile Number validation (10-digit Indian phone or international 10-14 digits)
      const phoneClean = mobile.replace(/[\s\-\(\)]/g, '');
      const phoneRegex = /^(\+?\d{1,4})?[6-9]\d{9}$/;
      const generalPhoneRegex = /^\+?[0-9]{10,14}$/;
      if (!mobile || (!phoneRegex.test(phoneClean) && !generalPhoneRegex.test(phoneClean))) {
        if (errorMobile) errorMobile.textContent = 'Please enter a valid 10-digit mobile number.';
        if (mobileInput) mobileInput.classList.add('has-error');
        hasError = true;
        if (!firstErrorField) firstErrorField = mobileInput;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
      if (!email || !emailRegex.test(email)) {
        if (errorEmail) errorEmail.textContent = 'Please enter a valid email address.';
        if (emailInput) emailInput.classList.add('has-error');
        hasError = true;
        if (!firstErrorField) firstErrorField = emailInput;
      }

      // Service selection validation
      if (!service) {
        if (errorService) errorService.textContent = 'Please select an application or service.';
        if (serviceSelect) serviceSelect.classList.add('has-error');
        hasError = true;
        if (!firstErrorField) firstErrorField = serviceSelect;
      }

      // If validation fails, preserve entered data and focus first error field
      if (hasError) {
        if (firstErrorField) firstErrorField.focus();
        return;
      }

      // Valid: transfer inquiry details to sessionStorage for Contact page prefill
      try {
        sessionStorage.setItem('op_gallery_enquiry', JSON.stringify({
          name: fullName,
          phone: mobile,
          email: email,
          service: service,
          message: message,
          timestamp: Date.now()
        }));
      } catch (err) {
        console.warn('Storage error:', err);
      }

      // Redirect to contact page
      const contactUrl = modal.getAttribute('data-contact-url') || 'contact.php';
      window.location.href = contactUrl;
    });

    // Track user active typing outside of modal
    document.addEventListener('keydown', function(e) {
      const target = e.target;
      if (!target || !target.tagName) return;
      const tag = target.tagName.toLowerCase();
      if (tag === 'input' || tag === 'textarea' || target.isContentEditable) {
        if (modal.contains(target)) return;
        isUserTypingActive = true;
        clearTimeout(typingDebounceTimer);
        typingDebounceTimer = setTimeout(function() {
          isUserTypingActive = false;
        }, 2500);
      }
    }, true);

    // Helper: is user currently typing or focused in a form control?
    function isUserInteractingWithForm() {
      if (isUserTypingActive) return true;
      const active = document.activeElement;
      if (!active || active === document.body || modal.contains(active)) return false;
      const tag = active.tagName ? active.tagName.toLowerCase() : '';
      return tag === 'input' || tag === 'textarea' || tag === 'select' || active.isContentEditable;
    }

    // Helper: is another modal or drawer open?
    function isAnotherModalOpen() {
      const openModals = document.querySelectorAll(
        '.modal.show, .modal.open, .modal.is-open:not(#galleryContactModal), [role="dialog"][aria-hidden="false"]:not(#galleryContactModal)'
      );
      return openModals.length > 0 || document.body.classList.contains('mobile-menu-open');
    }

    // Helper: has enquiry details just been transferred to Contact Us page?
    function isContactPageWithTransferredEnquiry() {
      const isContactPage = window.location.pathname.indexOf('contact') !== -1;
      if (!isContactPage) {
        try { sessionStorage.removeItem('op_gallery_enquiry_transferred'); } catch (e) {}
        return false;
      }
      try {
        return !!(sessionStorage.getItem('op_gallery_enquiry') ||
                  sessionStorage.getItem('op_gallery_enquiry_transferred') ||
                  window.opGalleryEnquiryTransferred);
      } catch (e) {
        return false;
      }
    }

    // Attempt to trigger auto popup after 5 seconds with interaction deferral
    function attemptAutoPopup() {
      if (autoPopupTriggeredThisPage) return;
      if (modal.classList.contains('is-open')) return;
      if (isAnotherModalOpen()) return;
      if (isContactPageWithTransferredEnquiry()) return;

      // If user is actively typing, defer until they finish their interaction
      if (isUserInteractingWithForm()) {
        const active = document.activeElement;
        const deferCheck = function() {
          if (active) {
            active.removeEventListener('blur', deferCheck);
            active.removeEventListener('change', deferCheck);
          }
          // Wait 1.5s after blur to verify user didn't just tab to next field
          setTimeout(function() {
            if (!isUserInteractingWithForm()) {
              attemptAutoPopup();
            } else {
              deferCheck();
            }
          }, 1500);
        };

        if (active) {
          active.addEventListener('blur', deferCheck, { once: true });
          active.addEventListener('change', deferCheck, { once: true });
        } else {
          setTimeout(attemptAutoPopup, 2000);
        }
        return;
      }

      // Display the automatic contact popup (resets on next page load/refresh)
      openContactModal(null, null, true);
    }

    // Schedule 5-second timer on every public page load or refresh
    if (!isContactPageWithTransferredEnquiry()) {
      autoPopupTimer = setTimeout(attemptAutoPopup, 5000);
    }
  })();
});

