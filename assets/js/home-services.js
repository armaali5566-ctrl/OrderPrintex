/* ==========================================================================
   ORDERPRINTEX — Editorial Homepage Motion & Interactivity
   Glinto-Inspired Motion Architecture | Sticky Scroll Storytelling | 100% Native Performance
   ========================================================================== */

// 1. Glinto-Inspired Scroll Entrance & Reveal System
(() => {
  const root = document.querySelector('#homepage-information');
  if (!root || !('IntersectionObserver' in window) || !Element.prototype.animate) return;

  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const activeAnimations = new Set();
  const seenElements = new WeakSet();

  const GLINTO_EASING = 'cubic-bezier(0.16, 1, 0.3, 1)';
  const DURATION_NORMAL = 750;

  const animateElement = (el, x, y, delay = 0, scaleStart = 1, scaleEnd = 1) => {
    if (reduced.matches || !el) return;
    const anim = el.animate([
      { opacity: 0, transform: `translate3d(${x}px, ${y}px, 0) scale(${scaleStart})` },
      { opacity: 1, transform: `translate3d(0, 0, 0) scale(${scaleEnd})` }
    ], {
      duration: DURATION_NORMAL,
      delay,
      easing: GLINTO_EASING,
      fill: 'forwards'
    });
    activeAnimations.add(anim);
    anim.finished.then(() => activeAnimations.delete(anim), () => activeAnimations.delete(anim));
    return anim;
  };

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      const el = entry.target;
      if (!entry.isIntersecting) return;
      if (seenElements.has(el) || reduced.matches) {
        obs.unobserve(el);
        return;
      }
      seenElements.add(el);
      obs.unobserve(el);

      const isMobile = window.innerWidth <= 800;
      const verticalDistance = isMobile ? 14 : 24;

      // Studio Editorial Heading Line Stroke
      if (el.classList.contains('studio-editorial-heading')) {
        el.classList.add('line-active');
        [...el.children].forEach((child, i) => {
          animateElement(child, 0, verticalDistance, i * 65);
        });
        return;
      }

      // Printing Process Progressive Timeline Flow
      if (el.classList.contains('printing-process') || el.classList.contains('printing-timeline')) {
        const timeline = el.classList.contains('printing-timeline') ? el : el.querySelector('.printing-timeline');
        if (timeline) {
          timeline.classList.add('timeline-revealed');
          const stages = timeline.querySelectorAll('.printing-stage');
          stages.forEach((stage, i) => {
            setTimeout(() => {
              stage.classList.add('stage-revealed');
            }, 80 + i * 140);
            animateElement(stage, 0, verticalDistance, 60 + i * 110);
            const marker = stage.querySelector('.printing-stage-marker');
            if (marker) animateElement(marker, 0, 8, 40 + i * 110, 0.92, 1);
          });
        }
        return;
      }

      // Solutions Photo & Applications List
      if (el.classList.contains('solutions-layout')) {
        const photo = el.querySelector('.solutions-photo');
        if (photo) animateElement(photo, 0, verticalDistance, 0);
        const listItems = el.querySelectorAll('.solutions-list li');
        listItems.forEach((li, i) => {
          animateElement(li, 0, verticalDistance, 80 + i * 45);
        });
        return;
      }

      // General Containers & Section Headers
      [...el.children].forEach((child, i) => {
        animateElement(child, 0, verticalDistance, i * 55);
      });
    });
  }, { threshold: [0, 0.08], rootMargin: '36px 0px' });

  // Observe key elements
  const observeTargets = root.querySelectorAll(
    '.studio-editorial-heading, .studio-overlap-card, ' +
    '.print-showcase-heading, .printing-process, .printing-timeline, .printing-process-note, ' +
    '.solutions-heading, .solutions-layout, .print-faq header, [data-print-motion]'
  );
  observeTargets.forEach(el => observer.observe(el));

  reduced.addEventListener('change', () => {
    if (reduced.matches) activeAnimations.forEach(a => a.cancel());
  });
})();

// 2. Meet Printex — Sticky Scroll Storytelling (Image Transition Controller)
(() => {
  const track = document.querySelector('.studio-sticky-track');
  if (!track || !('IntersectionObserver' in window)) return;

  const slide1 = track.querySelector('.studio-cinematic-slide.slide-1');
  const slide2 = track.querySelector('.studio-cinematic-slide.slide-2');
  const step2 = track.querySelector('.studio-story-step.step-transition');

  if (!slide1 || !slide2 || !step2) return;

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        slide1.classList.remove('active');
        slide2.classList.add('active');
      } else {
        if (entry.boundingClientRect.top > 0) {
          slide2.classList.remove('active');
          slide1.classList.add('active');
        }
      }
    });
  }, {
    threshold: 0.2,
    rootMargin: '-10% 0px -20% 0px'
  });

  observer.observe(step2);
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

// 4.5 Printing Solutions — Interactive Category & Visual Synergy
(() => {
  const solutions = document.querySelector('#homepage-information .print-solutions');
  if (!solutions) return;

  const photo = solutions.querySelector('.solutions-photo');
  const items = solutions.querySelectorAll('.solutions-list li');
  const captionText = photo ? photo.querySelector('.caption-text') : null;
  const captionTag = photo ? photo.querySelector('.caption-tag') : null;
  if (!photo || !items.length || !captionText) return;

  const defaultText = captionText.textContent;
  const defaultTag = captionTag ? captionTag.textContent : '';

  items.forEach(item => {
    const title = item.querySelector('h3')?.textContent.trim() || '';
    const desc = item.querySelector('p')?.textContent.trim() || '';

    item.addEventListener('mouseenter', () => {
      photo.classList.add('app-focused');
      if (captionTag) captionTag.textContent = 'APPLICATION FOCUS';
      captionText.textContent = `${title} — ${desc}`;
    });

    item.addEventListener('mouseleave', () => {
      photo.classList.remove('app-focused');
      if (captionTag) captionTag.textContent = defaultTag;
      captionText.textContent = defaultText;
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
