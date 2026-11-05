const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

const VIEWPORTS = [320, 360, 375, 390, 412, 768, 1024, 1440];

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });

  for (const w of VIEWPORTS) {
    const page = await browser.newPage({ viewport: { width: w, height: 800 } });
    await page.goto(BASE_URL + '/gallery/', { waitUntil: 'networkidle' });
    
    const bodyWidth = await page.evaluate(() => document.body.scrollWidth);
    const docWidth = await page.evaluate(() => document.documentElement.scrollWidth);
    const winWidth = await page.evaluate(() => window.innerWidth);
    const hasOverflow = bodyWidth > winWidth || docWidth > winWidth;

    const spotlightBox = await page.locator('#spotlightCard').boundingBox();
    const specsGrid = await page.locator('.spotlight-specs-grid').boundingBox();

    console.log(`Width ${w}px -> Page overflow: ${hasOverflow} (Body: ${bodyWidth}px, Win: ${winWidth}px)`);
    if (spotlightBox) {
      console.log(`   SpotlightCard width: ${Math.round(spotlightBox.width)}px, x: ${Math.round(spotlightBox.x)}`);
    }
    if (specsGrid) {
      console.log(`   SpecsGrid width: ${Math.round(specsGrid.width)}px`);
    }

    await page.close();
  }

  await browser.close();
})().catch(err => {
  console.error(err);
  process.exit(1);
});

