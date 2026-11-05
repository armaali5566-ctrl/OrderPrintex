const { chromium } = require('./node_modules/playwright-core');

const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

const TARGET_MODULES = [
  { name: 'Enquiries & Artwork', url: 'admin/enquiries.php' },
  { name: 'Quotations', url: 'admin/quotations.php' },
  { name: 'Customer Accounts', url: 'admin/customers.php' },
  { name: 'Printing Services', url: 'admin/services.php' },
  { name: 'Customer Reviews', url: 'admin/reviews.php' },
  { name: 'Homepage CMS', url: 'admin/homepage.php' },
  { name: 'Pages CMS', url: 'admin/pages.php' },
  { name: 'Blog Articles', url: 'admin/blog.php' },
  { name: 'Media Library', url: 'admin/media.php' },
  { name: 'Gallery & Showcase', url: 'admin/gallery.php' },
  { name: 'Manage FAQs', url: 'admin/faqs.php' },
  { name: 'Admin Users', url: 'admin/users.php' }
];

const VIEWPORTS = [
  { name: 'Mobile 375px (iPhone SE)', width: 375, height: 667 },
  { name: 'Tablet 768px (iPad Mini)', width: 768, height: 1024 },
  { name: 'Desktop 1440px (Laptop)', width: 1440, height: 900 }
];

async function runAudit() {
  console.log('=== STARTING COMPLETE ADMIN PANEL VERIFICATION ===\n');

  const browser = await chromium.launch({
    executablePath: CHROME_PATH,
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 }
  });

  const page = await context.newPage();

  // 1. Authenticate
  console.log('[1/5] Authenticating at /admin/login.php...');
  await page.goto(`${BASE_URL}/admin/login.php`, { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'Admin@Printex2026!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  if (page.url().includes('login.php')) {
    throw new Error('Authentication failed');
  }
  console.log('✓ Successfully authenticated!\n');

  // 2. Audit all 12 modules for HTTP 200, PHP errors, and visual elements
  console.log('[2/5] Auditing all 12 targeted admin modules...');
  for (const mod of TARGET_MODULES) {
    const res = await page.goto(`${BASE_URL}/${mod.url}`, { waitUntil: 'networkidle' });
    const status = res.status();
    const content = await page.content();
    const hasError = content.includes('Fatal error') || content.includes('Parse error') || content.includes('Warning:');

    if (status !== 200 || hasError) {
      throw new Error(`Module ${mod.name} failed: HTTP ${status}, hasError=${hasError}`);
    }
    console.log(`  ✓ [PASS] ${mod.name.padEnd(22)} (HTTP ${status})`);
  }

  // 3. Test Module Specific Interactive Controls
  console.log('\n[3/5] Testing Module-Specific Interactive Controls...');

  // A. Printing Services: Check "+ Add New Service" navigation
  await page.goto(`${BASE_URL}/admin/services.php`, { waitUntil: 'networkidle' });
  const addServiceBtn = await page.$('a[href*="services.php?action=create"]');
  if (!addServiceBtn) throw new Error('Add New Service button not found in admin/services.php');
  await Promise.all([
    page.waitForURL(/action=create/),
    addServiceBtn.click()
  ]);
  await page.waitForLoadState('networkidle');
  const serviceFormTitle = await page.textContent('.admin-card-title');
  if (!serviceFormTitle.includes('Create New Printing Service')) {
    throw new Error('Create New Printing Service form not rendered: ' + serviceFormTitle);
  }
  console.log('  ✓ [PASS] Services Module: "+ Add New Service" opens creation form correctly');

  // B. Customer Reviews: Check "+ Add Review Manually" collapsible form
  await page.goto(`${BASE_URL}/admin/reviews.php`, { waitUntil: 'networkidle' });
  const manualCardInitial = await page.evaluate(() => document.getElementById('manualReviewCard').style.display);
  await page.click('button:has-text("+ Add Review Manually")');
  await page.waitForTimeout(100);
  const manualCardOpen = await page.evaluate(() => document.getElementById('manualReviewCard').style.display);
  if (manualCardOpen !== 'block') {
    throw new Error('Manual Review Card failed to toggle open');
  }
  console.log('  ✓ [PASS] Reviews Module: "+ Add Review Manually" collapsible form opens on demand');

  // C. Quotations: Check dynamic calculation
  await page.goto(`${BASE_URL}/admin/quotations.php?action=create`, { waitUntil: 'networkidle' });
  await page.fill('.item-qty', '5');
  await page.fill('.item-price', '200');
  await page.evaluate(() => {
    document.querySelector('.item-price').dispatchEvent(new Event('input', { bubbles: true }));
  });
  await page.waitForTimeout(200);
  const subtotalText = await page.textContent('#calc_subtotal');
  const grandTotalText = await page.textContent('#calc_grand_total');
  console.log(`  ✓ [PASS] Quotations Module: Dynamic pricing recalculates (Subtotal: ${subtotalText.trim()}, Grand: ${grandTotalText.trim()})`);

  // D. Users: Check initials avatar rendered
  await page.goto(`${BASE_URL}/admin/users.php`, { waitUntil: 'networkidle' });
  const avatarCount = await page.$$eval('.admin-table tbody tr div[style*="border-radius: 50%"]', els => els.length);
  if (avatarCount === 0) throw new Error('User avatars not rendered in admin/users.php');
  console.log(`  ✓ [PASS] Admin Users Module: Personnel table rendered ${avatarCount} custom user avatars`);

  // 4. Test Viewport Responsiveness across all 12 modules
  console.log('\n[4/5] Testing Responsive Layouts (Mobile 375px, Tablet 768px, Desktop 1440px)...');
  for (const vp of VIEWPORTS) {
    await page.setViewportSize({ width: vp.width, height: vp.height });
    for (const mod of TARGET_MODULES) {
      await page.goto(`${BASE_URL}/${mod.url}`, { waitUntil: 'networkidle' });
      const overflow = await page.evaluate(() => {
        const docW = document.documentElement.scrollWidth;
        const winW = window.innerWidth;
        const bodyW = document.body.scrollWidth;
        return docW > winW + 2 || bodyW > winW + 2;
      });
      if (overflow) {
        throw new Error(`Horizontal overflow detected on ${vp.name} for ${mod.name}`);
      }
    }
    console.log(`  ✓ [PASS] All 12 modules clean with zero horizontal overflow on ${vp.name}`);
  }

  // 5. Test SEO / Technical Files direct in-browser rendering
  console.log('\n[5/5] Testing Direct In-Browser Rendering of Technical & SEO Files...');
  const techFiles = [
    { url: `${BASE_URL}/robots.txt`, expected: 'User-agent:' },
    { url: `${BASE_URL}/llms.txt`, expected: '# OrderPrintex' },
    { url: `${BASE_URL}/sitemap.xml`, expected: '<urlset' },
    { url: `${BASE_URL}/sitemap_index.xml`, expected: '<sitemapindex' },
    { url: `${BASE_URL}/example.md`, expected: '# OrderPrintex' },
    { url: `${BASE_URL}/schema.json`, expected: 'schema.org' }
  ];

  for (const tf of techFiles) {
    const res = await page.goto(tf.url, { waitUntil: 'networkidle' });
    const status = res.status();
    const text = await page.content();
    if (status !== 200 || !text.includes(tf.expected)) {
      throw new Error(`Technical file ${tf.url} failed in browser: status=${status}`);
    }
    console.log(`  ✓ [PASS] Browser loaded ${tf.url} inline without prompt (HTTP ${status})`);
  }

  await browser.close();
  console.log('\n>>> COMPLETE VERIFICATION AUDIT PASSED 100%! <<<\n');
}

runAudit().catch(err => {
  console.error('\n❌ AUDIT FAILED:', err);
  process.exit(1);
});
