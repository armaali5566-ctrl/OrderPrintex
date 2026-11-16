const { chromium } = require('./node_modules/playwright-core');

(async () => {
  try {
    const browser = await chromium.launch({
      channel: 'chrome',
      headless: true
    });
    console.log('Chrome launched successfully via playwright-core!');
    const page = await browser.newPage();
    await page.goto('http://127.0.0.1:8080/blog/dtf-printing-technology-custom-apparel');
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.screenshot({ path: 'scratch/verified_blog_1440.png' });
    
    await page.setViewportSize({ width: 375, height: 812 });
    await page.screenshot({ path: 'scratch/verified_blog_375.png' });

    // Test Product Details
    await page.goto('http://127.0.0.1:8080/products/custom-dtf-gang-sheet');
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.screenshot({ path: 'scratch/verified_product_1440.png' });

    await browser.close();
    console.log('Screenshots saved successfully!');
  } catch (err) {
    console.error('Playwright error:', err.message);
  }
})();
