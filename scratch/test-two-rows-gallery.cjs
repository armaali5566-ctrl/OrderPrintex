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

  console.log('\n--- 1. Testing Two Default Expanded Cards on Load ---');
  const gallery = await page.$('.expanding-gallery-container');
  await gallery.scrollIntoViewIfNeeded();
  await page.waitForTimeout(200);

  const panels = await page.$$('.gallery-panel');
  console.log(`Total panels found: ${panels.length} (must be 8)`);
  if (panels.length !== 8) throw new Error(`Expected 8 panels, got ${panels.length}`);

  const activeIndices = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) activeIndices.push(i);
  }
  console.log(`Initial active panel indices: ${JSON.stringify(activeIndices)} (must be [0, 7])`);
  if (activeIndices.length !== 2 || activeIndices[0] !== 0 || activeIndices[1] !== 7) {
    throw new Error(`Expected exactly panels 0 and 7 active on load, got ${JSON.stringify(activeIndices)}`);
  }

  const p0Title = await panels[0].$eval('.gallery-panel-title', el => el.innerText.trim());
  const p7Title = await panels[7].$eval('.gallery-panel-title', el => el.innerText.trim());
  console.log(`Row 1 default active: "${p0Title}" (Personal Gifts)`);
  console.log(`Row 2 default active: "${p7Title}" (Branded Merchandise)`);
  if (p0Title !== 'Personal Gifts' || p7Title !== 'Branded Merchandise') {
    throw new Error('Default titles do not match expected Personal Gifts and Branded Merchandise');
  }

  console.log('\n--- 2. Testing Independent Row Hover Behavior ---');
  // Hover Panel 1 (Couple T-Shirts in Row 1)
  console.log('Hovering Panel 1 (Couple T-Shirts in Row 1)...');
  const p1Box = await panels[1].boundingBox();
  await page.mouse.move(p1Box.x + p1Box.width / 2, p1Box.y + p1Box.height / 2);
  await page.waitForTimeout(700); // Wait for transition

  const afterR1HoverActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) afterR1HoverActives.push(i);
  }
  console.log(`Active panels after hovering Panel 1: ${JSON.stringify(afterR1HoverActives)} (must be [1, 7])`);
  if (afterR1HoverActives.length !== 2 || afterR1HoverActives[0] !== 1 || afterR1HoverActives[1] !== 7) {
    throw new Error(`Expected panels 1 and 7 active, got ${JSON.stringify(afterR1HoverActives)}`);
  }

  // Hover Panel 5 (Custom T-Shirts in Row 2)
  console.log('Hovering Panel 5 (Custom T-Shirts in Row 2)...');
  const p5Box = await panels[5].boundingBox();
  await page.mouse.move(p5Box.x + p5Box.width / 2, p5Box.y + p5Box.height / 2);
  await page.waitForTimeout(700);

  const afterR2HoverActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) afterR2HoverActives.push(i);
  }
  console.log(`Active panels after hovering Panel 5: ${JSON.stringify(afterR2HoverActives)} (must be [1, 5])`);
  if (afterR2HoverActives.length !== 2 || afterR2HoverActives[0] !== 1 || afterR2HoverActives[1] !== 5) {
    throw new Error(`Expected panels 1 and 5 active, got ${JSON.stringify(afterR2HoverActives)}`);
  }

  console.log('\n--- 3. Testing Mouseleave Persistence (No Auto-Reset) ---');
  console.log('Moving mouse outside gallery to (50, 50)...');
  await page.mouse.move(50, 50);
  await page.waitForTimeout(500);

  const afterLeaveActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) afterLeaveActives.push(i);
  }
  console.log(`Active panels after mouseleave: ${JSON.stringify(afterLeaveActives)} (must remain [1, 5])`);
  if (afterLeaveActives.length !== 2 || afterLeaveActives[0] !== 1 || afterLeaveActives[1] !== 5) {
    throw new Error(`Active cards must stay stable on mouseleave! Got: ${JSON.stringify(afterLeaveActives)}`);
  }

  console.log('\n--- 4. Testing Delayed Text Reveal & Quick Hide on Collapse ---');
  // Check that collapsed panel 0 has hidden text (opacity 0, visibility hidden)
  const p0TextHidden = await panels[0].evaluate(el => {
    const title = el.querySelector('.gallery-panel-title');
    const desc = el.querySelector('.gallery-panel-desc');
    const sTitle = window.getComputedStyle(title);
    const sDesc = window.getComputedStyle(desc);
    return parseFloat(sTitle.opacity) === 0 && parseFloat(sDesc.opacity) === 0;
  });
  console.log(`Collapsed Panel 0 text is hidden: ${p0TextHidden} (must be true)`);
  if (!p0TextHidden) throw new Error('Collapsed panel text must be hidden!');

  // Check that active panel 1 has visible text
  const p1TextVisible = await panels[1].evaluate(el => {
    const title = el.querySelector('.gallery-panel-title');
    const desc = el.querySelector('.gallery-panel-desc');
    const sTitle = window.getComputedStyle(title);
    const sDesc = window.getComputedStyle(desc);
    return parseFloat(sTitle.opacity) === 1 && parseFloat(sDesc.opacity) === 1;
  });
  console.log(`Active Panel 1 text is visible: ${p1TextVisible} (must be true)`);
  if (!p1TextVisible) throw new Error('Active panel text must be visible!');

  // Check transition delays on active text elements
  const delays = await panels[1].evaluate(el => {
    const tag = window.getComputedStyle(el.querySelector('.gallery-panel-tag')).transitionDelay;
    const title = window.getComputedStyle(el.querySelector('.gallery-panel-title')).transitionDelay;
    const desc = window.getComputedStyle(el.querySelector('.gallery-panel-desc')).transitionDelay;
    return { tag, title, desc };
  });
  console.log('Active text transition delays:', delays);

  console.log('\n--- 5. Testing Viewports & Responsiveness ---');
  const viewports = [
    { width: 320, height: 600, name: '320px (Mobile S)' },
    { width: 375, height: 667, name: '375px (iPhone SE)' },
    { width: 430, height: 932, name: '430px (iPhone Pro Max)' },
    { width: 768, height: 1024, name: '768px (Tablet)' },
    { width: 1024, height: 768, name: '1024px (Desktop S)' },
    { width: 1440, height: 900, name: '1440px (Desktop HD)' },
    { width: 1920, height: 1080, name: '1920px (Full HD)' }
  ];

  for (const vp of viewports) {
    await page.setViewportSize({ width: vp.width, height: vp.height });
    await page.waitForTimeout(100);
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth);
    console.log(`Viewport ${vp.name}: Horizontal overflow = ${overflow}`);
    if (overflow) throw new Error(`Overflow at ${vp.name}!`);
  }

  // Mobile tap on Panel 2
  await page.setViewportSize({ width: 390, height: 844 });
  await page.waitForTimeout(150);
  await panels[2].click();
  await page.waitForTimeout(150);
  const p2Active = await panels[2].evaluate(el => el.classList.contains('active'));
  console.log(`Mobile tap on Panel 2 activates panel: ${p2Active}`);
  if (!p2Active) throw new Error('Mobile tap must activate card');

  // Screenshots
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.waitForTimeout(200);
  const solSection = await page.$('#print-solutions-section');
  await solSection.screenshot({ path: 'scratch/ultra-smooth-gallery-desktop.png' });

  await page.setViewportSize({ width: 390, height: 844 });
  await page.waitForTimeout(200);
  await solSection.screenshot({ path: 'scratch/ultra-smooth-gallery-mobile.png' });

  console.log('\nTotal Console Errors:', consoleErrors.length);
  if (consoleErrors.length > 0) {
    console.error('Console errors:', consoleErrors);
    throw new Error('Console errors encountered!');
  }

  await browser.close();
  console.log('\n>>> ULTRA-SMOOTH EXPANDING GALLERY TEST PASSED PERFECTLY! <<<');
})();

