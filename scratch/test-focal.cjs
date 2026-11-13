const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ viewport: { width: 1440, height: 950 } });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  await page.evaluate(() => {
    const style = document.createElement('style');
    style.id = 'sticky-fix';
    style.innerHTML = 'html, body { overflow-x: clip !important; overflow-y: visible !important; }';
    document.head.appendChild(style);
  });

  const absTop = await page.evaluate(() => {
    return document.querySelector('.services-sticky-showcase').getBoundingClientRect().top + window.scrollY;
  });

  for (let s = -300; s <= 2200; s += 200) {
    await page.evaluate(y => window.scrollTo(0, y), absTop + s);
    await page.waitForTimeout(50);

    const detected = await page.evaluate(() => {
      const sticky = document.querySelector('.services-sticky-visual-column');
      const stickyRect = sticky.getBoundingClientRect();
      const focalY = stickyRect.top + stickyRect.height * 0.5;

      const steps = Array.from(document.querySelectorAll('.print-story-step'));
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
      return {
        bestIndex,
        stepName: ['01 DTF', '02 UV DTF', '03 T-Shirt', '04 Corporate'][bestIndex],
        stickyTop: Math.round(stickyRect.top)
      };
    });

    console.log('Offset ' + s + ': ' + detected.stepName + ' (stickyTop: ' + detected.stickyTop + 'px)');
  }

  await browser.close();
})();

