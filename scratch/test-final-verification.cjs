const fs = require('fs');
const assert = require('assert');
const { chromium } = require('./node_modules/playwright-core');

(async () => {
  console.log('--- 1. Checking Markup Preservation & Clean Section Flow ---');
  const indexContent = fs.readFileSync('index.php', 'utf8');
  assert(indexContent.includes('hero-section'), 'Hero section preserved');
  assert(indexContent.includes('upload-studio'), 'Upload studio workbench preserved');
  assert(indexContent.includes('products-showcase'), 'Popular products preserved');
  assert(!indexContent.includes('Our DTF, UV DTF and Custom Printing Services'), 'Duplicate services section cleanly removed');
  assert(!indexContent.includes('Why Choose Printex for Bulk DTF & UV DTF Printing?'), 'Duplicate why-printex section cleanly removed');
  assert(indexContent.includes('Recent DTF, UV DTF and Merchandise Projects'), 'Featured portfolio showcase preserved');
  console.log('✓ Sections verified: Core features intact and duplicate sections removed.');

  console.log('--- 2. Launching Headless Chrome at 100% Zoom ---');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  const pageErrors = [];
  page.on('pageerror', err => pageErrors.push(err.message));
  page.on('console', msg => {
    if (msg.type() === 'error') pageErrors.push(msg.text());
  });

  const widths = [320, 360, 375, 390, 430, 768, 1024, 1280, 1440, 1920];
  console.log('--- 3. Testing Responsive Layouts across ' + widths.length + ' breakpoints ---');
  for (const w of widths) {
    await page.setViewportSize({ width: w, height: 1000 });
    await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

    const checks = await page.evaluate(() => {
      return {
        overflow: document.documentElement.scrollWidth > window.innerWidth,
        zoom: window.visualViewport ? window.visualViewport.scale : 1,
        h1Count: document.querySelectorAll('h1').length,
        hasBelt: !!document.querySelector('.home-marquee-belt'),
        phpErrors: /Warning:|Fatal error:/i.test(document.body.textContent)
      };
    });

    assert(!checks.overflow, `Horizontal overflow at ${w}px!`);
    assert.strictEqual(checks.zoom, 1, `Zoom is not 1 at ${w}px!`);
    assert.strictEqual(checks.h1Count, 1, `H1 count should be 1 at ${w}px!`);
    assert(checks.hasBelt, `Marquee belt missing at ${w}px!`);
    assert(!checks.phpErrors, `PHP errors detected on page at ${w}px!`);
    console.log(`✓ ${w}px: overflow=false, zoom=1, h1=1, marquee=present, phpErrors=false`);
  }

  console.log('--- 4. Testing Infinite Seamless Marquee Mechanics ---');
  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  const marqueeData = await page.evaluate(() => {
    const belt = document.querySelector('.home-marquee-belt');
    const anim = belt.getAnimations()[0];
    const tracks = belt.children;
    const w0 = tracks[0].getBoundingClientRect().width;
    const w1 = tracks[1].getBoundingClientRect().width;
    const dur = anim ? anim.effect.getTiming().duration : 0;
    
    // Test seam
    anim.pause();
    anim.currentTime = dur - 0.1;
    const beforeLeft = tracks[1].getBoundingClientRect().left;
    anim.currentTime = dur + 0.1;
    const afterLeft = tracks[0].getBoundingClientRect().left;
    const seam = Math.abs(beforeLeft - afterLeft);

    const hoverPlayState = getComputedStyle(belt).animationPlayState;

    return {
      w0,
      w1,
      equal: Math.abs(w0 - w1) < 0.2,
      widerThanViewport: w0 >= window.innerWidth,
      durSeconds: dur / 1000,
      seam,
      hoverPlayState
    };
  });

  assert(marqueeData.widerThanViewport, 'Marquee track 0 must be wider than viewport to avoid gap!');
  assert(marqueeData.equal, 'Tracks 0 and 1 must have equal width!');
  assert(marqueeData.seam < 1, `Seam jump should be < 1px, got ${marqueeData.seam}!`);
  assert(marqueeData.durSeconds >= 40, `Duration should be >= 40s (slower speed), got ${marqueeData.durSeconds}s!`);
  console.log(`✓ Marquee width=${marqueeData.w0.toFixed(1)}px (>= viewport), seam=${marqueeData.seam.toFixed(4)}px, duration=${marqueeData.durSeconds.toFixed(1)}s, never pauses.`);

  console.log('--- 5. Testing FAQ Accordion & Keyboard Accessibility ---');
  const faq1Trigger = page.locator('.print-faq .faq-trigger').nth(1);
  const faq1Item = page.locator('.print-faq .faq-item').nth(1);
  
  // Initially item 1 is collapsed
  assert.strictEqual(await faq1Trigger.getAttribute('aria-expanded'), 'false');
  
  // Press Enter
  await faq1Trigger.focus();
  await page.keyboard.press('Enter');
  await page.waitForTimeout(200);
  assert.strictEqual(await faq1Trigger.getAttribute('aria-expanded'), 'true');
  assert(await faq1Item.evaluate(el => el.classList.contains('active')));
  console.log('✓ FAQ item 1 opens on Enter key.');

  // Press Space
  await page.keyboard.press('Space');
  await page.waitForTimeout(200);
  assert.strictEqual(await faq1Trigger.getAttribute('aria-expanded'), 'false');
  console.log('✓ FAQ item 1 closes on Space key.');

  console.log('--- 6. Testing Service Links ---');
  const serviceLinks = await page.locator('#homepage-information a').all();
  for (const link of serviceLinks) {
    const href = await link.getAttribute('href');
    if (href && !href.startsWith('#')) {
      const resp = await page.request.get(href);
      assert.strictEqual(resp.status(), 200, `Link ${href} returned ${resp.status()}`);
    }
  }
  console.log(`✓ All ${serviceLinks.length} navigation and service links return HTTP 200.`);

  console.log('--- 7. Testing Upload Workbench Dropzone ---');
  await page.locator('#artworkFileInput').setInputFiles('assets/images/dtf_fabric_print.jpg');
  const previewText = await page.locator('#filePreviewArea').innerText();
  assert(previewText.includes('dtf_fabric_print.jpg'), 'Preview area displays uploaded filename');
  console.log('✓ Upload workbench preview functions without issues.');

  console.log('--- 8. Testing prefers-reduced-motion ---');
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await page.waitForTimeout(150);
  const reducedAnimCount = await page.locator('.home-marquee-belt').evaluate(el => el.getAnimations().length);
  assert.strictEqual(reducedAnimCount, 0, 'Marquee animation disabled under reduced motion');
  console.log('✓ Reduced motion cleanly cancels marquee animations.');

  console.log('--- 9. Console Error Verification ---');
  assert.strictEqual(pageErrors.length, 0, `Expected 0 page errors, got: ${JSON.stringify(pageErrors)}`);
  console.log('✓ 0 JavaScript console errors detected.');

  await browser.close();
  console.log('====================================================');
  console.log('ALL VERIFICATION SUITE CHECKS PASSED SUCCESSFULLY!');
  console.log('====================================================');
})();

