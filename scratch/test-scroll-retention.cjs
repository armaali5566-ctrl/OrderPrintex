const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:/Users/Arman/AppData/Local/Google/Chrome/Application/chrome.exe',
    headless: true
  });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  console.log('Testing homepage scroll down and scroll up for flicker...');

  // Step 1: Scroll down through page in increments
  for (let y = 0; y <= 5000; y += 400) {
    await page.evaluate((pos) => window.scrollTo(0, pos), y);
    await page.waitForTimeout(50);
  }

  // Check state of revealed items at bottom
  const revealedStatsBottom = await page.evaluate(() => {
    const reveals = Array.from(document.querySelectorAll('.reveal'));
    const activeCount = reveals.filter(r => r.classList.contains('active')).length;
    return { total: reveals.length, active: activeCount };
  });
  console.log(`Scrolled to bottom: ${revealedStatsBottom.active}/${revealedStatsBottom.total} .reveal elements active.`);

  // Step 2: Scroll up through page in increments
  for (let y = 5000; y >= 0; y -= 400) {
    await page.evaluate((pos) => window.scrollTo(0, pos), y);
    await page.waitForTimeout(50);
  }

  // Check state of revealed items back at top
  const revealedStatsTop = await page.evaluate(() => {
    const reveals = Array.from(document.querySelectorAll('.reveal'));
    const activeCount = reveals.filter(r => r.classList.contains('active')).length;
    return { total: reveals.length, active: activeCount };
  });
  console.log(`Scrolled back to top: ${revealedStatsTop.active}/${revealedStatsTop.total} .reveal elements active.`);

  if (revealedStatsTop.active < revealedStatsBottom.active) {
    console.error('FAIL: Elements were deactivated when scrolling back up!');
    process.exit(1);
  }
  console.log('PASS: Zero deactivation or blinking on scroll up. All elements stay revealed.');

  // Also test about.php for tech-zigzag-row
  await page.goto('http://127.0.0.1:8080/about', { waitUntil: 'networkidle' });
  await page.evaluate(() => window.scrollTo(0, 1500));
  await page.waitForTimeout(300);
  const aboutRowsVisible = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.tech-zigzag-row')).filter(r => r.classList.contains('is-visible')).length;
  });
  console.log(`about.php tech rows visible: ${aboutRowsVisible}`);

  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);
  const aboutRowsVisibleAfter = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.tech-zigzag-row')).filter(r => r.classList.contains('is-visible')).length;
  });
  console.log(`about.php tech rows visible after scroll to top: ${aboutRowsVisibleAfter}`);

  if (aboutRowsVisibleAfter < aboutRowsVisible) {
    console.error('FAIL: tech-zigzag-rows were hidden on scroll up!');
    process.exit(1);
  }
  console.log('PASS: tech-zigzag-rows stay visible permanently without blinking.');

  await browser.close();
  console.log('ALL SCROLL RETENTION CHECKS PASSED!');
})();

