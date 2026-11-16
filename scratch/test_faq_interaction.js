const { chromium } = require('./node_modules/playwright-core');

(async () => {
  try {
    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    const page = await browser.newPage();
    await page.goto('http://127.0.0.1:8080/blog/dtf-printing-technology-custom-apparel');
    
    // Find FAQ items
    const faqItems = await page.$$('.faq-item');
    console.log(`Found ${faqItems.length} FAQ items.`);

    // Check item 0 is active initially
    const isFirstActive = await faqItems[0].evaluate(el => el.classList.contains('active'));
    console.log(`Item 0 active initially: ${isFirstActive}`);

    // Click item 1
    const q1 = await faqItems[1].$('.faq-question');
    await q1.click();
    await page.waitForTimeout(400);

    const isFirstActiveAfter = await faqItems[0].evaluate(el => el.classList.contains('active'));
    const isSecondActiveAfter = await faqItems[1].evaluate(el => el.classList.contains('active'));
    console.log(`Item 0 active after click: ${isFirstActiveAfter}`);
    console.log(`Item 1 active after click: ${isSecondActiveAfter}`);

    // Capture screenshot of expanded FAQ
    const faqSection = await page.$('.blog-faqs-wrapper');
    if (faqSection) {
      await faqSection.screenshot({ path: 'scratch/verified_faq_accordion.png' });
      console.log('FAQ accordion screenshot saved!');
    }

    await browser.close();
    console.log('FAQ accordion test completed successfully!');
  } catch (err) {
    console.error('FAQ test error:', err.message);
  }
})();
