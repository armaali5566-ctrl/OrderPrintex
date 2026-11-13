const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  
  const solutions = page.locator('.print-solutions');
  await solutions.scrollIntoViewIfNeeded();
  await page.waitForTimeout(600);
  await solutions.screenshot({ path: 'scratch/solutions_current_1440.png' });
  console.log('Saved scratch/solutions_current_1440.png');

  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  const solutionsMobile = page.locator('.print-solutions');
  await solutionsMobile.scrollIntoViewIfNeeded();
  await page.waitForTimeout(600);
  await solutionsMobile.screenshot({ path: 'scratch/solutions_current_390.png' });
  console.log('Saved scratch/solutions_current_390.png');

  await browser.close();
})();

