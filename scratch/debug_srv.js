const { chromium } = require('./node_modules/playwright-core');
(async () => {
  const browser = await chromium.launch({ executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe' });
  const page = await browser.newPage();
  await page.goto('http://127.0.0.1:8080/admin/login.php');
  await page.fill('input[name=email]', 'admin@orderprintex.com');
  await page.fill('input[name=password]', 'Admin@Printex2026!');
  await page.click('button[type=submit]');
  await page.waitForLoadState('networkidle');
  await page.goto('http://127.0.0.1:8080/admin/services.php?action=create');
  console.log('URL:', page.url());
  const h2 = await page.textContent('.admin-card-title');
  console.log('Card title:', h2);
  const titles = await page.$$eval('.admin-card-title', els => els.map(e => e.textContent));
  console.log('All card titles:', titles);
  await browser.close();
})();

