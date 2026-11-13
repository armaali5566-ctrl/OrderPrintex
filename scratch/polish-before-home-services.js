/* New informational sections only. Content remains visible without JavaScript. */
(() => {
  const root = document.querySelector('#homepage-information');
  if (!root || !('IntersectionObserver' in window) || !Element.prototype.animate) return;
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const active = new Set();
  const seen = new WeakSet();
  const running = new WeakMap();
  const animate = (el, x, y, delay = 0, scale = 1) => {
    running.get(el)?.cancel();
    const a = el.animate([
      { opacity: 0, transform: `translate(${x}px, ${y}px) scale(${scale})` },
      { opacity: 1, transform: 'translate(0, 0) scale(1)' }
    ], { duration: 800, delay, easing: 'cubic-bezier(.22, 1, .36, 1)', fill: 'backwards' });
    running.set(el, a);
    active.add(a);
    a.finished.then(() => active.delete(a), () => active.delete(a));
  };
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const el = entry.target;
      if (!entry.isIntersecting) {
        if (entry.boundingClientRect.bottom <= 0 || entry.boundingClientRect.top >= innerHeight) seen.delete(el);
        return;
      }
      if (seen.has(el) || reduced.matches) return;
      seen.add(el);
      const distance = innerWidth <= 800 ? 16 : 32;
      if (el.classList.contains('print-story')) {
        const feature = el.classList.contains('print-story-feature') && innerWidth > 800;
        animate(el.querySelector('.print-story-visual'), feature ? -distance : 0, feature ? 0 : distance, 0, .985);
        [...el.querySelector('.print-story-copy').children].forEach((child, i) => animate(child, feature ? distance : 0, feature ? 0 : distance, 50 + i * 55));
      } else {
        [...el.children].forEach((child, i) => animate(child, 0, distance, i * 55));
      }
    });
  }, { threshold: [0, .12] });
  root.querySelectorAll('.print-story, [data-print-motion], .print-showcase-heading').forEach(el => observer.observe(el));
  reduced.addEventListener('change', () => { if (reduced.matches) active.forEach(a => a.cancel()); });
})();

// Shared FAQ handlers in main.js retain click and native button keyboard behavior.
// Start expanded for no-JavaScript access, then collapse the secondary questions.
document.addEventListener('DOMContentLoaded', () => {
  const faq = document.querySelector('#homepage-information .print-faq');
  if (!faq) return;
  faq.querySelectorAll('.faq-item').forEach((item, i) => {
    item.classList.toggle('active', i === 0);
    item.querySelector('.faq-trigger').setAttribute('aria-expanded', i === 0 ? 'true' : 'false');
  });
  faq.classList.add('print-faq-ready');
});
