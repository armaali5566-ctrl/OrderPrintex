const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  await page.screenshot({ path: 'scratch/navbar_current_1440.png' });

  // Mobile
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  await page.screenshot({ path: 'scratch/navbar_current_390.png' });

  await browser.close();
  console.log('Saved scratch/navbar_current_1440.png and 390.png');
})();

