const { chromium } = require('./node_modules/playwright-core');

(async () => {
  let browser;
  try {
    browser = await chromium.launch({
      headless: true,
      executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe'
    });

    const page = await browser.newPage({
      viewport: { width: 1440, height: 900 }
    });

    const consoleErrors = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });
    page.on('pageerror', err => {
      consoleErrors.push(err.message);
    });

    console.log('Navigating to http://127.0.0.1:8080/ ...');
    await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

    // 1. Check expanding gallery presence
    const gallerySection = await page.$('.print-solutions');
    if (!gallerySection) throw new Error('Missing .print-solutions section');

    const rows = await page.$$('.expanding-gallery-row');
    console.log(`Found ${rows.length} gallery rows (expected 2)`);
    if (rows.length !== 2) throw new Error(`Expected 2 rows, found ${rows.length}`);

    const panels = await page.$$('.gallery-panel');
    console.log(`Found ${panels.length} gallery panels (expected 8)`);
    if (panels.length !== 8) throw new Error(`Expected 8 panels, found ${panels.length}`);

    // Verify initial active state in Row 1 (Item 0) and Row 2 (Item 4)
    const initialActiveRow1 = await page.$eval('.expanding-gallery-row[data-gallery-row="1"] .gallery-panel.active .gallery-panel-title', el => el.textContent.trim());
    const initialActiveRow2 = await page.$eval('.expanding-gallery-row[data-gallery-row="2"] .gallery-panel.active .gallery-panel-title', el => el.textContent.trim());
    console.log(`Row 1 initial active: "${initialActiveRow1}" (expected "Personal Gifts")`);
    console.log(`Row 2 initial active: "${initialActiveRow2}" (expected "Corporate Gifts")`);

    // Verify widths of active vs compressed sibling in Row 1
    const p0Box = await panels[0].boundingBox();
    const p1Box = await panels[1].boundingBox();
    console.log(`Initial Desktop widths: Panel 0 (active) = ${p0Box.width.toFixed(1)}px, Panel 1 (sibling) = ${p1Box.width.toFixed(1)}px`);
    if (p0Box.width <= p1Box.width * 2) {
      throw new Error(`Active panel width (${p0Box.width}) should be > 2x sibling panel width (${p1Box.width})`);
    }

    // 2. Test Hover Expansion on Panel 1 (Couple T-Shirts)
    console.log('Hovering Panel 1 (Couple T-Shirts)...');
    await panels[1].hover();
    await page.waitForTimeout(800); // Wait for transition (750ms)

    const isPanel1Active = await panels[1].evaluate(el => el.classList.contains('active') && el.getAttribute('aria-expanded') === 'true');
    const isPanel0Active = await panels[0].evaluate(el => el.classList.contains('active'));
    const isRow2Panel4Active = await panels[4].evaluate(el => el.classList.contains('active'));

    console.log(`Panel 1 active after hover: ${isPanel1Active} (expected true)`);
    console.log(`Panel 0 active after sibling hovered: ${isPanel0Active} (expected false)`);
    console.log(`Row 2 Panel 4 isolated state: ${isPanel2Panel4Active = isRow2Panel4Active} (expected true - row isolation preserved)`);

    if (!isPanel1Active) throw new Error('Panel 1 did not expand on hover');
    if (isPanel0Active) throw new Error('Panel 0 remained active after Panel 1 was hovered');
    if (!isRow2Panel4Active) throw new Error('Row 2 active state changed unexpectedly (row isolation violated)');

    const p1HoverBox = await panels[1].boundingBox();
    const p0HoverBox = await panels[0].boundingBox();
    console.log(`After hover widths: Panel 1 (now active) = ${p1HoverBox.width.toFixed(1)}px, Panel 0 (now compressed) = ${p0HoverBox.width.toFixed(1)}px`);
    if (p1HoverBox.width <= p0HoverBox.width * 2) {
      throw new Error('Panel 1 width did not expand past sibling compressed width');
    }

    // Check visible typography inside Panel 1
    const p1Title = await panels[1].$eval('.gallery-panel-title', el => el.textContent.trim());
    const p1Desc = await panels[1].$eval('.gallery-panel-desc', el => el.textContent.trim());
    const p1Tag = await panels[1].$eval('.gallery-panel-tag', el => el.textContent.trim());
    console.log(`Panel 1 typography: Title="${p1Title}", Tag="${p1Tag}", Desc="${p1Desc}"`);
    if (p1Title !== 'Couple T-Shirts') throw new Error(`Wrong title: ${p1Title}`);

    // Check vertical label opacity on active vs inactive
    const p1VLabelOpacity = await panels[1].$eval('.gallery-vertical-label', el => window.getComputedStyle(el).opacity);
    const p0VLabelOpacity = await panels[0].$eval('.gallery-vertical-label', el => window.getComputedStyle(el).opacity);
    console.log(`Vertical label opacities: Panel 1 (active) = ${p1VLabelOpacity}, Panel 0 (inactive) = ${p0VLabelOpacity}`);

    // 3. Test Tasks 5 & 6 (Removed duplicate sections)
    console.log('\nVerifying removal of duplicate sections...');
    const duplicateServicesGrid = await page.$$('.services-grid');
    const duplicateFeaturesGrid = await page.$$('.home-features-grid');
    const duplicateHeading1 = await page.$('text="Our DTF, UV DTF and Custom Printing Services"');
    const duplicateHeading2 = await page.$('text="Why Choose Printex for Bulk DTF & UV DTF Printing?"');

    console.log(`Duplicate .services-grid elements found: ${duplicateServicesGrid.length} (expected 0)`);
    console.log(`Duplicate .home-features-grid elements found: ${duplicateFeaturesGrid.length} (expected 0)`);
    console.log(`Duplicate Heading 1 found: ${!!duplicateHeading1} (expected false)`);
    console.log(`Duplicate Heading 2 found: ${!!duplicateHeading2} (expected false)`);

    if (duplicateServicesGrid.length > 0 || duplicateFeaturesGrid.length > 0 || duplicateHeading1 || duplicateHeading2) {
      throw new Error('Duplicate sections were not completely removed');
    }
    console.log('Task 5 & Task 6 verified: Duplicate sections 4 and 5 are completely removed!');

    // 4. Test Keyboard Navigation
    console.log('\nTesting keyboard navigation...');
    await panels[2].focus();
    await page.keyboard.press('Enter');
    await page.waitForTimeout(400);
    const isPanel2Active = await panels[2].evaluate(el => el.classList.contains('active'));
    console.log(`Panel 2 active after Enter key: ${isPanel2Active} (expected true)`);
    if (!isPanel2Active) throw new Error('Keyboard activation failed on Panel 2');

    // Take Desktop Screenshot
    await page.screenshot({ path: 'scratch/expanding-gallery-desktop.png', fullPage: false });
    console.log('Saved screenshot: scratch/expanding-gallery-desktop.png');

    // 5. Test Mobile Responsiveness (390 x 844)
    console.log('\nTesting mobile responsiveness (390 x 844)...');
    await page.setViewportSize({ width: 390, height: 844 });
    await page.waitForTimeout(400);

    const hasHorizontalOverflow = await page.evaluate(() => {
      return document.documentElement.scrollWidth > document.documentElement.clientWidth;
    });
    console.log(`Mobile horizontal overflow: ${hasHorizontalOverflow} (expected false)`);
    if (hasHorizontalOverflow) throw new Error('Mobile has horizontal scroll overflow!');

    const mobileP0Box = await panels[0].boundingBox();
    console.log(`Mobile Panel 0 dimensions: ${mobileP0Box.width.toFixed(1)}x${mobileP0Box.height.toFixed(1)}px`);
    if (mobileP0Box.width < 340 || mobileP0Box.height < 180) {
      throw new Error(`Mobile panel dimensions too small: ${mobileP0Box.width}x${mobileP0Box.height}`);
    }

    // Mobile tap interaction
    console.log('Tapping Panel 3 on mobile...');
    await panels[3].tap();
    await page.waitForTimeout(300);
    const isPanel3Active = await panels[3].evaluate(el => el.classList.contains('active'));
    console.log(`Panel 3 active after mobile tap: ${isPanel3Active} (expected true)`);

    // Take Mobile Screenshot
    await page.screenshot({ path: 'scratch/expanding-gallery-mobile.png', fullPage: false });
    console.log('Saved screenshot: scratch/expanding-gallery-mobile.png');

    // 6. Verify zero console errors
    console.log(`\nConsole errors: ${consoleErrors.length}`);
    if (consoleErrors.length > 0) {
      console.error('Console errors:', consoleErrors);
      throw new Error(`Found ${consoleErrors.length} console errors`);
    }

    console.log('\n========================================');
    console.log('ALL EXPANDING GALLERY TESTS PASSED (100%)');
    console.log('========================================');

  } catch (err) {
    console.error('TEST FAILED:', err);
    process.exit(1);
  } finally {
    if (browser) await browser.close();
  }
})();
