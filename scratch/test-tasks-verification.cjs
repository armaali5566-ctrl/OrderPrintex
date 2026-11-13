const { chromium } = require('./node_modules/playwright-core');
const path = require('path');

(async () => {
  console.log('=== VERIFYING TASKS 1, 2, AND 3 ===');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 }
  });
  const page = await context.newPage();

  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // 1. TASK 1: VERIFY DUPLICATE STRIP IS REMOVED
  console.log('\n--- 1. Testing Task 1 (Duplicate Strip Removal) ---');
  const actionBar = await page.$('.studio-action-bar');
  if (actionBar !== null) {
    console.error('FAIL: .studio-action-bar still exists in DOM!');
    process.exit(1);
  } else {
    console.log('PASS: .studio-action-bar is completely removed from DOM.');
  }

  // Check that original quote / products links exist elsewhere
  const bulkQuotes = await page.$$('a[href*="quote"], a[href*="upload"]');
  console.log(`Found ${bulkQuotes.length} quotation / upload links on page (intact).`);
  if (bulkQuotes.length === 0) {
    console.error('FAIL: Original bulk quote links missing from page!');
    process.exit(1);
  }

  // 2. TASK 2: VERIFY PUBLIC HEADER & NON-STICKY BEHAVIOR
  console.log('\n--- 2. Testing Task 2 (Header Redesign & Non-Sticky Verification) ---');
  const headerStyles = await page.evaluate(() => {
    const topBar = document.querySelector('.top-contact-bar');
    const header = document.querySelector('.site-header');
    const topBarCs = topBar ? window.getComputedStyle(topBar) : null;
    const headerCs = header ? window.getComputedStyle(header) : null;
    return {
      topBarPos: topBarCs ? topBarCs.position : null,
      topBarHeight: topBarCs ? topBarCs.height : null,
      headerPos: headerCs ? headerCs.position : null,
      headerHeight: headerCs ? headerCs.height : null,
      headerBg: headerCs ? headerCs.backgroundColor : null,
      separatorsCount: document.querySelectorAll('.top-contact-sep').length
    };
  });
  console.log('Header Initial State:', headerStyles);

  if (headerStyles.topBarPos !== 'relative' || headerStyles.headerPos !== 'relative') {
    console.error(`FAIL: Header is not position: relative! (topBar: ${headerStyles.topBarPos}, header: ${headerStyles.headerPos})`);
    process.exit(1);
  }
  console.log('PASS: Header and top contact bar have position: relative.');

  // Scroll down 600px and verify header scrolls off naturally (rect.bottom < 0)
  await page.evaluate(() => window.scrollTo(0, 600));
  await page.waitForTimeout(100);

  const scrollOffState = await page.evaluate(() => {
    const topBar = document.querySelector('.top-contact-bar');
    const header = document.querySelector('.site-header');
    const topBarRect = topBar.getBoundingClientRect();
    const headerRect = header.getBoundingClientRect();
    const topBarCs = window.getComputedStyle(topBar);
    const headerCs = window.getComputedStyle(header);
    return {
      topBarRectBottom: topBarRect.bottom,
      headerRectBottom: headerRect.bottom,
      topBarPos: topBarCs.position,
      headerPos: headerCs.position
    };
  });
  console.log('Header Scrolled State (at 600px):', scrollOffState);

  if (scrollOffState.headerRectBottom > 0) {
    console.error('FAIL: Header is still visible in viewport after scrolling 600px! It is acting sticky!');
    process.exit(1);
  } else {
    console.log('PASS: Header scrolled out of viewport naturally without sticking.');
  }

  // 3. TASK 3: VERIFY ZERO SCROLL FLICKER / RE-ARMING
  console.log('\n--- 3. Testing Task 3 (Scroll Flicker Fix & Element Retention) ---');
  // Scroll down to reveal tech rows
  await page.evaluate(() => {
    const el = document.querySelector('.tech-zigzag-row');
    if (el) el.scrollIntoView({ behavior: 'instant', block: 'center' });
  });
  await page.waitForTimeout(300);

  const techRowsVisible = await page.evaluate(() => {
    const rows = document.querySelectorAll('.tech-zigzag-row');
    return Array.from(rows).map(r => r.classList.contains('is-visible'));
  });
  console.log('Tech rows visibility after scroll into view:', techRowsVisible);

  // Scroll completely away (e.g. back to top 0)
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);

  // Verify that tech rows did NOT lose 'is-visible' (no flicker / vanish on re-entry!)
  const techRowsAfterScrollAway = await page.evaluate(() => {
    const rows = document.querySelectorAll('.tech-zigzag-row');
    return Array.from(rows).map(r => r.classList.contains('is-visible'));
  });
  console.log('Tech rows visibility after scrolling away to top:', techRowsAfterScrollAway);

  const keptVisible = techRowsAfterScrollAway.every(v => v === true);
  if (!keptVisible) {
    console.error('FAIL: Tech rows lost is-visible when scrolled away! Scroll flicker is NOT fixed!');
    process.exit(1);
  } else {
    console.log('PASS: Elements retain is-visible and do NOT flicker or vanish on bidirectional scroll.');
  }

  // 4. VERIFY STICKY SERVICE IMAGE TRANSITION BEHAVIOR
  console.log('\n--- 4. Testing Sticky Service Showcase Slide Transitions ---');
  await page.evaluate(() => {
    const sec = document.querySelector('.services-sticky-showcase');
    if (sec) sec.scrollIntoView({ behavior: 'instant', block: 'start' });
  });
  await page.waitForTimeout(300);

  const serviceCheck = await page.evaluate(() => {
    const showcase = document.querySelector('.services-sticky-showcase');
    const stickyCol = showcase ? showcase.querySelector('.services-sticky-visual-column') : null;
    const cs = stickyCol ? window.getComputedStyle(stickyCol) : null;
    const slides = showcase ? showcase.querySelectorAll('.services-deck-slide') : [];
    const activeSlide = showcase ? showcase.querySelector('.services-deck-slide.active') : null;
    return {
      position: cs ? cs.position : null,
      slideCount: slides.length,
      hasActiveSlide: !!activeSlide,
      activeTitle: activeSlide ? activeSlide.querySelector('img')?.getAttribute('alt') : null
    };
  });
  console.log('Sticky services check:', serviceCheck);

  if (serviceCheck.position !== 'sticky') {
    console.error(`FAIL: Sticky column position is ${serviceCheck.position}, expected sticky!`);
    process.exit(1);
  }
  console.log('PASS: Printing services sticky frame is sticky.');

  // 5. SCREENSHOT VERIFICATION
  console.log('\n--- 5. Capturing Verification Screenshots ---');
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);
  await page.screenshot({ path: path.join(__dirname, 'header-desktop.png'), clip: { x: 0, y: 0, width: 1440, height: 450 } });
  console.log('Desktop header screenshot saved: scratch/header-desktop.png');

  // Mobile viewport screenshot
  await page.setViewportSize({ width: 390, height: 844 });
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);
  await page.screenshot({ path: path.join(__dirname, 'header-mobile.png'), clip: { x: 0, y: 0, width: 390, height: 350 } });
  console.log('Mobile header screenshot saved: scratch/header-mobile.png');

  await browser.close();
  console.log('\nALL TESTS PASSED SUCCESSFULLY!');
})();
