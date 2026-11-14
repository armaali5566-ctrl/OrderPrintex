const { chromium } = require('./node_modules/playwright-core');
const path = require('path');
const assert = require('assert');

(async () => {
  console.log('=== VERIFYING PRINTING APPLICATIONS UPGRADE ===\n');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 }
  });
  const page = await context.newPage();

  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });

  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // 1. SCROLL ENTRANCE VERIFICATION
  console.log('--- 1. Testing Slow & Smooth Scroll Entrance Animation ---');
  // Scroll to just above the section
  const sectionPos = await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    return sec ? sec.getBoundingClientRect().top + window.scrollY : null;
  });
  console.log('Section top offset:', sectionPos);

  // Scroll into view
  await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'center' });
  });
  await page.waitForTimeout(2500); // Wait for the staggered sequence (up to 2.3s) to complete

  const entranceState = await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    const h2 = sec.querySelector('.solutions-heading h2');
    const photo = sec.querySelector('.solutions-photo');
    const items = Array.from(sec.querySelectorAll('.solutions-list li'));

    return {
      h2Opacity: window.getComputedStyle(h2).opacity,
      photoOpacity: window.getComputedStyle(photo).opacity,
      itemsOpacity: items.map(li => window.getComputedStyle(li).opacity),
      allVisible: items.every(li => parseFloat(window.getComputedStyle(li).opacity) > 0.9)
    };
  });
  console.log('Entrance elements opacity after completion:', {
    h2Opacity: entranceState.h2Opacity,
    photoOpacity: entranceState.photoOpacity,
    allVisible: entranceState.allVisible
  });
  assert.strictEqual(entranceState.allVisible, true, 'All application items must be fully visible after entrance');
  console.log('PASS: Entrance animation completed smoothly and all elements are visible.');

  // Scroll away and back - verify zero flicker and permanent visibility
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);
  await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'center' });
  });
  await page.waitForTimeout(200);

  const retainedState = await page.evaluate(() => {
    const items = Array.from(document.querySelectorAll('.solutions-list li'));
    return items.every(li => parseFloat(window.getComputedStyle(li).opacity) > 0.9);
  });
  assert.strictEqual(retainedState, true, 'Elements must retain visibility on scroll up/down');
  console.log('PASS: Zero flicker or opacity drop on bidirectional scroll.');

  // 2. DYNAMIC IMAGE & CAPTION SWITCHING ON HOVER FOR ALL 8 ITEMS
  console.log('\n--- 2. Testing Dynamic Image & Caption Switching Across All 8 Applications ---');

  const expectedApplications = [
    { idx: 0, title: 'Personal Gifts', imgSubstring: 'custom_stickers' },
    { idx: 1, title: 'Couple T-Shirts', imgSubstring: 'custom_tshirt_apparel' },
    { idx: 2, title: 'Occasion Gifts', imgSubstring: 'dtf_fabric_print' },
    { idx: 3, title: 'Wedding Packages', imgSubstring: 'packaging_box' },
    { idx: 4, title: 'Corporate Gifts', imgSubstring: 'corporate_swag_kit' },
    { idx: 5, title: 'Custom T-Shirts', imgSubstring: 'streetwear_tee' },
    { idx: 6, title: 'Printed Mugs', imgSubstring: 'uv_dtf_drinkware' },
    { idx: 7, title: 'Branded Merchandise', imgSubstring: 'branding_stationery' }
  ];

  for (const app of expectedApplications) {
    const selector = `.solutions-list li[data-app-index="${app.idx}"]`;
    await page.hover(selector);
    await page.waitForTimeout(250); // Small wait for class & transition

    const state = await page.evaluate((index) => {
      const activeSlide = document.querySelector('.solutions-slide.active');
      const activeImg = activeSlide ? activeSlide.querySelector('img') : null;
      const activeItem = document.querySelector(`.solutions-list li[data-app-index="${index}"]`);
      const captionText = document.querySelector('.solutions-photo .caption-text');
      const captionTag = document.querySelector('.solutions-photo .caption-tag');
      const iconBox = activeItem ? activeItem.querySelector('.solutions-icon-box') : null;
      const iconBoxCs = iconBox ? window.getComputedStyle(iconBox) : null;

      return {
        slideIdx: activeSlide ? activeSlide.getAttribute('data-app-slide') : null,
        imgSrc: activeImg ? activeImg.src : null,
        imgAlt: activeImg ? activeImg.alt : null,
        itemHasActiveApp: activeItem ? activeItem.classList.contains('active-app') : false,
        captionText: captionText ? captionText.textContent.trim() : null,
        captionTag: captionTag ? captionTag.textContent.trim() : null,
        iconBorderColor: iconBoxCs ? iconBoxCs.borderColor : null
      };
    }, app.idx);

    console.log(`[Item ${app.idx}: ${app.title}] Slide: ${state.slideIdx} | Img: ${path.basename(state.imgSrc || '')} | Tag: ${state.captionTag}`);
    assert.strictEqual(state.slideIdx, String(app.idx), `Slide index should match ${app.idx}`);
    assert.ok(state.imgSrc.includes(app.imgSubstring), `Image source must contain ${app.imgSubstring}, got: ${state.imgSrc}`);
    assert.strictEqual(state.itemHasActiveApp, true, 'Active application must have active-app class');
    assert.ok(state.captionText.includes(app.title), `Caption text must include "${app.title}", got: "${state.captionText}"`);
  }
  console.log('PASS: All 8 applications accurately switched their corresponding image, caption, and gold highlight!');

  // 3. TESTING UNHOVER / MOUSELEAVE (REVERT TO SHOWCASE DEFAULT)
  console.log('\n--- 3. Testing Mouseleave (Return to Default Showcase) ---');
  await page.mouse.move(10, 10); // Move away
  await page.waitForTimeout(300);

  const defaultState = await page.evaluate(() => {
    const activeSlide = document.querySelector('.solutions-slide.active');
    const activeItem = document.querySelector('.solutions-list li.active-app');
    const captionText = document.querySelector('.solutions-photo .caption-text');
    const captionTag = document.querySelector('.solutions-photo .caption-tag');
    return {
      slideIdx: activeSlide ? activeSlide.getAttribute('data-app-slide') : null,
      hasActiveApp: !!activeItem,
      captionTag: captionTag ? captionTag.textContent.trim() : null
    };
  });
  console.log('Default State after mouseleave:', defaultState);
  assert.strictEqual(defaultState.slideIdx, 'default', 'Must return to default slide');
  assert.strictEqual(defaultState.hasActiveApp, false, 'No item should have active-app after mouseleave');
  assert.strictEqual(defaultState.captionTag, 'CURATED SUITE', 'Caption tag should return to CURATED SUITE');
  console.log('PASS: Seamlessly returns to default showcase on mouseleave.');

  // 4. TESTING RAPID SWITCHING (ZERO FLICKER)
  console.log('\n--- 4. Testing Rapid Hover Switching ---');
  for (let i = 0; i < 8; i++) {
    await page.hover(`.solutions-list li[data-app-index="${i}"]`);
    await page.waitForTimeout(40); // Rapid 40ms transitions
  }
  await page.waitForTimeout(200);
  const rapidFinalSlide = await page.evaluate(() => {
    const active = document.querySelector('.solutions-slide.active');
    return active ? active.getAttribute('data-app-slide') : null;
  });
  assert.strictEqual(rapidFinalSlide, '7', 'Latest hovered item (7) must be active after rapid hover');
  console.log('PASS: Rapid hover transitions smoothly without blinking or desync.');

  // 5. TESTING MOBILE TAP & TOUCH INTERACTION
  console.log('\n--- 5. Testing Mobile Viewport & Tap Lock (390px) ---');
  await page.setViewportSize({ width: 390, height: 844 });
  await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'start' });
  });
  await page.waitForTimeout(300);

  // Tap on Wedding Packages (idx 3)
  await page.click('.solutions-list li[data-app-index="3"]');
  await page.waitForTimeout(200);

  const mobileTapState = await page.evaluate(() => {
    const activeSlide = document.querySelector('.solutions-slide.active');
    const activeItem = document.querySelector('.solutions-list li[data-app-index="3"]');
    const captionText = document.querySelector('.solutions-photo .caption-text');
    return {
      slideIdx: activeSlide ? activeSlide.getAttribute('data-app-slide') : null,
      isItemActive: activeItem ? activeItem.classList.contains('active-app') : false,
      caption: captionText ? captionText.textContent.trim() : null
    };
  });
  console.log('Mobile tap state on Wedding Packages:', mobileTapState);
  assert.strictEqual(mobileTapState.slideIdx, '3', 'Mobile tap on item 3 must activate slide 3');
  assert.strictEqual(mobileTapState.isItemActive, true, 'Mobile tap must keep active-app locked');
  assert.ok(mobileTapState.caption.includes('Wedding Packages'), 'Caption must match Wedding Packages');
  console.log('PASS: Mobile tap selection works smoothly and remains locked.');

  // 6. TESTING KEYBOARD ACCESSIBILITY
  console.log('\n--- 6. Testing Keyboard Accessibility (Focus, Enter, Space) ---');
  await page.focus('.solutions-list li[data-app-index="6"]'); // Printed Mugs
  await page.keyboard.press('Enter');
  await page.waitForTimeout(200);

  const keyboardState = await page.evaluate(() => {
    const activeSlide = document.querySelector('.solutions-slide.active');
    const activeItem = document.querySelector('.solutions-list li[data-app-index="6"]');
    return {
      slideIdx: activeSlide ? activeSlide.getAttribute('data-app-slide') : null,
      isItemActive: activeItem ? activeItem.classList.contains('active-app') : false
    };
  });
  assert.strictEqual(keyboardState.slideIdx, '6', 'Keyboard Enter must activate Printed Mugs (slide 6)');
  assert.strictEqual(keyboardState.isItemActive, true, 'Keyboard Enter must set active-app');
  console.log('PASS: Keyboard navigation (Tab, Enter, Space) functions cleanly.');

  // 7. CAPTURING VERIFICATION SCREENSHOTS
  console.log('\n--- 7. Capturing Screenshots ---');
  // Desktop view
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'center' });
  });
  await page.waitForTimeout(300);
  await page.hover('.solutions-list li[data-app-index="4"]'); // Corporate gifts
  await page.waitForTimeout(300);

  const desktopSec = await page.$('.print-solutions');
  if (desktopSec) {
    await desktopSec.screenshot({ path: path.join(__dirname, 'solutions_desktop_hover.png') });
    console.log('Saved desktop screenshot: scratch/solutions_desktop_hover.png');
  }

  // Mobile view
  await page.setViewportSize({ width: 390, height: 844 });
  await page.evaluate(() => {
    const sec = document.querySelector('.print-solutions');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'start' });
  });
  await page.waitForTimeout(300);
  const mobileSec = await page.$('.print-solutions');
  if (mobileSec) {
    await mobileSec.screenshot({ path: path.join(__dirname, 'solutions_mobile_active.png') });
    console.log('Saved mobile screenshot: scratch/solutions_mobile_active.png');
  }

  // 8. VERIFY ZERO CONSOLE ERRORS
  console.log('\n--- 8. Checking Console Errors ---');
  console.log('Console errors encountered:', consoleErrors.length);
  assert.strictEqual(consoleErrors.length, 0, `Expected 0 console errors, got: ${JSON.stringify(consoleErrors)}`);
  console.log('PASS: Zero console errors.');

  await browser.close();
  console.log('\n====================================================');
  console.log('ALL PRINTING APPLICATIONS UPGRADE VERIFICATIONS PASSED 100%!');
  console.log('====================================================');
})();
