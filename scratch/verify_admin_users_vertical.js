const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  // 1. Login
  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  // 2. Open admin/users.php on Desktop (1440px)
  await page.setViewportSize({ width: 1440, height: 900 });
  const res = await page.goto(BASE_URL + '/admin/users.php');
  await page.waitForLoadState('networkidle');
  console.log('users.php HTTP status:', res.status());

  const content = await page.content();
  const hasPhpErrors = /Fatal error|Warning:|Notice:|Undefined variable/i.test(content);
  console.log('PHP errors check:', hasPhpErrors ? 'FOUND ERRORS' : 'CLEAN');

  // Check card titles and positions
  const cardTitles = await page.locator('.admin-card-title').allInnerTexts();
  console.log('Card titles found:', cardTitles);

  const cards = await page.$$('.admin-card');
  console.log('Number of admin cards:', cards.length);

  const box1 = await cards[0].boundingBox();
  const box2 = await cards[1].boundingBox();
  console.log('Card 1 (Form): y =', Math.round(box1.y), 'height =', Math.round(box1.height), 'width =', Math.round(box1.width));
  console.log('Card 2 (Table): y =', Math.round(box2.y), 'height =', Math.round(box2.height), 'width =', Math.round(box2.width));

  const isVertical = box2.y >= box1.y + box1.height;
  console.log('Is Table positioned vertically below the Form?:', isVertical);

  // Check full-width usage (both cards should be similarly wide, taking full available container width)
  console.log('Are both cards full width (ratio ~ 1.0)?:', (box1.width / box2.width).toFixed(2));

  // Check responsiveness on multiple viewports
  const viewports = [1440, 1024, 768, 412, 390, 375, 360, 320];
  for (const w of viewports) {
    await page.setViewportSize({ width: w, height: 800 });
    await page.waitForTimeout(100);
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth);
    console.log(`Viewport ${w}px overflow:`, overflow ? 'YES (OVERFLOW)' : 'NO (PASSED)');
  }

  // 3. Test Add New Staff form functionality
  const testEmail = `teststaff_${Date.now()}@orderprintex.com`;
  console.log('\nTesting Staff creation form submission with email:', testEmail);
  await page.fill('input[name="name"]', 'Automated Test Staff');
  await page.fill('input[name="email"]', testEmail);
  await page.fill('input[name="phone"]', '+44 7700 900999');
  await page.selectOption('select[name="role"]', 'staff');
  await page.fill('input[name="password"]', 'TestPass123!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  const afterContent = await page.content();
  const createdSuccess = afterContent.includes('Personnel account created successfully');
  console.log('Staff creation success alert present:', createdSuccess);
  console.log('Test staff in table:', afterContent.includes(testEmail));

  await browser.close();
  console.log('\nVerification completed successfully!');
})().catch(err => {
  console.error(err);
  process.exit(1);
});

