const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  page.on('console', msg => console.log('BROWSER CONSOLE:', msg.type(), msg.text()));
  page.on('pageerror', err => console.log('PAGE ERROR:', err.message));

  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  await page.goto(BASE_URL + '/admin/users.php');
  await page.waitForLoadState('networkidle');

  console.log('Attempting to click Edit button...');
  const editBtn = page.locator('button:has-text("Edit")').first();
  console.log('Edit btn onclick attribute:', await editBtn.getAttribute('onclick'));
  
  await editBtn.click();
  await page.waitForTimeout(500);

  const modalDisplay = await page.evaluate(() => {
    const m = document.getElementById('editPersonnelModal');
    return {
      classes: m.className,
      display: m.style.display,
      visibility: window.getComputedStyle(m).visibility
    };
  });
  console.log('Modal state after click:', modalDisplay);

  await browser.close();
})().catch(console.error);

