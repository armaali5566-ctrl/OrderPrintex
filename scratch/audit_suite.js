const { chromium } = require('./node_modules/playwright-core');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'http://127.0.0.1:8080';

const PAGES = [
  { name: 'home', url: '/' },
  { name: 'products', url: '/products/' },
  { name: 'product_details', url: '/products/custom-dtf-gang-sheet' },
  { name: 'get_quote', url: '/get-quote/' },
  { name: 'services', url: '/services/' },
  { name: 'about', url: '/about/' },
  { name: 'contact', url: '/contact/' },
  { name: 'gallery', url: '/gallery/' },
  { name: 'blog', url: '/blog/' },
  { name: 'blog_detail', url: '/blog/complete-guide-to-business-card-printing' },
  { name: 'pricing', url: '/pricing/' },
  { name: 'cart', url: '/cart/' },
  { name: 'checkout', url: '/checkout/' },
  { name: 'account_login', url: '/account/login/' },
  { name: 'account_register', url: '/account/register.php' },
  { name: 'account_wishlist', url: '/account/wishlist.php' },
  { name: 'page_404', url: '/404.php' },
  { name: 'admin_login', url: '/admin/login.php' }
];

const VIEWPORTS = [
  // Small phones
  { name: 'small_phone_320', width: 320, height: 568 },
  { name: 'small_phone_360', width: 360, height: 740 },
  { name: 'iphone_se_375', width: 375, height: 667 },
  // Standard phones
  { name: 'iphone_14_390', width: 390, height: 844 },
  { name: 'iphone_15_393', width: 393, height: 852 },
  { name: 'android_412', width: 412, height: 915 },
  // Large phones
  { name: 'iphone_promax_430', width: 430, height: 932 },
  { name: 'large_android_440', width: 440, height: 956 },
  // Tablets
  { name: 'tablet_600', width: 600, height: 960 },
  { name: 'ipad_mini_768', width: 768, height: 1024 },
  { name: 'tablet_800', width: 800, height: 1280 },
  { name: 'ipad_air_820', width: 820, height: 1180 },
  { name: 'ipad_pro_11_834', width: 834, height: 1194 },
  { name: 'surface_pro_912', width: 912, height: 1368 },
  { name: 'ipad_pro_1024', width: 1024, height: 1366 },
  { name: 'ipad_pro_issue_1032', width: 1032, height: 1376 },
  { name: 'tablet_1112', width: 1112, height: 834 },
  { name: 'tablet_1180', width: 1180, height: 820 },
  // Desktop
  { name: 'desktop_1280', width: 1280, height: 800 },
  { name: 'desktop_1366', width: 1366, height: 768 },
  { name: 'desktop_1440', width: 1440, height: 900 },
  { name: 'desktop_1600', width: 1600, height: 900 },
  { name: 'desktop_1920', width: 1920, height: 1080 }
];

async function scrollPage(page) {
  await page.evaluate(async () => {
    await new Promise((resolve) => {
      let totalHeight = 0;
      const distance = 400;
      const timer = setInterval(() => {
        const scrollHeight = document.body.scrollHeight;
        window.scrollBy(0, distance);
        totalHeight += distance;
        if (totalHeight >= scrollHeight) {
          clearInterval(timer);
          window.scrollTo(0, 0);
          resolve();
        }
      }, 50);
    });
  });
  await page.waitForTimeout(250);
}

async function runAudit(mode = 'before') {
  const screenshotDir = path.join(__dirname, '..', 'reports', 'screenshots', mode);
  if (!fs.existsSync(screenshotDir)) {
    fs.mkdirSync(screenshotDir, { recursive: true });
  }

  const browser = await chromium.launch({
    executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
    headless: true
  });

  const results = [];

  console.log(`=== STARTING ORDERPRINTEX UI/UX AUDIT (${mode.toUpperCase()}) ===`);
  console.log(`Total Pages: ${PAGES.length}`);
  console.log(`Total Viewports: ${VIEWPORTS.length}`);

  for (const pageInfo of PAGES) {
    console.log(`\n--- Auditing Page: ${pageInfo.name} (${pageInfo.url}) ---`);
    const page = await browser.newPage();

    // Listen to console errors
    const consoleErrors = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });

    for (const vp of VIEWPORTS) {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      try {
        const res = await page.goto(BASE_URL + pageInfo.url, { waitUntil: 'domcontentloaded', timeout: 15000 });
        const statusCode = res ? res.status() : 0;

        // Smooth scroll to trigger reveals and animations
        await scrollPage(page);

        // Analyze layout and overflow
        const pageMetrics = await page.evaluate(() => {
          const docEl = document.documentElement;
          const body = document.body;
          const docWidth = docEl.clientWidth;
          const scrollWidth = Math.max(docEl.scrollWidth, body.scrollWidth);
          const hasHorizontalOverflow = scrollWidth > docWidth + 1; // 1px threshold for subpixels

          // Find overflowing elements
          const overflowingElements = [];
          if (hasHorizontalOverflow) {
            const allElements = document.querySelectorAll('*');
            for (const el of allElements) {
              const rect = el.getBoundingClientRect();
              if (rect.right > docWidth + 2) {
                overflowingElements.push({
                  tag: el.tagName,
                  id: el.id,
                  className: el.className ? String(el.className).substring(0, 50) : '',
                  right: rect.right,
                  overflowBy: rect.right - docWidth
                });
                if (overflowingElements.length >= 5) break;
              }
            }
          }

          // Measure main container width relative to viewport
          const container = document.querySelector('.container, .container-wide, .container-fluid');
          const containerWidth = container ? container.getBoundingClientRect().width : null;
          const containerRatio = containerWidth ? (containerWidth / docWidth) : null;

          // Check if any headings or text have broken single-letter wrapping
          const textNodes = [];
          const headings = document.querySelectorAll('h1, h2, h3, h4, h5, .btn, .nav-link, .category-chip');
          let singleLetterWrapDetected = false;
          for (const h of headings) {
            const style = window.getComputedStyle(h);
            if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') continue;
            const rect = h.getBoundingClientRect();
            // If visible element is narrower than 45px and contains more than 4 letters
            if (rect.width > 0 && rect.width < 45 && h.textContent.trim().length > 4) {
              singleLetterWrapDetected = true;
            }
          }

          return {
            docWidth,
            scrollWidth,
            hasHorizontalOverflow,
            overflowingElements,
            containerWidth,
            containerRatio,
            singleLetterWrapDetected
          };
        });

        // Screenshot representative viewports
        // 375 (SE), 390 (iPhone), 430 (Max), 768 (iPad mini), 1032 (iPad Pro), 1440 (Desktop)
        const isKeyViewport = [375, 390, 430, 768, 1032, 1440].includes(vp.width);
        if (isKeyViewport) {
          const filename = `${pageInfo.name}_${vp.width}px.png`;
          const filePath = path.join(screenshotDir, filename);
          await page.screenshot({ path: filePath, fullPage: true });
        }

        const issueFound = pageMetrics.hasHorizontalOverflow || pageMetrics.singleLetterWrapDetected;
        if (issueFound) {
          console.log(`  [ISSUE] ${vp.name} (${vp.width}px): Overflow=${pageMetrics.hasHorizontalOverflow} (by ${pageMetrics.scrollWidth - pageMetrics.docWidth}px), SingleLetterWrap=${pageMetrics.singleLetterWrapDetected}`);
          if (pageMetrics.overflowingElements.length > 0) {
            console.log('    Elements:', JSON.stringify(pageMetrics.overflowingElements));
          }
        } else {
          // Success
          process.stdout.write(`.`);
        }

        results.push({
          page: pageInfo.name,
          url: pageInfo.url,
          viewport: vp.name,
          width: vp.width,
          statusCode,
          hasHorizontalOverflow: pageMetrics.hasHorizontalOverflow,
          overflowBy: pageMetrics.scrollWidth - pageMetrics.docWidth,
          overflowingElements: pageMetrics.overflowingElements,
          containerRatio: pageMetrics.containerRatio,
          singleLetterWrapDetected: pageMetrics.singleLetterWrapDetected,
          consoleErrorsCount: consoleErrors.length
        });
      } catch (err) {
        console.error(`  [ERROR] ${pageInfo.name} at ${vp.name}: ${err.message}`);
        results.push({
          page: pageInfo.name,
          viewport: vp.name,
          width: vp.width,
          error: err.message
        });
      }
    }
    await page.close();
  }

  await browser.close();

  const reportPath = path.join(__dirname, `audit_${mode}_results.json`);
  fs.writeFileSync(reportPath, JSON.stringify(results, null, 2));
  console.log(`\n\nAudit finished! Results written to ${reportPath}`);

  return results;
}

const auditMode = process.argv[2] || 'after';
runAudit(auditMode).catch(console.error);
