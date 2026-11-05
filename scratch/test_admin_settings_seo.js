const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  console.log('[1/3] Logging in as administrator...');
  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  console.log('[2/3] Visiting admin/settings.php...');
  const res = await page.goto(BASE_URL + '/admin/settings.php');
  console.log('Status:', res.status());

  const content = await page.content();
  const errors = content.includes('Fatal error') || content.includes('Warning:') || content.includes('Notice:');
  console.log('Any PHP errors/warnings:', errors);

  const hasSeoCard = content.includes('Automated SEO &amp; AI Discovery Engine') || content.includes('Automated SEO & AI Discovery Engine');
  console.log('SEO Card Present:', hasSeoCard);

  console.log('[3/3] Clicking "Re-Sync All 4 Discovery Files Now" button...');
  await page.click('button[value="sync_seo"]');
  await page.waitForLoadState('networkidle');

  const afterContent = await page.content();
  const successFlash = afterContent.includes('synchronized successfully');
  console.log('Success flash displayed:', successFlash);

  await browser.close();
  console.log('Finished Playwright test.');
})().catch(err => {
  console.error(err);
  process.exit(1);
});

