/**
 * ORDERPRINTEX — Scroll Reveals & Micro-Interactions (Vanilla JS)
 * IntersectionObserver with Fallback
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // Check for reduced motion preference
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
    return;
  }

  // 1. IntersectionObserver for Scroll Reveals
  const revealElements = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealElements.length > 0) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          // Keep observing or unobserve depending on UX
          obs.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      threshold: 0.15,
      rootMargin: '0px 0px -40px 0px'
    });

    revealElements.forEach(el => observer.observe(el));
  } else {
    // Fallback for older browsers
    revealElements.forEach(el => el.classList.add('active'));
  }

  // 2. Animated Numerical Counters
  const countElements = document.querySelectorAll('[data-counter]');
  if ('IntersectionObserver' in window && countElements.length > 0) {
    const counterObserver = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const target = entry.target;
          const endValue = parseFloat(target.getAttribute('data-counter')) || 0;
          const duration = parseInt(target.getAttribute('data-duration'), 10) || 1600;
          const suffix = target.getAttribute('data-suffix') || '';
          const prefix = target.getAttribute('data-prefix') || '';
          
          let startValue = 0;
          const startTime = performance.now();

          const animateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic
            const easeProgress = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(easeProgress * endValue);
            
            target.textContent = prefix + current.toLocaleString() + suffix;

            if (progress < 1) {
              requestAnimationFrame(animateCounter);
            } else {
              target.textContent = prefix + endValue.toLocaleString() + suffix;
            }
          };

          requestAnimationFrame(animateCounter);
          obs.unobserve(target);
        }
      });
    }, { threshold: 0.5 });

    countElements.forEach(el => counterObserver.observe(el));
  }
});
