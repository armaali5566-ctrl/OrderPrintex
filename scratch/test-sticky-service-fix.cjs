const { chromium } = require('./node_modules/playwright-core');
const assert = require('assert');

(async () => {
  console.log('=== ORDERPRINTEX STICKY SERVICE IMAGE SCROLLING VERIFICATION ===');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });

  const page = await browser.newPage({ deviceScaleFactor: 1 });
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', msg => { if (msg.type() === 'error') errors.push(msg.text()); });

  // 1. Initial State Check (DTF must be paired with DTF image, NOT bottles)
  console.log('--- 1. Testing Initial Service Pairing ---');
  await page.setViewportSize({ width: 1440, height: 950 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  const initialCheck = await page.evaluate(() => {
    const activeSlide = document.querySelector('.services-deck-slide.active');
    const activeSlideIndex = activeSlide ? activeSlide.getAttribute('data-service-index') : null;
    const activeSlideImgSrc = activeSlide ? activeSlide.querySelector('img').src : null;
    const activeStep = document.querySelector('.print-story-step.active-service');
    const activeStepIndex = activeStep ? activeStep.getAttribute('data-service-step') : null;
    return { activeSlideIndex, activeSlideImgSrc, activeStepIndex };
  });

  assert.strictEqual(initialCheck.activeSlideIndex, '0', 'Initial active slide must be 0 (DTF Printing)');
  assert.strictEqual(initialCheck.activeStepIndex, '0', 'Initial active step must be 0 (DTF Printing)');
  assert(initialCheck.activeSlideImgSrc.includes('dtf_fabric_print'), 'Initial image must be DTF garment transfer, NOT drinkware bottles!');
  console.log('✓ DTF Printing is correctly paired with dtf_fabric_print image (NOT drinkware bottles).');

  // 2. Sticky Pinning Verification
  console.log('--- 2. Testing Left Image Sticky Pinning Throughout Service Scroll ---');
  const steps = page.locator('.print-story-step');
  const expectedMappings = [
    { index: '0', name: 'DTF Printing', imgKeyword: 'dtf_fabric_print' },
    { index: '1', name: 'UV DTF Printing', imgKeyword: 'uv_dtf_drinkware' },
    { index: '2', name: 'T-Shirt Printing', imgKeyword: 'streetwear_tee' },
    { index: '3', name: 'Corporate Printing', imgKeyword: 'corporate_swag_kit' }
  ];

  // Scroll DOWN through each service
  console.log('--- 3. Testing Scrolling DOWN (01 -> 02 -> 03 -> 04) ---');
  for (let i = 0; i < 4; i++) {
    await steps.nth(i).scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);

    const state = await page.evaluate(() => {
      const stickyCol = document.querySelector('.services-sticky-visual-column');
      const stickyTop = Math.round(stickyCol.getBoundingClientRect().top);
      const activeSlide = document.querySelector('.services-deck-slide.active');
      const activeStep = document.querySelector('.print-story-step.active-service');
      return {
        stickyTop,
        slideIdx: activeSlide ? activeSlide.getAttribute('data-service-index') : null,
        imgSrc: activeSlide ? activeSlide.querySelector('img').src : null,
        stepIdx: activeStep ? activeStep.getAttribute('data-service-step') : null
      };
    });

    assert.strictEqual(state.slideIdx, expectedMappings[i].index, `Slide index should be ${expectedMappings[i].index}`);
    assert.strictEqual(state.stepIdx, expectedMappings[i].index, `Step index should be ${expectedMappings[i].index}`);
    assert(state.imgSrc.includes(expectedMappings[i].imgKeyword), `Image must contain ${expectedMappings[i].imgKeyword}`);
    // Sticky must stay pinned near top of viewport (e.g. within 100-200px)
    assert(state.stickyTop >= 100 && state.stickyTop <= 200, `Left image must be pinned, got ${state.stickyTop}px`);
    console.log(`✓ Down-scroll: ${expectedMappings[i].name} active -> Image: ${expectedMappings[i].imgKeyword} (stickyTop: ${state.stickyTop}px)`);
  }

  // Scroll UP through each service
  console.log('--- 4. Testing Scrolling UP (04 -> 03 -> 02 -> 01) ---');
  for (let i = 3; i >= 0; i--) {
    await steps.nth(i).scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);

    const state = await page.evaluate(() => {
      const activeSlide = document.querySelector('.services-deck-slide.active');
      const activeStep = document.querySelector('.print-story-step.active-service');
      return {
        slideIdx: activeSlide ? activeSlide.getAttribute('data-service-index') : null,
        imgSrc: activeSlide ? activeSlide.querySelector('img').src : null,
        stepIdx: activeStep ? activeStep.getAttribute('data-service-step') : null
      };
    });

    assert.strictEqual(state.slideIdx, expectedMappings[i].index);
    assert.strictEqual(state.stepIdx, expectedMappings[i].index);
    assert(state.imgSrc.includes(expectedMappings[i].imgKeyword));
    console.log(`✓ Up-scroll: ${expectedMappings[i].name} restored -> Image: ${expectedMappings[i].imgKeyword}`);
  }

  // 5. Sticky Release Test after Corporate Printing
  console.log('--- 5. Testing Natural Sticky Release at Section End ---');
  await page.locator('.printing-process').scrollIntoViewIfNeeded();
  await page.waitForTimeout(300);

  const releaseState = await page.evaluate(() => {
    const stickyCol = document.querySelector('.services-sticky-visual-column');
    const showcase = document.querySelector('.services-sticky-showcase');
    return {
      stickyTop: Math.round(stickyCol.getBoundingClientRect().top),
      showcaseBottom: Math.round(showcase.getBoundingClientRect().bottom)
    };
  });
  // When scrolled past to Printing Process, the sticky element must unpin and move up
  assert(releaseState.stickyTop < 100, `Sticky element must release and scroll away, got ${releaseState.stickyTop}px`);
  console.log(`✓ Sticky element releases naturally as section ends (stickyTop: ${releaseState.stickyTop}px).`);

  // 6. Mobile Layout & Images Test at 390px
  console.log('--- 6. Testing Mobile Layout at 390px ---');
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  const mobileCheck = await page.evaluate(() => {
    const stickyCol = document.querySelector('.services-sticky-visual-column');
    const mobileVisuals = Array.from(document.querySelectorAll('.print-mobile-visual img')).map(img => img.src);
    const overflow = document.documentElement.scrollWidth > window.innerWidth;
    return {
      stickyColHidden: getComputedStyle(stickyCol).display === 'none',
      mobileVisualsCount: mobileVisuals.length,
      mobileVisuals,
      overflow
    };
  });

  assert(mobileCheck.stickyColHidden, 'Desktop sticky column must be hidden on mobile');
  assert.strictEqual(mobileCheck.mobileVisualsCount, 4, 'Mobile must render all 4 service visuals inline');
  assert(mobileCheck.mobileVisuals[0].includes('dtf_fabric_print'), 'Mobile Service 01 has DTF image');
  assert(mobileCheck.mobileVisuals[1].includes('uv_dtf_drinkware'), 'Mobile Service 02 has UV DTF image');
  assert(mobileCheck.mobileVisuals[2].includes('streetwear_tee'), 'Mobile Service 03 has T-Shirt image');
  assert(mobileCheck.mobileVisuals[3].includes('corporate_swag_kit'), 'Mobile Service 04 has Corporate image');
  assert(!mobileCheck.overflow, 'No horizontal overflow on mobile');
  console.log('✓ Mobile view: All 4 inline visuals rendered correctly, side-by-side sticky disabled, 0 overflow.');

  // 7. Console Errors
  assert.strictEqual(errors.length, 0, `Page errors: ${JSON.stringify(errors)}`);
  console.log('✓ 0 JavaScript console errors detected.');

  await browser.close();
  console.log('====================================================');
  console.log('ALL 11 STICKY SERVICE SHOWCASE REQUIREMENTS VERIFIED!');
  console.log('====================================================');
})();
