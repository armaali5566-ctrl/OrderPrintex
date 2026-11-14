const { chromium } = require('./node_modules/playwright-core');
const assert = require('assert');

(async () => {
  let browser;
  try {
    console.log('=== ORDERPRINTEX CORRECTION & ANIMATION VERIFICATION ===');
    browser = await chromium.launch({
      executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
      headless: true
    });

    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    page.on('console', msg => { if (msg.type() === 'error') errors.push(msg.text()); });

    console.log('Navigating to http://127.0.0.1:8080/ ...');
    await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

    // ----------------------------------------------------
    // 1. MEET PRINTEX: Typography, Masked Reveal & 3D Tilt
    // ----------------------------------------------------
    console.log('\n--- 1. Testing Meet Printex Typography & Intro Motion ---');
    const meetHeading = await page.$('.studio-editorial-heading');
    assert(meetHeading, 'Meet Printex heading exists');

    const titleText = await page.$eval('.studio-editorial h2', el => el.textContent.replace(/\s+/g, ' ').trim());
    console.log(`Heading text: "${titleText}"`);
    assert(titleText.includes('Meet Printex.'), 'Contains "Meet Printex."');
    assert(titleText.includes('Printing for your brand.'), 'Contains "Printing for your brand."');

    // Verify masked reveal elements
    const maskedTexts = await page.$$('.studio-masked-text');
    console.log(`Found ${maskedTexts.length} masked text elements (expected 2)`);
    assert.strictEqual(maskedTexts.length, 2, 'Exactly 2 masked lines in heading');

    // Wait for reveal transition to settle
    await page.waitForTimeout(600);
    const lineActive = await page.$eval('.studio-editorial-heading', el => el.classList.contains('line-active'));
    console.log(`Gold accent line revealed: ${lineActive} (expected true)`);
    assert(lineActive, 'Line reveal stroke is active');

    // Verify 3D Tilt interaction on Desktop
    console.log('Testing 3D image tilt on .studio-hero-figure...');
    const figure = await page.$('.studio-hero-figure');
    assert(figure, '.studio-hero-figure exists');

    const initialTransform = await figure.evaluate(el => el.style.transform);
    console.log(`Initial figure transform: "${initialTransform}"`);

    // Move mouse over the corner of the figure
    const figBox = await figure.boundingBox();
    await page.mouse.move(figBox.x + figBox.width * 0.85, figBox.y + figBox.height * 0.2);
    await page.waitForTimeout(200);

    const tiltedTransform = await figure.evaluate(el => el.style.transform);
    console.log(`Tilted figure transform on mousemove: "${tiltedTransform}"`);
    assert(tiltedTransform.includes('rotateX') && tiltedTransform.includes('rotateY'), 'Figure has 3D tilt applied on mousemove');

    // Move mouse away
    await page.mouse.move(10, 10);
    await page.waitForTimeout(200);
    const resetTransform = await figure.evaluate(el => el.style.transform);
    console.log(`Reset figure transform on mouseleave: "${resetTransform}"`);
    assert.strictEqual(resetTransform, '', 'Transform resets cleanly on mouseleave');

    // ----------------------------------------------------
    // 2. SCROLL ENTRANCE 1: Our Printing Process
    // ----------------------------------------------------
    console.log('\n--- 2. Testing Scroll Entrance on Our Printing Process ---');
    const processSec = await page.$('.printing-process');
    assert(processSec, '.printing-process exists');

    await processSec.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);

    const isProcessRevealed = await processSec.evaluate(el => el.classList.contains('scroll-revealed'));
    const isTimelineRevealed = await page.$eval('.printing-timeline', el => el.classList.contains('timeline-revealed'));
    console.log(`Printing Process scroll-revealed: ${isProcessRevealed} (expected true)`);
    console.log(`Timeline revealed: ${isTimelineRevealed} (expected true)`);
    assert(isProcessRevealed, 'Printing Process has .scroll-revealed');
    assert(isTimelineRevealed, 'Printing Timeline has .timeline-revealed');

    // ----------------------------------------------------
    // 3. SCROLL ENTRANCE 2: Expanding Hover Gallery
    // ----------------------------------------------------
    console.log('\n--- 3. Testing Scroll Entrance on Expanding Hover Gallery ---');
    const solutionsSec = await page.$('.print-solutions');
    assert(solutionsSec, '.print-solutions exists');

    await solutionsSec.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);

    const isSolutionsRevealed = await solutionsSec.evaluate(el => el.classList.contains('scroll-revealed'));
    console.log(`Print Solutions scroll-revealed: ${isSolutionsRevealed} (expected true)`);
    assert(isSolutionsRevealed, 'Print Solutions has .scroll-revealed');

    // Test that hover expansion is fully functional
    console.log('Testing hover expansion functionality after entrance...');
    const panel1 = await page.$('.expanding-gallery-row[data-gallery-row="1"] .gallery-panel:nth-child(2)');
    await panel1.hover();
    await page.waitForTimeout(500);
    const isPanel1Active = await panel1.evaluate(el => el.classList.contains('active'));
    console.log(`Panel 1 active after hover: ${isPanel1Active} (expected true)`);
    assert(isPanel1Active, 'Gallery panel hover expansion works smoothly');

    // ----------------------------------------------------
    // 4. SCROLL ENTRANCE 3: Before You Print — FAQ
    // ----------------------------------------------------
    console.log('\n--- 4. Testing Scroll Entrance on Before You Print (FAQ) ---');
    const faqSec = await page.$('.print-faq');
    assert(faqSec, '.print-faq exists');

    await faqSec.scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);

    const isFaqRevealed = await faqSec.evaluate(el => el.classList.contains('scroll-revealed'));
    console.log(`FAQ scroll-revealed: ${isFaqRevealed} (expected true)`);
    assert(isFaqRevealed, 'FAQ has .scroll-revealed');

    // Test that accordion opening works
    console.log('Testing accordion toggle functionality after entrance...');
    const faqTrigger1 = await page.$('#home-faq-question-1');
    await faqTrigger1.click();
    await page.waitForTimeout(300);
    const isFaq1Expanded = await faqTrigger1.getAttribute('aria-expanded');
    console.log(`FAQ Item 1 aria-expanded after click: ${isFaq1Expanded} (expected true)`);
    assert.strictEqual(isFaq1Expanded, 'true', 'FAQ item expands on click');

    // ----------------------------------------------------
    // 5. TEST RUN-ONCE & NO-BLINKING BEHAVIOR
    // ----------------------------------------------------
    console.log('\n--- 5. Testing Run-Once Permanence on Reverse Scrolling ---');
    // Scroll back to top
    await page.evaluate(() => window.scrollTo({ top: 0, behavior: 'instant' }));
    await page.waitForTimeout(300);

    // Scroll down past all sections
    await page.evaluate(() => window.scrollTo({ top: 2500, behavior: 'instant' }));
    await page.waitForTimeout(300);

    const pRevealedAfter = await processSec.evaluate(el => el.classList.contains('scroll-revealed'));
    const sRevealedAfter = await solutionsSec.evaluate(el => el.classList.contains('scroll-revealed'));
    const fRevealedAfter = await faqSec.evaluate(el => el.classList.contains('scroll-revealed'));

    console.log(`Process remains revealed: ${pRevealedAfter}`);
    console.log(`Solutions remains revealed: ${sRevealedAfter}`);
    console.log(`FAQ remains revealed: ${fRevealedAfter}`);
    assert(pRevealedAfter && sRevealedAfter && fRevealedAfter, 'All sections remain permanently revealed with zero resets');

    // ----------------------------------------------------
    // 6. MOBILE RESPONSIVENESS (390 x 844)
    // ----------------------------------------------------
    console.log('\n--- 6. Testing Mobile Responsiveness (390 x 844) ---');
    await page.setViewportSize({ width: 390, height: 844 });
    await page.waitForTimeout(300);

    const hasOverflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth);
    console.log(`Mobile horizontal overflow: ${hasOverflow} (expected false)`);
    assert(!hasOverflow, 'Mobile layout has zero horizontal overflow');

    // Check errors
    console.log(`\nJavaScript Console Errors: ${errors.length}`);
    if (errors.length > 0) {
      console.error('Errors encountered:', errors);
      throw new Error(`Found ${errors.length} console errors`);
    }

    console.log('\n======================================================');
    console.log('ALL CORRECTION & SCROLL ENTRANCE TESTS PASSED (100%)');
    console.log('======================================================');

  } catch (err) {
    console.error('TEST FAILED:', err);
    process.exit(1);
  } finally {
    if (browser) await browser.close();
  }
})();

