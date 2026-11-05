const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();

  const failedRequests = [];
  page.on('response', resp => {
    if (resp.status() >= 400) {
      failedRequests.push({ url: resp.url(), status: resp.status() });
    }
  });

  console.log('Navigating to ' + BASE_URL + '/gallery/...');
  await page.goto(BASE_URL + '/gallery/', { waitUntil: 'networkidle' });

  console.log('Failed requests on initial load:', failedRequests);

  // Check spotlight image src
  const spotlightSrc = await page.locator('#spotlightImage').getAttribute('src');
  console.log('Initial Spotlight image src:', spotlightSrc);

  // Click second thumbnail
  console.log('Clicking second thumbnail...');
  const secondThumb = page.locator('.spotlight-thumb-btn').nth(1);
  await secondThumb.click();
  await page.waitForTimeout(300);

  const updatedSpotlightSrc = await page.locator('#spotlightImage').getAttribute('src');
  console.log('Updated Spotlight image src:', updatedSpotlightSrc);

  // Open modal
  console.log('Opening lightbox modal...');
  await page.locator('#spotlightMediaBox').click();
  await page.waitForTimeout(300);

  const modalImgSrc = await page.locator('#modalImageSrc').getAttribute('src');
  console.log('Modal image src:', modalImgSrc);

  console.log('Failed requests after interaction:', failedRequests);

  // Check layout overflow
  const bodyWidth = await page.evaluate(() => document.body.scrollWidth);
  const viewportWidth = await page.evaluate(() => window.innerWidth);
  console.log(`Viewport width: ${viewportWidth}px, Body scroll width: ${bodyWidth}px, Overflow: ${bodyWidth > viewportWidth}`);

  await browser.close();
})().catch(err => {
  console.error(err);
  process.exit(1);
});

