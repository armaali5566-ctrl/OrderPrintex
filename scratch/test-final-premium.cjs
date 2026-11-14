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

  // 1. EXACT CONTENT CHECK FOR MEET PRINTEX
  console.log('\n--- 1. Testing Exact Content for Meet Printex ---');
  const meetSection = await page.$('#meet-printex-section');
  if (!meetSection) throw new Error('Missing #meet-printex-section');

  const overline = await page.$eval('.studio-hero-overline', el => el.innerText.trim());
  console.log('Overline:', overline);
  if (!overline.includes('ABOUT PRINTEX') || !overline.includes('DELHI PRINTING STUDIO')) {
    throw new Error('Overline text mismatch');
  }

  const mainHeading = await page.$eval('#meet-printex-title', el => el.innerText.trim());
  console.log('Main Heading:', mainHeading);
  if (mainHeading !== 'MEET PRINTEX.') {
    throw new Error(`Main heading expected "MEET PRINTEX.", found "${mainHeading}"`);
  }

  const subheading = await page.$eval('.studio-subheading', el => el.innerText.trim().replace(/\s+/g, ' '));
  console.log('Subheading:', subheading);
  if (subheading !== 'Printing for your brand.') {
    throw new Error(`Subheading expected "Printing for your brand.", found "${subheading}"`);
  }

  const brandUnderline = await page.$eval('.studio-accent-brand em', el => {
    const style = window.getComputedStyle(el);
    return { fontStyle: style.fontStyle, color: style.color };
  });
  console.log('Brand accent style:', brandUnderline);

  const badgeTopLeft = await page.$eval('.studio-badge-top-left', el => el.innerText.trim());
  console.log('Badge Top Left:', badgeTopLeft);
  if (!badgeTopLeft.includes('STUDIO PRODUCTION')) throw new Error('Top left badge mismatch');

  const badgeBottomLeft = await page.$eval('.studio-badge-bottom-left', el => el.innerText.trim());
  console.log('Badge Bottom Left:', badgeBottomLeft);
  if (!badgeBottomLeft.includes('THE PRINT') || !badgeBottomLeft.includes('FROM ARTWORK TO APPLICATION')) {
    throw new Error('Bottom left badge mismatch');
  }

  const philosophyOverline = await page.$eval('.studio-kicker-label', el => el.innerText.trim());
  console.log('Philosophy Overline:', philosophyOverline);
  if (!philosophyOverline.includes('STUDIO PHILOSOPHY')) throw new Error('Philosophy overline mismatch');

  const paragraphs = await page.$$eval('.studio-philosophy-p', els => els.map(e => e.innerText.trim()));
  console.log(`Philosophy Paragraphs count: ${paragraphs.length}`);
  if (paragraphs.length !== 3) throw new Error(`Expected 3 paragraphs, found ${paragraphs.length}`);
  console.log('P1:', paragraphs[0]);
  console.log('P2:', paragraphs[1]);
  console.log('P3:', paragraphs[2]);

  const moreBtn = await page.$eval('.studio-more-btn', el => ({ text: el.innerText.trim().replace(/\s+/g, ' '), href: el.getAttribute('href') }));
  console.log('More button:', moreBtn);
  if (!moreBtn.text.includes('More about Printex') || !moreBtn.href.includes('about')) {
    throw new Error('More button mismatch');
  }

  const bulkTitle = await page.$eval('.bulk-card-title', el => el.innerText.trim());
  const bulkText = await page.$eval('.bulk-card-text', el => el.innerText.trim());
  console.log('Bulk Title:', bulkTitle);
  console.log('Bulk Text:', bulkText);
  if (bulkTitle !== 'MADE FOR BULK ORDERS' || !bulkText.includes('Soft-hand finish')) {
    throw new Error('Bulk card mismatch');
  }

  // 2. 3D TEXT SANDWICH LAYERING & ALIGNMENT
  console.log('\n--- 2. Testing 3D Text Sandwich Layering & Alignment ---');
  const backLayer = await page.$('.studio-layer-back');
  const midLayer = await page.$('.studio-layer-image');
  const frontLayer = await page.$('.studio-layer-front');

  const zBack = await backLayer.evaluate(el => window.getComputedStyle(el).zIndex);
  const zMid = await midLayer.evaluate(el => window.getComputedStyle(el).zIndex);
  const zFront = await frontLayer.evaluate(el => window.getComputedStyle(el).zIndex);
  const frontPointer = await frontLayer.evaluate(el => window.getComputedStyle(el).pointerEvents);
  const frontAria = await frontLayer.getAttribute('aria-hidden');

  console.log(`Z-indices: Back=${zBack}, Middle=${zMid}, Front=${zFront}`);
  console.log(`Front pointer-events: ${frontPointer}, aria-hidden: ${frontAria}`);

  if (parseInt(zBack) >= parseInt(zMid) || parseInt(zMid) >= parseInt(zFront)) {
    throw new Error('Layer stacking order is incorrect!');
  }
  if (frontAria !== 'true' || frontPointer !== 'none') {
    throw new Error('Layer 3 must be aria-hidden="true" and pointer-events: none');
  }

  // Check semantic H2 uniqueness
  const h2Count = await page.$$eval('#meet-printex-title', els => els.length);
  console.log(`Semantic heading #meet-printex-title count: ${h2Count} (must be exactly 1)`);
  if (h2Count !== 1) throw new Error('Duplicate semantic heading ID found!');

  // 3. DESKTOP MOUSE PARALLAX & CUSTOM CURSOR
  console.log('\n--- 3. Testing Desktop Mouse Parallax & Custom Cursor ---');
  const heroFig = await page.$('#studio-hero-figure');
  const figBox = await heroFig.boundingBox();
  await page.mouse.move(figBox.x + figBox.width * 0.75, figBox.y + figBox.height * 0.75);
  await page.waitForTimeout(200);

  const figTransform = await heroFig.evaluate(el => el.style.transform);
  const imgTransform = await page.$eval('#studio-hero-figure img', el => el.style.transform);
  console.log(`Hero figure transform on mousemove: "${figTransform}"`);
  console.log(`Hero img transform on mousemove: "${imgTransform}"`);

  // Check custom cursor element
  const cursor = await page.$('#studio-gold-cursor');
  const cursorActive = await cursor.evaluate(el => el.classList.contains('active'));
  console.log(`Custom cursor active inside section: ${cursorActive}`);

  // Hover moreBtn to check cursor expand
  const moreBtnEl = await page.$('.studio-more-btn');
  await moreBtnEl.scrollIntoViewIfNeeded();
  await page.waitForTimeout(100);
  const btnBox = await moreBtnEl.boundingBox();
  await page.mouse.move(btnBox.x + btnBox.width / 2, btnBox.y + btnBox.height / 2);
  await page.waitForTimeout(150);
  const cursorExpanded = await cursor.evaluate(el => el.classList.contains('expanded'));
  console.log(`Custom cursor expanded over button: ${cursorExpanded}`);

  // Move outside section
  const solBox = await page.$eval('#print-solutions-section', el => {
    const r = el.getBoundingClientRect();
    return { x: r.left + 50, y: r.top + 50 };
  });
  await page.mouse.move(solBox.x, solBox.y);
  await page.waitForTimeout(250);
  const cursorActiveOutside = await cursor.evaluate(el => el.classList.contains('active'));
  const figTransformReset = await heroFig.evaluate(el => el.style.transform);
  console.log(`Custom cursor active outside section: ${cursorActiveOutside} (expected false)`);
  console.log(`Hero figure transform reset: "${figTransformReset}" (expected empty)`);
  if (cursorActiveOutside) throw new Error('Custom cursor must be inactive outside section');

  // 4. EXPANDING HOVER GALLERY
  console.log('\n--- 4. Testing Expanding Hover Gallery ---');
  const gallery = await page.$('.expanding-gallery-container');
  await gallery.scrollIntoViewIfNeeded();
  await page.waitForTimeout(200);

  const panels = await page.$$('.gallery-panel');
  console.log(`Total gallery panels: ${panels.length} (must be 8)`);
  if (panels.length !== 8) throw new Error(`Expected 8 panels, found ${panels.length}`);

  // Check numbers completely removed
  const galleryText = await gallery.evaluate(el => el.innerText);
  const hasNumbers = /\b0[1-8]\b/.test(galleryText);
  console.log(`Gallery text contains 01-08 numbers? ${hasNumbers} (must be false)`);
  if (hasNumbers) throw new Error('Numbers 01-08 still found in gallery!');

  // Check default active = Personal Gifts (index 0)
  const initialActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) initialActives.push(i);
  }
  console.log(`Initial active panel indices: ${JSON.stringify(initialActives)} (must be [0])`);
  if (initialActives.length !== 1 || initialActives[0] !== 0) {
    throw new Error(`Expected ONLY panel 0 active initially, found: ${JSON.stringify(initialActives)}`);
  }

  // Check collapsed cards have content hidden
  const p1ContentHidden = await panels[1].evaluate(el => {
    const c = el.querySelector('.gallery-panel-content');
    const s = window.getComputedStyle(c);
    return s.visibility === 'hidden' && parseFloat(s.opacity) === 0;
  });
  console.log(`Panel 1 (collapsed) content is hidden (visibility:hidden, opacity:0): ${p1ContentHidden}`);
  if (!p1ContentHidden) throw new Error('Collapsed panel content must be hidden!');

  // Hover Panel 5 (Row 2, Custom T-Shirts)
  console.log('Hovering Panel 5 (Row 2 Custom T-Shirts)...');
  await panels[5].scrollIntoViewIfNeeded();
  const p5Box = await panels[5].boundingBox();
  await page.mouse.move(p5Box.x + p5Box.width / 2, p5Box.y + p5Box.height / 2);
  await page.waitForTimeout(650);

  const afterHoverActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) afterHoverActives.push(i);
  }
  console.log(`Active panels after hovering Panel 5: ${JSON.stringify(afterHoverActives)} (must be [5])`);
  if (afterHoverActives.length !== 1 || afterHoverActives[0] !== 5) {
    throw new Error(`Expected ONLY panel 5 active globally, found: ${JSON.stringify(afterHoverActives)}`);
  }

  // Check row flex mechanics
  const p0Grow = await panels[0].evaluate(el => window.getComputedStyle(el).flexGrow);
  const p5Grow = await panels[5].evaluate(el => window.getComputedStyle(el).flexGrow);
  const p6Grow = await panels[6].evaluate(el => window.getComputedStyle(el).flexGrow);
  console.log(`FlexGrow: Row 1 Panel 0 (inactive row)=${p0Grow}, Row 2 Panel 5 (active)=${p5Grow}, Row 2 Panel 6 (sibling)=${p6Grow}`);

  // Test mouseleave restore to Personal Gifts
  console.log('Moving mouse outside gallery to verify leave-restore...');
  await page.mouse.move(50, 50);
  await page.waitForTimeout(400);

  const restoredActives = [];
  for (let i = 0; i < panels.length; i++) {
    const act = await panels[i].evaluate(el => el.classList.contains('active'));
    if (act) restoredActives.push(i);
  }
  console.log(`Active panels after leaving gallery: ${JSON.stringify(restoredActives)} (must be [0])`);
  if (restoredActives.length !== 1 || restoredActives[0] !== 0) {
    throw new Error(`Expected restore to panel 0, found: ${JSON.stringify(restoredActives)}`);
  }

  // Check destination links
  const links = await page.$$('.gallery-panel-link');
  console.log(`Found ${links.length} destination links in gallery.`);
  for (let i = 0; i < links.length; i++) {
    const href = await links[i].getAttribute('href');
    const txt = await links[i].innerText();
    console.log(`Link ${i}: "${txt.trim().replace(/\s+/g, ' ')}" -> ${href}`);
    if (href === '#' || href === '') throw new Error(`Dead link found: ${txt}`);
  }

  // 5. RESPONSIVE VIEWPORT TESTS
  console.log('\n--- 5. Testing Viewports (320px, 375px, 430px, 768px, 1024px, 1440px, 1920px) ---');
  const viewports = [
    { width: 320, height: 600, name: '320px (Small Mobile)' },
    { width: 375, height: 667, name: '375px (iPhone SE)' },
    { width: 430, height: 932, name: '430px (iPhone Pro Max)' },
    { width: 768, height: 1024, name: '768px (Tablet Portrait)' },
    { width: 1024, height: 768, name: '1024px (Tablet Landscape)' },
    { width: 1440, height: 900, name: '1440px (Desktop HD)' },
    { width: 1920, height: 1080, name: '1920px (Desktop Full HD)' }
  ];

  for (const vp of viewports) {
    await page.setViewportSize({ width: vp.width, height: vp.height });
    await page.waitForTimeout(150);
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth);
    console.log(`Viewport ${vp.name}: Horizontal overflow = ${overflow}`);
    if (overflow) throw new Error(`Horizontal overflow detected at ${vp.name}!`);
  }

  // Mobile Tap check at 390px
  await page.setViewportSize({ width: 390, height: 844 });
  await page.waitForTimeout(200);
  const p3 = panels[3]; // Wedding packages
  await p3.click();
  await page.waitForTimeout(150);
  const p3Act = await p3.evaluate(el => el.classList.contains('active'));
  console.log(`Mobile tap on Panel 3 activates panel: ${p3Act}`);

  // Take final screenshots
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.waitForTimeout(200);

  await meetSection.screenshot({ path: 'scratch/final-meet-printex-desktop.png' });
  const solSection = await page.$('#print-solutions-section');
  await solSection.screenshot({ path: 'scratch/final-expanding-gallery-desktop.png' });

  await page.setViewportSize({ width: 390, height: 844 });
  await page.waitForTimeout(200);
  await meetSection.screenshot({ path: 'scratch/final-meet-printex-mobile.png' });
  await solSection.screenshot({ path: 'scratch/final-expanding-gallery-mobile.png' });

  console.log('\nTotal Console Errors:', consoleErrors.length);
  if (consoleErrors.length > 0) {
    console.error('Console errors:', consoleErrors);
    throw new Error('Console errors encountered!');
  }

  await browser.close();
  console.log('\n>>> ALL FINAL VERIFICATIONS PASSED PERFECTLY! <<<');
})();
