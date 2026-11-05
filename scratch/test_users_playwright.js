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
  console.log('After login URL:', page.url());

  const response = await page.goto(BASE_URL + '/admin/users.php');
  console.log('users.php HTTP status:', response.status());
  console.log('Final URL:', page.url());
  
  const content = await page.content();
  const errors = content.includes('Fatal error') || content.includes('Warning:') || content.includes('Notice:') || content.includes('Undefined variable');
  console.log('Any PHP errors/warnings:', errors);

  const tabText = await page.locator('.admin-tab-btn.active').innerText();
  console.log('Tab text:', tabText);

  const cardTitle = await page.locator('.admin-card-title').allInnerTexts();
  console.log('Card titles:', cardTitle);

  const tableRows = await page.locator('.admin-table tbody tr').count();
  console.log('User rows in table:', tableRows);

  await browser.close();
})().catch(err => {
  console.error(err);
  process.exit(1);
});
