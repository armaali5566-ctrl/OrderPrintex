const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  
  const faq = page.locator('.print-faq');
  await faq.scrollIntoViewIfNeeded();
  await page.waitForTimeout(600); // Allow entry animations to settle
  await faq.screenshot({ path: 'scratch/faq_current_1440.png' });
  console.log('Saved scratch/faq_current_1440.png');

  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
  const faqMobile = page.locator('.print-faq');
  await faqMobile.scrollIntoViewIfNeeded();
  await page.waitForTimeout(600);
  await faqMobile.screenshot({ path: 'scratch/faq_current_390.png' });
  console.log('Saved scratch/faq_current_390.png');

  await browser.close();
})();
