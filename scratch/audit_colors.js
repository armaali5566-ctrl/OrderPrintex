const { chromium } = require('./node_modules/playwright-core');
const fs = require('fs');
const path = require('path');

const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

const PAGES = [
  { name: 'Admin Users', file: 'admin/users.php' },
  { name: 'Manage FAQs', file: 'admin/faqs.php' },
  { name: 'Work Showcase & Gallery', file: 'admin/gallery.php' },
  { name: 'Blog Articles', file: 'admin/blog.php' },
  { name: 'Pages CMS', file: 'admin/pages.php' },
  { name: 'Product Reviews', file: 'admin/reviews.php' },
  { name: 'Printing Services', file: 'admin/services.php' },
  { name: 'Registered Clients', file: 'admin/customers.php' },
  { name: 'Client Quotations', file: 'admin/quotations.php' },
  { name: 'Customer Enquiries', file: 'admin/enquiries.php' }
];

function isLightColor(rgbStr) {
  if (!rgbStr || rgbStr === 'transparent' || rgbStr === 'rgba(0, 0, 0, 0)') return false;
  const match = rgbStr.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
  if (!match) return false;
  const r = parseInt(match[1], 10);
  const g = parseInt(match[2], 10);
  const b = parseInt(match[3], 10);
  // Perceived brightness
  const brightness = (r * 299 + g * 587 + b * 114) / 1000;
  return brightness > 180; // Bright/light colors (e.g. white is 255, light gray is > 200)
}

(async () => {
  console.log('=== STARTING DEEP COLOR & THEME AUDIT OF ALL 10 PAGES ===\n');

  const browser = await chromium.launch({
    executablePath: CHROME_PATH,
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

  // 1. Login
  await page.goto(`${BASE_URL}/admin/login.php`, { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'Admin@Printex2026!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  let totalFlaws = 0;

  for (const p of PAGES) {
    console.log(`\n--- Inspecting [${p.name}] (${p.file}) ---`);
    await page.goto(`${BASE_URL}/${p.file}`, { waitUntil: 'networkidle' });

    // A. Check that theme switcher is GONE
    const themeBtn = await page.$('.theme-toggle-btn');
    if (themeBtn) {
      console.error(`  ✕ FAIL: .theme-toggle-btn still exists in ${p.file}`);
      totalFlaws++;
    } else {
      console.log(`  ✓ Theme switcher button removed.`);
    }

    // B. Check computed styles of key containers and form elements
    const colorAudit = await page.evaluate(() => {
      function getBg(sel) {
        const el = document.querySelector(sel);
        if (!el) return null;
        return window.getComputedStyle(el).backgroundColor;
      }

      function getAllBg(sel) {
        const els = Array.from(document.querySelectorAll(sel));
        return els.map(e => ({
          tag: e.tagName,
          classes: e.className,
          bg: window.getComputedStyle(e).backgroundColor,
          color: window.getComputedStyle(e).color
        }));
      }

      return {
        bodyBg: getBg('body'),
        mainBg: getBg('.dashboard-main'),
        topbarBg: getBg('.dashboard-topbar'),
        cardBg: getBg('.admin-card') || getBg('.panel-card'),
        cardHeaderBg: getBg('.admin-card-header') || getBg('.panel-header'),
        tableThBg: getBg('.admin-table th') || getBg('.data-table th'),
        tableTdBg: getBg('.admin-table td') || getBg('.data-table td'),
        inputs: getAllBg('input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]), select, textarea'),
        filterBarBg: getBg('.admin-filter-bar')
      };
    });

    console.log(`  • Body BG:        ${colorAudit.bodyBg}`);
    console.log(`  • Main Canvas BG: ${colorAudit.mainBg}`);
    console.log(`  • Topbar BG:      ${colorAudit.topbarBg}`);
    console.log(`  • Card Surface:   ${colorAudit.cardBg}`);
    console.log(`  • Card Header:    ${colorAudit.cardHeaderBg}`);
    console.log(`  • Table Header:   ${colorAudit.tableThBg || 'N/A'}`);
    console.log(`  • Filter Bar:     ${colorAudit.filterBarBg || 'N/A'}`);

    // Check for light background violations
    if (isLightColor(colorAudit.bodyBg)) {
      console.error(`  ✕ VIOLATION: Body has light background: ${colorAudit.bodyBg}`);
      totalFlaws++;
    }
    if (isLightColor(colorAudit.mainBg)) {
      console.error(`  ✕ VIOLATION: Main canvas has light background: ${colorAudit.mainBg}`);
      totalFlaws++;
    }
    if (isLightColor(colorAudit.cardBg)) {
      console.error(`  ✕ VIOLATION: Card has light background: ${colorAudit.cardBg}`);
      totalFlaws++;
    }
    if (isLightColor(colorAudit.cardHeaderBg)) {
      console.error(`  ✕ VIOLATION: Card header has light background: ${colorAudit.cardHeaderBg}`);
      totalFlaws++;
    }
    if (colorAudit.tableThBg && isLightColor(colorAudit.tableThBg)) {
      console.error(`  ✕ VIOLATION: Table header has light background: ${colorAudit.tableThBg}`);
      totalFlaws++;
    }
    if (colorAudit.filterBarBg && isLightColor(colorAudit.filterBarBg)) {
      console.error(`  ✕ VIOLATION: Filter bar has light background: ${colorAudit.filterBarBg}`);
      totalFlaws++;
    }

    // Check inputs
    let lightInputs = 0;
    for (const inp of colorAudit.inputs) {
      if (isLightColor(inp.bg)) {
        lightInputs++;
      }
    }
    if (lightInputs > 0) {
      console.error(`  ✕ VIOLATION: ${lightInputs} inputs/selects/textareas have light background!`);
      totalFlaws++;
    } else if (colorAudit.inputs.length > 0) {
      console.log(`  ✓ All ${colorAudit.inputs.length} form inputs/selects/textareas have dark backgrounds.`);
    }

    // Capture screenshot for visual inspection
    const screenshotDir = path.join(__dirname, 'screenshots');
    if (!fs.existsSync(screenshotDir)) fs.mkdirSync(screenshotDir);
    const slug = p.file.replace('admin/', '').replace('.php', '');
    await page.screenshot({ path: path.join(screenshotDir, `${slug}_1440.png`), fullPage: false });
  }

  // Also test a mobile viewport (375px) on all 10 pages for horizontal overflow
  console.log('\n--- Checking Mobile (375px) Horizontal Overflow on All 10 Pages ---');
  await page.setViewportSize({ width: 375, height: 667 });
  for (const p of PAGES) {
    await page.goto(`${BASE_URL}/${p.file}`, { waitUntil: 'networkidle' });
    const overflow = await page.evaluate(() => {
      const docW = document.documentElement.scrollWidth;
      const winW = window.innerWidth;
      const bodyW = document.body.scrollWidth;
      return docW > winW + 2 || bodyW > winW + 2;
    });
    if (overflow) {
      console.error(`  ✕ OVERFLOW: ${p.name} overflows on 375px!`);
      totalFlaws++;
    } else {
      console.log(`  ✓ [375px PASS] ${p.name.padEnd(26)} Fits perfectly`);
    }
  }

  await browser.close();

  console.log('\n=== AUDIT SUMMARY ===');
  console.log(`Total Flaws Found: ${totalFlaws}`);
  if (totalFlaws === 0) {
    console.log('>>> ALL 10 PAGES ARE 100% CLEAN WITH LUXURY DARK THEME! <<<\n');
  } else {
    console.error('>>> SOME ISSUES WERE DETECTED. PLEASE RESOLVE. <<<\n');
    process.exit(1);
  }
})();

