/* ==========================================================================
   ORDERPRINTEX — Editorial Homepage Motion & Interactivity
   Glinto-Inspired Motion Architecture | Sticky Scroll Storytelling | 100% Native Performance
   ========================================================================== */

// 1. Slow & Smooth Scroll Entrance System (650–850ms, Staggered Reveals, Run Once)
(() => {
  const root = document.querySelector('#homepage-information');
  if (!root || !('IntersectionObserver' in window)) return;

  const reduced = matchMedia('(prefers-reduced-motion: reduce)');

  // Target sections for scroll entrance:
  // 0. Meet Printex — Photographic Banner & Intro (.studio-intro-section)
  // 1. Our Printing Process (.printing-process)
  // 2. Custom Printing Applications — Expanding Hover Gallery (.print-solutions)
  // 3. Before You Print — FAQ (.print-faq)
  const targetSections = root.querySelectorAll(
    '.studio-intro-section, .studio-editorial, .printing-process, .print-solutions, .print-faq'
  );

  // Activate CSS animation classes if reduced motion is not requested
  if (!reduced.matches) {
    root.classList.add('home-anim-ready');
  }

  // Helper to trigger section reveal
  const revealSection = (sec) => {
    sec.classList.add('scroll-revealed');

    // Trigger progressive horizontal timeline for Printing Process on desktop
    if (sec.classList.contains('printing-process')) {
      const stages = sec.querySelectorAll('.printing-stage');
      stages.forEach((stage, i) => {
        setTimeout(() => stage.classList.add('stage-revealed'), 140 + i * 90);
      });
    }
  };

  // Observer for the 4 target scroll-entrance sections
  const sectionObserver = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        revealSection(entry.target);
        obs.unobserve(entry.target); // Runs once permanently; never blinks or resets
      }
    });
  }, {
    threshold: 0.08,
    rootMargin: '0px 0px -40px 0px'
  });

  targetSections.forEach(sec => {
    sectionObserver.observe(sec);

    // Initial check: if already visible on page load/refresh, reveal immediately
    const rect = sec.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.95) {
      revealSection(sec);
      sectionObserver.unobserve(sec);
    }
  });

  if (reduced.matches) {
    targetSections.forEach(sec => sec.classList.add('scroll-revealed'));
  }
})();

// 1.5 Mobile Scroll-Linked Printing Process Timeline Controller (Bidirectional Progress Line & Icon Illumination)
(() => {
  const timeline = document.querySelector('.printing-timeline');
  const scrollBar = document.getElementById('timelineScrollBar');
  const track = document.querySelector('.timeline-scroll-track');
  const wrapper = document.querySelector('.printing-timeline-wrapper');
  if (!timeline || !scrollBar || !track) return;

  const stages = timeline.querySelectorAll('.printing-stage');
  const circles = timeline.querySelectorAll('.stage-marker-circle');
  if (!stages.length || !circles.length) return;

  const reduced = matchMedia('(prefers-reduced-motion: reduce)');

  let isMobile = window.innerWidth <= 900;
  let ticking = false;

  const updateTrackDimensions = () => {
    isMobile = window.innerWidth <= 900;
    if (!isMobile) return;

    const wrapRect = (wrapper || timeline).getBoundingClientRect();
    const firstCircle = circles[0];
    const lastCircle = circles[circles.length - 1];

    const firstRect = firstCircle.getBoundingClientRect();
    const lastRect = lastCircle.getBoundingClientRect();

    const startY = (firstRect.top + firstRect.height / 2) - wrapRect.top;
    const endY = (lastRect.top + lastRect.height / 2) - wrapRect.top;
    const totalDistance = Math.max(0, endY - startY);

    track.style.top = `${startY.toFixed(1)}px`;
    track.style.height = `${totalDistance.toFixed(1)}px`;
  };

  const updateScrollProgress = () => {
    ticking = false;
    if (!isMobile) return;

    if (reduced.matches) {
      scrollBar.style.transform = 'scaleY(1)';
      stages.forEach(st => st.classList.add('stage-active'));
      return;
    }

    const firstCircle = circles[0];
    const lastCircle = circles[circles.length - 1];

    const firstRect = firstCircle.getBoundingClientRect();
    const lastRect = lastCircle.getBoundingClientRect();

    const startY = firstRect.top + firstRect.height / 2;
    const endY = lastRect.top + lastRect.height / 2;
    const totalDistance = endY - startY;

    if (totalDistance <= 0) return;

    // Viewport focal line: at 62% of screen height (natural mobile reading focus)
    const focalY = window.innerHeight * 0.62;

    // Calculate real-time scroll progress from Circle 1 to Circle 4
    let progress = (focalY - startY) / totalDistance;
    progress = Math.max(0, Math.min(1, progress));

    scrollBar.style.transform = `scaleY(${progress.toFixed(4)})`;

    // Dynamically illuminate or de-illuminate stages based on line position
    stages.forEach((stage, idx) => {
      const circle = circles[idx];
      if (!circle) return;
      const cRect = circle.getBoundingClientRect();
      const circleCenterY = cRect.top + cRect.height / 2;

      // Stage becomes active when the scroll line reaches its circle center
      const hasReached = focalY >= (circleCenterY - 4);
      stage.classList.toggle('stage-active', hasReached);
    });
  };

  const onScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(updateScrollProgress);
      ticking = true;
    }
  };

  const onResize = () => {
    updateTrackDimensions();
    if (!ticking) {
      window.requestAnimationFrame(updateScrollProgress);
      ticking = true;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onResize, { passive: true });

  // Initialize on load and when fonts/layout are ready
  updateTrackDimensions();
  updateScrollProgress();

  if (document.fonts) {
    document.fonts.ready.then(() => {
      updateTrackDimensions();
      updateScrollProgress();
    });
  }
  window.addEventListener('load', () => {
    updateTrackDimensions();
    updateScrollProgress();
  });
})();

// 2. Meet Printex — Subtle Photographic Background Scroll Parallax
(() => {
  const section = document.querySelector('#meet-printex-section');
  if (!section) return;

  const bgImg = section.querySelector('.studio-banner-bg img, .studio-bg-visual img');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');

  if (bgImg && !reduced.matches) {
    window.addEventListener('scroll', () => {
      const rect = section.getBoundingClientRect();
      if (rect.bottom < 0 || rect.top > window.innerHeight) return;
      const offset = (rect.top - window.innerHeight * 0.4) * 0.045;
      bgImg.style.transform = `scale(1.02) translate3d(0, ${offset.toFixed(1)}px, 0)`;
    }, { passive: true });
  }
})();

// 3. Printing Services — Sticky Image Deck Controller (Focal Line Center Sync)
(() => {
  const showcase = document.querySelector('.services-sticky-showcase');
  if (!showcase) return;

  const slides = showcase.querySelectorAll('.services-deck-slide');
  const steps = showcase.querySelectorAll('.print-story-step');

  if (!slides.length || !steps.length) return;

  let activeIndex = -1;

  const setActiveService = (index) => {
    if (index === activeIndex || index < 0 || index >= slides.length) return;
    activeIndex = index;

    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });

    steps.forEach((step, i) => {
      step.classList.toggle('active-service', i === index);
    });
  };

  // Initially activate DTF Printing (step 0)
  setActiveService(0);

  // Synchronize on scroll using requestAnimationFrame for rock-solid stability
  let ticking = false;

  const updateActiveServiceOnScroll = () => {
    ticking = false;
    if (window.innerWidth <= 900) return; // Mobile uses stacked layout

    const showcaseRect = showcase.getBoundingClientRect();
    if (showcaseRect.bottom < 0 || showcaseRect.top > window.innerHeight) return;

    // Use the vertical center of the sticky frame as the focal line
    const stickyCol = showcase.querySelector('.services-sticky-visual-column');
    const stickyRect = stickyCol ? stickyCol.getBoundingClientRect() : null;
    const focalY = stickyRect ? stickyRect.top + stickyRect.height * 0.5 : window.innerHeight * 0.45;

    let bestIndex = 0;
    let minDistance = Infinity;

    steps.forEach((step, idx) => {
      const rect = step.getBoundingClientRect();
      if (rect.top <= focalY && rect.bottom >= focalY) {
        bestIndex = idx;
        minDistance = 0;
      } else if (minDistance > 0) {
        const dist = Math.min(Math.abs(rect.top - focalY), Math.abs(rect.bottom - focalY));
        if (dist < minDistance) {
          minDistance = dist;
          bestIndex = idx;
        }
      }
    });

    setActiveService(bestIndex);
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(updateActiveServiceOnScroll);
      ticking = true;
    }
  }, { passive: true });

  window.addEventListener('resize', () => {
    if (!ticking) {
      window.requestAnimationFrame(updateActiveServiceOnScroll);
      ticking = true;
    }
  }, { passive: true });

  // Initial check on load
  updateActiveServiceOnScroll();
})();

// 4. FAQ Accordion Enhancement & Keyboard Accessibility
document.addEventListener('DOMContentLoaded', () => {
  const faq = document.querySelector('#homepage-information .print-faq');
  if (!faq) return;

  const items = faq.querySelectorAll('.faq-item');
  items.forEach((item, i) => {
    item.classList.toggle('active', i === 0);
    const trigger = item.querySelector('.faq-trigger');
    if (trigger) {
      trigger.setAttribute('aria-expanded', i === 0 ? 'true' : 'false');
      trigger.addEventListener('keydown', e => {
        if (e.key === ' ' || e.code === 'Space') {
          e.preventDefault();
          trigger.click();
        }
      });
    }
  });

  faq.classList.add('print-faq-ready');
});

// 4.5 Printing Applications — Expanding Hover Gallery Controller
(() => {
  const container = document.querySelector('#homepage-information .expanding-gallery-container');
  if (!container) return;

  const rows = container.querySelectorAll('.expanding-gallery-row');
  if (!rows.length) return;

  // Preload all gallery panel images into browser cache for instant rendering
  const allImages = container.querySelectorAll('.gallery-panel-bg img');
  allImages.forEach(img => {
    if (img && img.src) {
      const p = new Image();
      p.src = img.src;
    }
  });

  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Initialize each row independently: exactly ONE active card per row (TWO active cards total across gallery)
  rows.forEach((row, rowIndex) => {
    const panels = [...row.querySelectorAll('.gallery-panel')];
    if (!panels.length) return;

    // Default for Row 1: index 0 (Personal Gifts)
    // Default for Row 2: last panel (Branded Merchandise, index 3 in row)
    const defaultIndex = rowIndex === 0 ? 0 : panels.length - 1;
    let activeIndex = panels.findIndex(p => p.classList.contains('active'));
    if (activeIndex === -1) {
      activeIndex = defaultIndex;
    }

    let revealTimer = null;
    let leaveTimer = null;

    const setActiveInRow = (index, focus = false) => {
      if (index < 0 || index >= panels.length) return;
      if (activeIndex === index) return;
      activeIndex = index;

      clearTimeout(revealTimer);

      panels.forEach((panel, i) => {
        const isActive = i === index;
        panel.classList.toggle('active', isActive);
        panel.setAttribute('aria-expanded', isActive ? 'true' : 'false');

        // When collapsing, immediately strip text-revealed state
        if (!isActive) {
          panel.classList.remove('text-revealed');
        }
      });

      if (reduced) {
        panels[index].classList.add('text-revealed');
      } else {
        // Begin revealing text only when the card is nearly fully expanded (at 380ms of 500ms transition)
        revealTimer = setTimeout(() => {
          if (activeIndex === index && panels[index] && panels[index].classList.contains('active')) {
            panels[index].classList.add('text-revealed');
          }
        }, 380);
      }

      if (focus && panels[index]) {
        panels[index].focus();
      }
    };

    // Ensure initial active state for this row on page load (show text immediately without waiting)
    panels.forEach((panel, i) => {
      const isActive = i === activeIndex;
      panel.classList.toggle('active', isActive);
      panel.setAttribute('aria-expanded', isActive ? 'true' : 'false');
      if (isActive) {
        panel.classList.add('text-revealed');
      } else {
        panel.classList.remove('text-revealed');
      }
    });

    // Restore default expanded card when mouse leaves the row (desktop only)
    row.addEventListener('mouseleave', () => {
      if (window.innerWidth > 768) {
        clearTimeout(leaveTimer);
        leaveTimer = setTimeout(() => {
          setActiveInRow(defaultIndex, false);
        }, 75);
      }
    });

    row.addEventListener('mouseenter', () => {
      clearTimeout(leaveTimer);
    });

    panels.forEach((panel, i) => {
      // TransitionEnd listener: Immediate backup trigger to add text-revealed if flex/height finishes
      panel.addEventListener('transitionend', (e) => {
        if (e.target === panel && (e.propertyName === 'flex-grow' || e.propertyName === 'flex' || e.propertyName === 'height')) {
          if (panel.classList.contains('active') && i === activeIndex) {
            panel.classList.add('text-revealed');
          }
        }
      });

      // Immediate desktop hover expansion within its row (disabled on touch/mobile)
      panel.addEventListener('mouseenter', () => {
        clearTimeout(leaveTimer);
        if (window.innerWidth > 768) {
          setActiveInRow(i, false);
        }
      });

      // Touch & Click support (allow inner link clicks to navigate naturally)
      panel.addEventListener('click', (e) => {
        if (e.target.closest('a')) return;
        setActiveInRow(i, false);
      });

      // Keyboard navigation (Enter / Space to expand, Tab focus)
      panel.addEventListener('keydown', (e) => {
        if (e.target.closest('a')) return;
        if (e.key === 'Enter' || e.key === ' ' || e.code === 'Space') {
          e.preventDefault();
          setActiveInRow(i, true);
        }
      });

      panel.addEventListener('focus', () => {
        setActiveInRow(i, false);
      });
    });
  });
})();

// 5. Infinite Seamless Marquee (Never Gaps, Constant Speed, No Unexpected Pause)
(() => {
  if (!document.body.classList.contains('home-page')) return;

  const setup = () => document.querySelectorAll('.marquee-container').forEach(strip => {
    const tracks = [...strip.querySelectorAll(':scope > .marquee-track')];
    if (tracks.length !== 2 || tracks[0].textContent !== tracks[1].textContent) return;

    const source = [...tracks[0].children].map(el => el.cloneNode(true));
    const belt = document.createElement('div');
    belt.className = 'home-marquee-belt';
    strip.append(belt);
    tracks.forEach(track => belt.append(track));

    let lastWidth = 0;
    const size = () => {
      const currentWidth = strip.clientWidth;
      if (currentWidth === lastWidth && lastWidth > 0) return;
      lastWidth = currentWidth;

      tracks.forEach(track => track.replaceChildren(...source.map(el => el.cloneNode(true))));
      const baseWidth = tracks[0].getBoundingClientRect().width;
      if (!baseWidth) return;

      const repeats = Math.max(2, Math.ceil((currentWidth + 200) / baseWidth));
      for (let i = 1; i < repeats; i++) {
        tracks.forEach(track => track.append(...source.map(el => el.cloneNode(true))));
      }

      const durationSeconds = (28 / 0.65) * repeats;
      belt.style.setProperty('--home-marquee-duration', `${durationSeconds}s`);
    };

    size();
    if ('ResizeObserver' in window) {
      new ResizeObserver(size).observe(strip);
    } else {
      window.addEventListener('resize', size, { passive: true });
    }
  });

  if (document.fonts) {
    document.fonts.ready.then(setup);
  } else {
    setup();
  }
})();
