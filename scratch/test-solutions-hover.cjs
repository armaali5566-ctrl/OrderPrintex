const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // Scroll so the entire section is comfortably in view below the navbar
  await page.evaluate(() => {
    const el = document.querySelector('.print-solutions');
    if (el) {
      const top = el.getBoundingClientRect().top + window.scrollY - 100;
      window.scrollTo({ top, behavior: 'instant' });
    }
  });
  await page.waitForTimeout(600);

  // Normal screenshot
  await page.screenshot({ path: 'scratch/solutions_full_view_1440.png' });
  console.log('Saved scratch/solutions_full_view_1440.png');

  // Now hover over the 4th item (Wedding Packages)
  const item4 = page.locator('.solutions-list li').nth(3);
  await item4.hover();
  await page.waitForTimeout(300);

  // Check state
  const isFocused = await page.locator('.solutions-photo').evaluate(el => el.classList.contains('app-focused'));
  const captionText = await page.locator('.solutions-photo .caption-text').textContent();
  console.log('Hover test: app-focused =', isFocused, '| caption =', captionText);

  await page.screenshot({ path: 'scratch/solutions_hover_view_1440.png' });
  console.log('Saved scratch/solutions_hover_view_1440.png');

  // Check mobile full view
  await page.setViewportSize({ width: 390, height: 844 });
  await page.evaluate(() => {
    const el = document.querySelector('.print-solutions');
    if (el) {
      const top = el.getBoundingClientRect().top + window.scrollY - 80;
      window.scrollTo({ top, behavior: 'instant' });
    }
  });
  await page.waitForTimeout(600);
  await page.screenshot({ path: 'scratch/solutions_full_view_390.png' });
  console.log('Saved scratch/solutions_full_view_390.png');

  await browser.close();
})();

