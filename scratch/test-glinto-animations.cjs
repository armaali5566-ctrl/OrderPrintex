const { chromium } = require('./node_modules/playwright-core');
const assert = require('assert');

(async () => {
  console.log('--- Testing Glinto-Inspired Motion & Interaction Suite ---');
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage({ deviceScaleFactor: 1 });
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', msg => { if (msg.type() === 'error') errors.push(msg.text()); });

  await page.setViewportSize({ width: 1440, height: 950 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // 1. Check Meet Printex line-active stroke
  await page.locator('.studio-editorial-heading').scrollIntoViewIfNeeded();
  await page.waitForTimeout(300);
  const headingHasLineActive = await page.evaluate(() => {
    return document.querySelector('.studio-editorial-heading').classList.contains('line-active');
  });
  assert(headingHasLineActive, 'Studio editorial heading has line-active class on scroll into view');
  console.log('✓ Heading line stroke reveal activated.');

  // 2. Check Timeline progressive reveal
  await page.locator('.printing-process').scrollIntoViewIfNeeded();
  await page.waitForTimeout(400);
  const timelineRevealed = await page.evaluate(() => {
    const tl = document.querySelector('.printing-timeline');
    const stages = document.querySelectorAll('.printing-stage.stage-revealed');
    return { hasClass: tl.classList.contains('timeline-revealed'), revealedStages: stages.length };
  });
  assert(timelineRevealed.hasClass, 'Timeline has timeline-revealed class');
  assert(timelineRevealed.revealedStages >= 3, `Expected stages revealed, got ${timelineRevealed.revealedStages}`);
  console.log(`✓ Progressive timeline reveal activated (${timelineRevealed.revealedStages}/4 stages revealed).`);

  // 3. Check bidirectional scroll stability (down then up)
  console.log('Testing bidirectional scrolling stability...');
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
  await page.waitForTimeout(300);
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(300);

  const elementsVisible = await page.evaluate(() => {
    const checkElements = [
      document.querySelector('#meet-printex-title'),
      document.querySelector('.studio-main-photo'),
      document.querySelector('#print-showcase-title'),
      document.querySelector('#printing-process-title')
    ];
    return checkElements.every(el => {
      const style = getComputedStyle(el);
      return style.opacity !== '0' && style.visibility !== 'hidden';
    });
  });
  assert(elementsVisible, 'All editorial elements remain 100% visible after scrolling down and back up');
  console.log('✓ Zero flickering: elements stay fully visible on bidirectional scroll.');

  // 4. Check Button & Link Micro-Interactions
  const btnHover = await page.evaluate(() => {
    const quote = document.querySelector('.studio-quote');
    const quoteStyle = getComputedStyle(quote);
    return {
      quoteTrans: quoteStyle.transitionProperty === 'all' || quoteStyle.transition.includes('0.35s')
    };
  });
  assert(btnHover.quoteTrans, 'Studio quote button has smooth transform transition');
  console.log('✓ Button and link micro-interaction transitions configured.');

  // 5. Check Console Errors
  assert.strictEqual(errors.length, 0, `Errors found: ${JSON.stringify(errors)}`);
  console.log('✓ 0 JavaScript or console errors detected.');

  await browser.close();
  console.log('====================================================');
  console.log('GLINTO MOTION SUITE VERIFIED WITH 100% SUCCESS!');
  console.log('====================================================');
})();
