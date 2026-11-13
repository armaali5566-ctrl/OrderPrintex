const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ viewport: { width: 1440, height: 950 } });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  const showcaseTop = await page.evaluate(() => {
    return document.querySelector('.print-showcase').getBoundingClientRect().top + window.scrollY;
  });

  for (let offset = -200; offset <= 2400; offset += 200) {
    await page.evaluate(y => window.scrollTo(0, y), showcaseTop + offset);
    await page.waitForTimeout(100);

    const info = await page.evaluate(() => {
      const sticky = document.querySelector('.services-sticky-visual-column');
      const stickyRect = sticky.getBoundingClientRect();
      const activeSlide = document.querySelector('.services-deck-slide.active')?.getAttribute('data-service-index');
      const activeStep = document.querySelector('.print-story-step.active-service')?.getAttribute('data-service-step');
      const stepRects = Array.from(document.querySelectorAll('.print-story-step')).map(s => {
        const r = s.getBoundingClientRect();
        return { top: Math.round(r.top), bottom: Math.round(r.bottom) };
      });
      return { stickyTop: Math.round(stickyRect.top), activeSlide, activeStep, stepRects };
    });

    console.log(`offset ${offset}: stickyTop=${info.stickyTop}, activeSlide=${info.activeSlide}, activeStep=${info.activeStep}`);
  }

  await browser.close();
})();

