const { chromium } = require('./node_modules/playwright-core');
const assert = require('assert');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // 1. Check positions at top
  const headerPos = await page.evaluate(() => {
    const h = document.querySelector('.site-header');
    const t = document.querySelector('.top-contact-bar');
    return {
      headerPosition: getComputedStyle(h).position,
      topPosition: getComputedStyle(t).position,
      headerTop: h.getBoundingClientRect().top,
      scrollY: window.scrollY
    };
  });
  assert.strictEqual(headerPos.headerPosition, 'relative', 'Header should be position: relative');
  assert.strictEqual(headerPos.topPosition, 'relative', 'Top bar should be position: relative');
  console.log('✓ Initial positions: header is relative, top bar is relative.');

  // 2. Scroll down 500px
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(200);

  const scrolledPos = await page.evaluate(() => {
    const h = document.querySelector('.site-header');
    const rect = h.getBoundingClientRect();
    return {
      top: rect.top,
      bottom: rect.bottom,
      scrollY: window.scrollY,
      hasScrolledClass: h.classList.contains('scrolled')
    };
  });
  assert(scrolledPos.bottom < 0, `Header should have scrolled completely off screen (bottom is ${scrolledPos.bottom})`);
  assert(!scrolledPos.hasScrolledClass, 'Header should not have scrolled class');
  console.log('✓ When scrolled down 500px, header is completely off-screen at top:', scrolledPos.top);

  // 3. Scroll back to top
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);

  const restoredPos = await page.evaluate(() => {
    const h = document.querySelector('.site-header');
    return h.getBoundingClientRect().top;
  });
  assert(restoredPos >= 0 && restoredPos <= 50, `Header should be back at top, got ${restoredPos}`);
  console.log('✓ When scrolled back to top, header is restored at top:', restoredPos);

  // 4. Test dropdown menu
  const dropdownToggle = page.locator('#servicesDropdownToggle');
  await dropdownToggle.hover();
  await page.waitForTimeout(300);
  const isDropdownVisible = await page.locator('#servicesDropdownMenu').evaluate(el => {
    const style = getComputedStyle(el);
    return style.visibility === 'visible' && style.opacity === '1';
  });
  assert(isDropdownVisible, 'Services dropdown menu should be visible on hover');
  console.log('✓ Printing Services dropdown is visible and accessible on hover.');

  await browser.close();
  console.log('====================================================');
  console.log('NAVBAR NON-STICKY VERIFICATION PASSED 100%!');
  console.log('====================================================');
})();

