const { chromium } = require('./node_modules/playwright-core');
const assert = require('assert');

(async () => {
  console.log('--- Testing Sticky Scroll Storytelling & Cinematic UI ---');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });

  const page = await browser.newPage({ deviceScaleFactor: 1 });
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', msg => { if (msg.type() === 'error') errors.push(msg.text()); });

  // 1. Desktop Sticky Verification at 1440px
  console.log('--- 1. Desktop 1440px Sticky Storytelling Tests ---');
  await page.setViewportSize({ width: 1440, height: 950 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // Verify sticky positions
  const stickyProps = await page.evaluate(() => {
    const studioSticky = document.querySelector('.studio-sticky-stage');
    const servicesSticky = document.querySelector('.services-sticky-visual-column');
    return {
      studioStickyPos: getComputedStyle(studioSticky).position,
      servicesStickyPos: getComputedStyle(servicesSticky).position
    };
  });

  assert.strictEqual(stickyProps.studioStickyPos, 'sticky', 'Studio stage must have position: sticky on desktop');
  assert.strictEqual(stickyProps.servicesStickyPos, 'sticky', 'Services visual column must have position: sticky on desktop');
  console.log('✓ Sticky positions verified on desktop.');

  // Test Meet Printex image transition:
  console.log('--- 2. Testing Meet Printex Image Transition on Scroll ---');
  await page.locator('.studio-editorial-heading').scrollIntoViewIfNeeded();
  await page.waitForTimeout(300);

  const initialSlideActive = await page.evaluate(() => {
    const s1 = document.querySelector('.studio-cinematic-slide.slide-1');
    const s2 = document.querySelector('.studio-cinematic-slide.slide-2');
    return {
      s1: s1.classList.contains('active'),
      s2: s2.classList.contains('active')
    };
  });
  assert(initialSlideActive.s1, 'Slide 1 should be active initially');
  assert(!initialSlideActive.s2, 'Slide 2 should not be active initially');
  console.log('✓ Slide 1 active initially.');

  // Scroll to step-transition
  await page.locator('.studio-story-step.step-transition').scrollIntoViewIfNeeded();
  await page.waitForTimeout(500);

  const transitionedSlideActive = await page.evaluate(() => {
    const s1 = document.querySelector('.studio-cinematic-slide.slide-1');
    const s2 = document.querySelector('.studio-cinematic-slide.slide-2');
    return {
      s1: s1.classList.contains('active'),
      s2: s2.classList.contains('active')
    };
  });
  assert(transitionedSlideActive.s2, 'Slide 2 should become active on scrolling to step 2');
  assert(!transitionedSlideActive.s1, 'Slide 1 should become inactive on scrolling to step 2');
  console.log('✓ Slide 2 becomes active smoothly as user scrolls into step 2.');

  // Test Services Sticky Deck image switching:
  console.log('--- 3. Testing Services Sticky Deck Dynamic Image Switching ---');
  const serviceSteps = page.locator('.print-story-step');
  const count = await serviceSteps.count();
  assert.strictEqual(count, 4, 'Should have 4 service steps');

  for (let i = 0; i < count; i++) {
    await serviceSteps.nth(i).scrollIntoViewIfNeeded();
    await page.waitForTimeout(350);

    const activeState = await page.evaluate((idx) => {
      const activeSlide = document.querySelector(`.services-deck-slide[data-service-index="${idx}"]`);
      const activeStep = document.querySelector(`.print-story-step[data-service-step="${idx}"]`);
      return {
        slideActive: activeSlide.classList.contains('active'),
        stepActive: activeStep.classList.contains('active-service')
      };
    }, i);

    assert(activeState.slideActive, `Service deck slide ${i} should be active when scrolled into view`);
    assert(activeState.stepActive, `Service step ${i} should have active-service class`);
    console.log(`✓ Service ${i + 1} (${['DTF', 'UV DTF', 'T-Shirt', 'Corporate'][i]}) activated correctly with corresponding image and gold highlight.`);
  }

  // 4. Mobile Layout Verification at 390px
  console.log('--- 4. Mobile 390px Stacked Layout Verification ---');
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  const mobileProps = await page.evaluate(() => {
    const servicesDeckDisplay = getComputedStyle(document.querySelector('.services-sticky-visual-column')).display;
    const mobileVisualDisplay = getComputedStyle(document.querySelector('.print-mobile-visual')).display;
    const overflow = document.documentElement.scrollWidth > window.innerWidth;
    return {
      servicesDeckDisplay,
      mobileVisualDisplay,
      overflow
    };
  });

  assert.strictEqual(mobileProps.servicesDeckDisplay, 'none', 'Sticky deck column should be hidden on mobile');
  assert.strictEqual(mobileProps.mobileVisualDisplay, 'block', 'Inline mobile visuals should be visible on mobile');
  assert(!mobileProps.overflow, 'No horizontal overflow on mobile');
  console.log('✓ Mobile stacked layout confirmed: sticky column hidden, inline visuals displayed, zero overflow.');

  // 5. Console Errors
  assert.strictEqual(errors.length, 0, `Page errors detected: ${JSON.stringify(errors)}`);
  console.log('✓ 0 JavaScript console errors detected.');

  await browser.close();
  console.log('====================================================');
  console.log('ALL STICKY SCROLL & CINEMATIC UI TESTS PASSED 100%!');
  console.log('====================================================');
})();

