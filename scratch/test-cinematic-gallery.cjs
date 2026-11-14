const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    headless: true,
    executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe'
  });

  const page = await browser.newPage({
    viewport: { width: 1440, height: 900 }
  });

  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });
  page.on('pageerror', err => {
    consoleErrors.push(err.message);
  });

  console.log('Navigating to http://127.0.0.1:8080/ ...');
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // 1. Check Meet Printex Sandwich
  console.log('\n--- 1. Testing Meet Printex 3D Text-Image Sandwich ---');
  const sandwichStage = await page.$('.studio-sandwich-stage');
  if (!sandwichStage) throw new Error('Missing .studio-sandwich-stage');

  const backHeading = await page.$('.studio-heading-back');
  const middleComposition = await page.$('.studio-composition');
  const frontOutline = await page.$('.studio-heading-front');

  if (!backHeading || !middleComposition || !frontOutline) {
    throw new Error('Missing one of the sandwich layers (back, middle, front)');
  }

  const backZ = await backHeading.evaluate(el => window.getComputedStyle(el).zIndex);
  const midZ = await middleComposition.evaluate(el => window.getComputedStyle(el).zIndex);
  const frontZ = await frontOutline.evaluate(el => window.getComputedStyle(el).zIndex);
  const frontPointer = await frontOutline.evaluate(el => window.getComputedStyle(el).pointerEvents);

  console.log(`Z-indices: Back=${backZ}, Middle=${midZ}, Front=${frontZ}, FrontPointerEvents=${frontPointer}`);

  const ariaHidden = await frontOutline.getAttribute('aria-hidden');
  console.log(`Front layer aria-hidden: ${ariaHidden}`);

  const mainTitleText = await page.$eval('.studio-title-main', el => el.textContent.trim());
  const outlineTitleText = await page.$eval('.studio-outline-text', el => el.textContent.trim());
  console.log(`Main Title Text: "${mainTitleText}", Outline Title Text: "${outlineTitleText}"`);

  // Check 3D tilt on hero figure
  const heroFig = await page.$('.studio-hero-figure');
  await heroFig.scrollIntoViewIfNeeded();
  await page.waitForTimeout(100);
  const figBox = await heroFig.boundingBox();
  await page.mouse.move(figBox.x + figBox.width * 0.8, figBox.y + figBox.height * 0.8);
  await page.waitForTimeout(150);

  const figTransform = await heroFig.evaluate(el => el.style.transform);
  console.log(`Hero figure mousemove transform: ${figTransform}`);

  await page.mouse.move(10, 10);
  await page.waitForTimeout(200);
  const figTransformReset = await heroFig.evaluate(el => el.style.transform);
  console.log(`Hero figure mouseleave transform: "${figTransformReset}"`);

  // 2. Check Gallery Numbers Removal
  console.log('\n--- 2. Testing Gallery Numbers Removal ---');
  const numSpines = await page.$$('.gallery-panel-num');
  const numBadges = await page.$$('.gallery-panel-num-badge');
  console.log(`Found .gallery-panel-num count: ${numSpines.length}`);
  console.log(`Found .gallery-panel-num-badge count: ${numBadges.length}`);

  const galleryText = await page.$eval('.expanding-gallery-container', el => el.innerText);
  const has01to08 = /\b0[1-8]\b/.test(galleryText);
  console.log(`Does gallery text contain standalone numbers 01-08? ${has01to08}`);

  // 3. Testing Single Global Active Panel
  console.log('\n--- 3. Testing Single Global Active Panel ---');
  const galleryContainer = await page.$('.expanding-gallery-container');
  await galleryContainer.scrollIntoViewIfNeeded();
  await page.waitForTimeout(200);

  const panels = await page.$$('.gallery-panel');
  console.log(`Total panels count: ${panels.length}`);

  const initialActive = [];
  for (let i = 0; i < panels.length; i++) {
    const isActive = await panels[i].evaluate(el => el.classList.contains('active'));
    if (isActive) initialActive.push(i);
  }
  console.log(`Initial active panels indices: ${JSON.stringify(initialActive)}`);
  if (initialActive.length !== 1 || initialActive[0] !== 0) {
    throw new Error(`Expected exactly panel 0 active initially, found: ${JSON.stringify(initialActive)}`);
  }

  // Hover Panel 5 (Row 2 Custom T-Shirts)
  console.log('Hovering Panel 5 (Row 2, Custom T-Shirts)...');
  await panels[5].scrollIntoViewIfNeeded();
  const p5Box = await panels[5].boundingBox();
  await page.mouse.move(p5Box.x + p5Box.width / 2, p5Box.y + p5Box.height / 2);
  await page.waitForTimeout(200);

  const afterHoverActive = [];
  for (let i = 0; i < panels.length; i++) {
    const isActive = await panels[i].evaluate(el => el.classList.contains('active'));
    if (isActive) afterHoverActive.push(i);
  }
  console.log(`Active panels after hovering Panel 5: ${JSON.stringify(afterHoverActive)}`);
  if (afterHoverActive.length !== 1 || afterHoverActive[0] !== 5) {
    throw new Error(`Expected exactly panel 5 active, found: ${JSON.stringify(afterHoverActive)}`);
  }

  // Check flex values on Row 1 and Row 2
  const p0Flex = await panels[0].evaluate(el => window.getComputedStyle(el).flexGrow);
  const p5Flex = await panels[5].evaluate(el => window.getComputedStyle(el).flexGrow);
  const p6Flex = await panels[6].evaluate(el => window.getComputedStyle(el).flexGrow);
  console.log(`FlexGrow: Panel 0 (Row 1 inactive)=${p0Flex}, Panel 5 (Row 2 active)=${p5Flex}, Panel 6 (Row 2 inactive sibling)=${p6Flex}`);

  // Test mouseleave restore
  console.log('Moving mouse outside gallery to trigger leave-restore...');
  await page.mouse.move(50, 50);
  await page.waitForTimeout(400); // 220ms debounce + transition

  const restoredActive = [];
  for (let i = 0; i < panels.length; i++) {
    const isActive = await panels[i].evaluate(el => el.classList.contains('active'));
    if (isActive) restoredActive.push(i);
  }
  console.log(`Active panels after leaving gallery: ${JSON.stringify(restoredActive)}`);
  if (restoredActive.length !== 1 || restoredActive[0] !== 0) {
    throw new Error(`Expected revert to panel 0, found: ${JSON.stringify(restoredActive)}`);
  }

  // 4. Testing Explore Application Links
  console.log('\n--- 4. Testing Explore Application Links ---');
  const links = await page.$$('.gallery-panel-link');
  console.log(`Found ${links.length} genuine destination links in gallery.`);
  for (let i = 0; i < links.length; i++) {
    const href = await links[i].getAttribute('href');
    const text = await links[i].innerText();
    console.log(`Link ${i}: "${text.trim().replace(/\s+/g, ' ')}" -> ${href}`);
    if (href === '#' || href === '') {
      throw new Error(`Invalid dead link found: ${text}`);
    }
  }

  // 5. Responsive / Mobile Check
  console.log('\n--- 5. Testing Mobile Viewport (390px) ---');
  await page.setViewportSize({ width: 390, height: 844 });
  await page.waitForTimeout(300);

  const overflow = await page.evaluate(() => {
    return document.documentElement.scrollWidth > document.documentElement.clientWidth;
  });
  console.log(`Mobile horizontal overflow: ${overflow}`);

  // Check mobile tap activation
  const p2 = panels[2];
  await p2.click();
  await page.waitForTimeout(100);
  const p2ActiveMobile = await p2.evaluate(el => el.classList.contains('active'));
  console.log(`Panel 2 active after mobile tap: ${p2ActiveMobile}`);

  // Screenshot test
  await page.screenshot({ path: 'scratch/gallery-mobile-verified.png', fullPage: false });

  // Switch back to desktop and screenshot
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.waitForTimeout(300);
  const meetPrintexSec = await page.$('#meet-printex-section');
  if (meetPrintexSec) {
    await meetPrintexSec.screenshot({ path: 'scratch/meet-printex-sandwich-desktop.png' });
  }
  const solutionsSec = await page.$('#print-solutions-section');
  if (solutionsSec) {
    await solutionsSec.screenshot({ path: 'scratch/expanding-gallery-single-active-desktop.png' });
  }

  console.log('\nConsole errors count:', consoleErrors.length);
  if (consoleErrors.length > 0) {
    console.error('Console errors:', consoleErrors);
  }

  await browser.close();
  console.log('\nAll tests completed successfully!');
})();
