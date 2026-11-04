const { chromium } = require('./node_modules/playwright-core');

const pages = [
  { name: 'services_overview', url: 'http://127.0.0.1:8080/services/' },
  { name: 'dtf_printing', url: 'http://127.0.0.1:8080/services/dtf-printing.php' },
  { name: 'uv_dtf_printing', url: 'http://127.0.0.1:8080/services/uv-dtf-printing.php' },
  { name: 'tshirt_printing', url: 'http://127.0.0.1:8080/services/t-shirt-printing.php' },
  { name: 'corporate_printing', url: 'http://127.0.0.1:8080/services/corporate-printing.php' }
];

const viewports = [
  { name: 'mobile_320', width: 320, height: 568 },
  { name: 'mobile_375', width: 375, height: 667 },
  { name: 'mobile_390', width: 390, height: 844 },
  { name: 'mobile_430', width: 430, height: 932 },
  { name: 'tablet_768', width: 768, height: 1024 },
  { name: 'tablet_1024', width: 1024, height: 1366 },
  { name: 'desktop_1440', width: 1440, height: 900 }
];

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
    headless: true
  });

  for (const p of pages) {
    console.log(`\n=== Checking ${p.name} ===`);
    const page = await browser.newPage();
    for (const vp of viewports) {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      await page.goto(p.url, { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(250);

      const metrics = await page.evaluate(() => {
        const docWidth = document.documentElement.clientWidth;
        const scrollWidth = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth);
        const overflow = scrollWidth > docWidth + 1;

        // Check service cta buttons
        const ctaBtns = Array.from(document.querySelectorAll('.service-cta-group .btn')).map(b => {
          const rect = b.getBoundingClientRect();
          return {
            text: b.textContent.trim().replace(/\s+/g, ' '),
            width: Math.round(rect.width),
            height: Math.round(rect.height),
            ratio: (rect.width / docWidth).toFixed(2)
          };
        });

        // Check overview grid
        const grid = document.querySelector('.service-overview-grid');
        let gridCols = null;
        let imgHeight = null;
        if (grid) {
          const style = window.getComputedStyle(grid);
          gridCols = style.gridTemplateColumns;
          const img = grid.querySelector('img');
          if (img) imgHeight = Math.round(img.getBoundingClientRect().height);
        }

        return {
          docWidth,
          overflow,
          ctaBtns,
          gridCols,
          imgHeight
        };
      });

      console.log(`  ${vp.name} (${vp.width}px): Overflow=${metrics.overflow} | GridCols=${metrics.gridCols} | ImgHeight=${metrics.imgHeight}`);
      for (const b of metrics.ctaBtns) {
        console.log(`    Btn: "${b.text}" -> W=${b.width}px, H=${b.height}px (Ratio=${b.ratio})`);
      }

      // Screenshot 375px mobile and 1440px desktop
      if (vp.width === 375 || vp.width === 1440) {
        const screenshotPath = `scratch/${p.name}_${vp.width}px.png`;
        await page.screenshot({ path: screenshotPath, fullPage: false });
      }
    }
    await page.close();
  }

  await browser.close();
  console.log('\nAll service pages checked successfully!');
})();

