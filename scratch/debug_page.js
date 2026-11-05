const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  await page.goto(BASE_URL + '/admin/users.php');
  const csrfToken = await page.evaluate(() => document.querySelector('input[name="csrf_token"]').value);

  // Submit self-edit form directly via page.evaluate
  const result = await page.evaluate(async (csrf) => {
    const fd = new FormData();
    fd.append('action', 'edit_personnel');
    fd.append('csrf_token', csrf);
    fd.append('user_id', 1);
    fd.append('name', 'Hacked Admin');
    fd.append('email', 'admin@orderprintex.com');

    const res = await fetch('users.php', { method: 'POST', body: fd });
    return { status: res.status, url: res.url, text: await res.text() };
  }, csrfToken);

  console.log('Result URL:', result.url);
  console.log('Result text has security block:', result.text.includes('Security Policy: Super Administrators cannot modify their own roles'));

  await browser.close();
})();
