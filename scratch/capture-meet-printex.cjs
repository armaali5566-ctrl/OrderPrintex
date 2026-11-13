const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 950 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  await page.waitForTimeout(600);

  // Take screenshot of the top of the page (Navbar + Meet Printex)
  await page.screenshot({ path: 'scratch/meet_printex_redesign_1440.png' });
  console.log('Saved scratch/meet_printex_redesign_1440.png');

  // Scroll down slightly to capture the composition & action bar
  await page.evaluate(() => window.scrollBy(0, 350));
  await page.waitForTimeout(400);
  await page.screenshot({ path: 'scratch/meet_printex_composition_1440.png' });
  console.log('Saved scratch/meet_printex_composition_1440.png');

  // Mobile
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  await page.waitForTimeout(600);
  await page.screenshot({ path: 'scratch/meet_printex_redesign_390.png' });
  console.log('Saved scratch/meet_printex_redesign_390.png');

  await page.evaluate(() => window.scrollBy(0, 450));
  await page.waitForTimeout(400);
  await page.screenshot({ path: 'scratch/meet_printex_mobile_scroll_390.png' });
  console.log('Saved scratch/meet_printex_mobile_scroll_390.png');

  await browser.close();
})();

