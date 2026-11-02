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

  // Mobile Dropdown Click Toggle
  const dropdownItems = document.querySelectorAll('.nav-item.has-dropdown');
  dropdownItems.forEach(item => {
    const link = item.querySelector('.nav-link');
    if (link) {
      link.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
          e.preventDefault();
          item.classList.toggle('dropdown-open');
        }
      });
    }
  });

  // 3. Interactive FAQ Accordion
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const trigger = item.querySelector('.faq-question-btn, .faq-trigger, .faq-question');
    if (trigger) {
      trigger.addEventListener('click', function () {
        const isActive = item.classList.contains('active');
        // Close siblings for accordion behavior
        faqItems.forEach(sibling => sibling.classList.remove('active'));
        if (!isActive) {
          item.classList.add('active');
        }
      });
    }
  });

  // 4. Gallery Category Filter Tabs
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
});

