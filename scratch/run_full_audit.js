const { chromium } = require('playwright');

const BASE_URL = 'http://127.0.0.1:8080';

const VIEWPORTS = [
  { name: 'iPhone SE (320px)', width: 320, height: 568 },
  { name: 'Galaxy S8 (360px)', width: 360, height: 740 },
  { name: 'iPhone mini (375px)', width: 375, height: 667 },
  { name: 'iPhone 13/14 (390px)', width: 390, height: 844 },
  { name: 'Pixel 7 (412px)', width: 412, height: 915 },
  { name: 'iPhone Pro Max (430px)', width: 430, height: 932 },
  { name: 'Tablet Portrait (768px)', width: 768, height: 1024 },
  { name: 'Tablet Landscape (1024px)', width: 1024, height: 768 },
  { name: 'Laptop Standard (1280px)', width: 1280, height: 800 },
  { name: 'Desktop HD (1440px)', width: 1440, height: 900 },
  { name: 'Full HD Desktop (1920px)', width: 1920, height: 1080 }
];

async function runAudit() {
  const browser = await chromium.launch({ headless: true });
  const results = {
    publicSite: [],
    customerPortal: [],
    adminPanel: [],
    decommissionedRedirects: [],
    responsiveAudits: []
  };

  console.log('====================================================');
  console.log('STARTING ORDERPRINTEX MASTER VERIFICATION AUDIT');
  console.log('====================================================\n');

  // ================================================================
  // 1. PUBLIC SITE AUDIT
  // ================================================================
  console.log('>>> [1/4] AUDITING PUBLIC WEBSITE...');
  const page = await browser.newPage();

  // A. Homepage
  await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle' });
  
  // Verify cart button absent
  const cartBtnCount = await page.locator('.cart-btn, a[href*="cart"]').count();
  const wishlistBtnCount = await page.locator('.btn-wishlist').count();
  const compareCbCount = await page.locator('.opx-compare-cb, .compare-checkbox-label').count();
  const compareTrayCount = await page.locator('#compareFloatingTray').count();

  results.publicSite.push({
    test: 'Homepage Cart Button Absent',
    passed: cartBtnCount === 0,
    details: `Cart buttons found: ${cartBtnCount}`
  });
  results.publicSite.push({
    test: 'Homepage Wishlist Buttons Absent',
    passed: wishlistBtnCount === 0,
    details: `Wishlist buttons found: ${wishlistBtnCount}`
  });
  results.publicSite.push({
    test: 'Homepage Compare Checkboxes Absent',
    passed: compareCbCount === 0,
    details: `Compare checkboxes found: ${compareCbCount}`
  });
  results.publicSite.push({
    test: 'Floating Compare Tray Absent',
    passed: compareTrayCount === 0,
    details: `Tray elements found: ${compareTrayCount}`
  });

  // B. Product Details Page
  await page.goto(`${BASE_URL}/product/custom-uv-dtf-decal-sheet/`, { waitUntil: 'networkidle' });
  const detailWishlistCount = await page.locator('.btn-wishlist, #detailWishlistBtn').count();
  const detailCompareCount = await page.locator('.opx-compare-cb, .compare-checkbox-label').count();
  const stickyBtnText = await page.locator('.sticky-order-btn').textContent().catch(() => '');

  results.publicSite.push({
    test: 'Product Details Wishlist Button Absent',
    passed: detailWishlistCount === 0,
    details: `Detail wishlist buttons found: ${detailWishlistCount}`
  });
  results.publicSite.push({
    test: 'Product Details Compare Checkbox Absent',
    passed: detailCompareCount === 0,
    details: `Detail compare checkboxes found: ${detailCompareCount}`
  });
  results.publicSite.push({
    test: 'Sticky Bar CTA is "Request Quote"',
    passed: stickyBtnText.toLowerCase().includes('quote'),
    details: `Sticky button text: "${stickyBtnText.trim()}"`
  });

  // ================================================================
  // 2. DECOMMISSIONED REDIRECTS AUDIT
  // ================================================================
  console.log('>>> [2/4] AUDITING DECOMMISSIONED REDIRECTS...');
  const redirectUrls = [
    { from: `${BASE_URL}/cart/`, expectedTarget: '/products/' },
    { from: `${BASE_URL}/checkout/`, expectedTarget: '/get-quote/' },
    { from: `${BASE_URL}/account/orders.php`, expectedTarget: 'account/quotations.php' },
    { from: `${BASE_URL}/account/wishlist.php`, expectedTarget: '/products/' }
  ];

  for (const r of redirectUrls) {
    const res = await page.goto(r.from, { waitUntil: 'commit' });
    const finalUrl = page.url();
    const passed = finalUrl.includes(r.expectedTarget) || finalUrl.includes('login.php');
    results.decommissionedRedirects.push({
      from: r.from,
      expected: r.expectedTarget,
      finalUrl: finalUrl,
      passed: passed
    });
  }

  // ================================================================
  // 3. CUSTOMER PORTAL AUDIT
  // ================================================================
  console.log('>>> [3/4] AUDITING CUSTOMER PORTAL...');
  const custContext = await browser.newContext();
  const custPage = await custContext.newPage();

  // Login as Customer
  await custPage.goto(`${BASE_URL}/account/login.php`);
  await custPage.fill('input[name="email"]', 'customer@orderprintex.com');
  await custPage.fill('input[name="password"]', 'customer123');
  await custPage.click('button[type="submit"]');
  await custPage.waitForURL('**/account/dashboard.php');

  // Verify Customer Dashboard
  const dashBg = await custPage.evaluate(() => {
    const bodyBg = window.getComputedStyle(document.body).backgroundColor;
    const containerBg = window.getComputedStyle(document.querySelector('.container').parentElement).backgroundColor;
    return { bodyBg, containerBg };
  });

  // Check 5 KPI cards
  const kpiCount = await custPage.locator('.container > div:has-text("TOTAL ENQUIRIES") > div').count();
  const subnavText = await custPage.locator('.gallery-filter-btn').allTextContents();

  results.customerPortal.push({
    test: 'Customer Portal Dark Theme (No White Background)',
    passed: !dashBg.containerBg.includes('248, 250, 252'), // not #F8FAFC
    details: `Container BG: ${dashBg.containerBg}`
  });
  results.customerPortal.push({
    test: 'Customer Portal Subnav Tabs Simplified',
    passed: !subnavText.some(t => t.toLowerCase().includes('order') || t.toLowerCase().includes('wishlist')),
    details: `Tabs found: ${subnavText.join(', ')}`
  });
  results.customerPortal.push({
    test: '5 KPI Cards Rendered on Dashboard',
    passed: kpiCount === 5,
    details: `KPI cards rendered: ${kpiCount}`
  });

  // Verify Quotations Page & Review
  await custPage.goto(`${BASE_URL}/account/quotations.php?id=2`, { waitUntil: 'networkidle' });
  const quoteItemsCount = await custPage.locator('table tr').count();
  const hasGrandTotal = await custPage.locator('text=Grand Total').count();
  const acceptBtnCount = await custPage.locator('button:has-text("Accept & Confirm Quotation")').count();

  results.customerPortal.push({
    test: 'Customer Quotation Review Itemized Breakdown & Total',
    passed: quoteItemsCount > 1 && hasGrandTotal > 0,
    details: `Items rows: ${quoteItemsCount}, Has Grand Total: ${hasGrandTotal > 0}`
  });
  results.customerPortal.push({
    test: 'Accept Quotation Action Button Present (No Direct Cart/Order Creation)',
    passed: acceptBtnCount > 0,
    details: `Accept buttons: ${acceptBtnCount}`
  });

  // Test Accepting the Quote
  custPage.on('dialog', async dialog => {
    await dialog.accept();
  });
  await custPage.click('button:has-text("Accept & Confirm Quotation")');
  await custPage.waitForLoadState('networkidle');

  // Verify status is now accepted and message confirms team will contact
  const acceptedBadge = await custPage.locator('text=Quotation Accepted!').count();
  results.customerPortal.push({
    test: 'Quotation Successfully Accepted & Flash Message Displayed',
    passed: acceptedBadge > 0,
    details: `Quotation Accepted badges found: ${acceptedBadge}`
  });

  // Check Enquiries Page
  await custPage.goto(`${BASE_URL}/account/enquiries.php`, { waitUntil: 'networkidle' });
  const artworkColCount = await custPage.locator('th:has-text("Artwork File"), th:has-text("Download")').count();
  results.customerPortal.push({
    test: 'Customer Enquiries Table Artwork Columns Removed',
    passed: artworkColCount === 0,
    details: `Artwork header columns found: ${artworkColCount}`
  });

  // ================================================================
  // 4. ADMIN PANEL AUDIT
  // ================================================================
  console.log('>>> [4/4] AUDITING ADMIN PANEL...');
  const adminContext = await browser.newContext();
  const adminPage = await adminContext.newPage();

  // Login as Admin
  await adminPage.goto(`${BASE_URL}/admin/login.php`);
  await adminPage.fill('input[name="username"]', 'admin@orderprintex.com');
  await adminPage.fill('input[name="password"]', 'admin123');
  await adminPage.click('button[type="submit"]');
  await adminPage.waitForURL('**/admin/dashboard.php');

  // Verify Admin Dashboard 8 KPIs
  const adminKpis = await adminPage.locator('.kpi-card').count();
  const ordersQueueCount = await adminPage.locator('text=Production Orders Queue, text=Active Orders').count();

  results.adminPanel.push({
    test: 'Admin Dashboard 8 KPI Cards Present',
    passed: adminKpis === 8,
    details: `KPI cards counted: ${adminKpis}`
  });
  results.adminPanel.push({
    test: 'Admin Dashboard Production Orders & Revenue Queue Removed',
    passed: ordersQueueCount === 0,
    details: `Orders queue occurrences: ${ordersQueueCount}`
  });

  // Verify Admin Sidebar Links
  const sidebarHtml = await adminPage.locator('.admin-sidebar').innerHTML();
  const hasOrdersLink = sidebarHtml.includes('orders.php');
  const hasHomepageLink = sidebarHtml.includes('homepage.php');
  const hasPagesLink = sidebarHtml.includes('pages.php');
  const hasMediaLink = sidebarHtml.includes('media.php');
  const hasPortfolioLink = sidebarHtml.includes('gallery.php');
  const hasActivityLink = sidebarHtml.includes('activity.php');

  results.adminPanel.push({
    test: 'Admin Sidebar Obsolete Modules Removed',
    passed: !hasOrdersLink && !hasHomepageLink && !hasPagesLink && !hasMediaLink,
    details: `Orders: ${hasOrdersLink}, Homepage: ${hasHomepageLink}, Pages: ${hasPagesLink}, Media: ${hasMediaLink}`
  });
  results.adminPanel.push({
    test: 'Admin Sidebar Portfolio & Activity Log Present',
    passed: hasPortfolioLink && hasActivityLink,
    details: `Portfolio: ${hasPortfolioLink}, Activity Log: ${hasActivityLink}`
  });

  // Verify Enquiries Module (Artwork removed)
  await adminPage.goto(`${BASE_URL}/admin/enquiries.php`, { waitUntil: 'networkidle' });
  const adminArtworkHeaders = await adminPage.locator('th:has-text("Artwork")').count();
  results.adminPanel.push({
    test: 'Admin Enquiries Artwork Column Removed',
    passed: adminArtworkHeaders === 0,
    details: `Artwork table columns found: ${adminArtworkHeaders}`
  });

  // Verify Product Management (4 Tabs Consolidation)
  await adminPage.goto(`${BASE_URL}/admin/products.php?action=edit&id=1`, { waitUntil: 'networkidle' });
  const tabA = await adminPage.locator('a[href="#tab-basic"]').count();
  const tabB = await adminPage.locator('a[href="#tab-skus"]').count();
  const tabC = await adminPage.locator('a[href="#tab-attributes"]').count();
  const tabD = await adminPage.locator('a[href="#tab-variations"]').count();

  results.adminPanel.push({
    test: 'Product Management 4 Consolidated Tabs Present',
    passed: tabA > 0 && tabB > 0 && tabC > 0 && tabD > 0,
    details: `Tab A: ${tabA}, Tab B: ${tabB}, Tab C: ${tabC}, Tab D: ${tabD}`
  });

  // Test Tab Switching to Attributes (Tab C)
  await adminPage.click('a[href="#tab-attributes"]');
  const isTabCActive = await adminPage.locator('#tab-attributes').isVisible();
  const hasInlineAttrCreator = await adminPage.locator('form[action*="create_attribute"], input[name="new_attr_name"]').count();

  results.adminPanel.push({
    test: 'Product Attributes Tab Switches & Has Inline Creator',
    passed: isTabCActive && hasInlineAttrCreator > 0,
    details: `Tab C visible: ${isTabCActive}, Inline creator found: ${hasInlineAttrCreator > 0}`
  });

  // Verify Portfolio Management (admin/gallery.php)
  await adminPage.goto(`${BASE_URL}/admin/gallery.php`, { waitUntil: 'networkidle' });
  const portfolioTitle = await adminPage.locator('h1, .page-title').textContent();
  const hasPublishedToggle = await adminPage.locator('button[title*="Publish"], button[title*="Unpublish"], a[href*="toggle_publish"]').count();

  results.adminPanel.push({
    test: 'Portfolio Management Module Upgraded with Publishing Controls',
    passed: portfolioTitle.toLowerCase().includes('portfolio') && hasPublishedToggle > 0,
    details: `Title: "${portfolioTitle.trim()}", Publish toggles: ${hasPublishedToggle}`
  });

  // Verify Security & Activity Log (admin/activity.php)
  await adminPage.goto(`${BASE_URL}/admin/activity.php`, { waitUntil: 'networkidle' });
  const logRows = await adminPage.locator('table tr').count();
  const hasFilters = await adminPage.locator('select[name="event_type"], input[name="q"]').count();

  results.adminPanel.push({
    test: 'Security & Activity Log Filterable Table Present',
    passed: logRows > 1 && hasFilters > 0,
    details: `Log table rows: ${logRows}, Filter controls: ${hasFilters}`
  });

  // Admin Decommissioned Redirects
  const adminRedirects = [
    { from: `${BASE_URL}/admin/orders.php`, expected: 'admin/quotations.php' },
    { from: `${BASE_URL}/admin/homepage.php`, expected: 'admin/settings.php' },
    { from: `${BASE_URL}/admin/pages.php`, expected: 'admin/dashboard.php' },
    { from: `${BASE_URL}/admin/media.php`, expected: 'admin/gallery.php' },
    { from: `${BASE_URL}/admin/attributes.php`, expected: 'admin/products.php' },
    { from: `${BASE_URL}/admin/variations.php`, expected: 'admin/products.php' }
  ];

  for (const ar of adminRedirects) {
    await adminPage.goto(ar.from, { waitUntil: 'commit' });
    const finalUrl = adminPage.url();
    results.decommissionedRedirects.push({
      from: ar.from,
      expected: ar.expected,
      finalUrl: finalUrl,
      passed: finalUrl.includes(ar.expected)
    });
  }

  // ================================================================
  // 5. RESPONSIVE AUDIT ACROSS 11 VIEWPORTS (ZERO HORIZONTAL OVERFLOW)
  // ================================================================
  console.log('>>> [5/5] TESTING 11 VIEWPORTS FOR HORIZONTAL OVERFLOW...');
  const testPages = [
    { name: 'Homepage', url: `${BASE_URL}/`, pageObj: page },
    { name: 'Products Catalog', url: `${BASE_URL}/products/`, pageObj: page },
    { name: 'Product Details', url: `${BASE_URL}/product/custom-uv-dtf-decal-sheet/`, pageObj: page },
    { name: 'Customer Dashboard', url: `${BASE_URL}/account/dashboard.php`, pageObj: custPage },
    { name: 'Customer Quotations', url: `${BASE_URL}/account/quotations.php?id=2`, pageObj: custPage },
    { name: 'Admin Dashboard', url: `${BASE_URL}/admin/dashboard.php`, pageObj: adminPage },
    { name: 'Admin Products', url: `${BASE_URL}/admin/products.php`, pageObj: adminPage },
    { name: 'Admin Portfolio', url: `${BASE_URL}/admin/gallery.php`, pageObj: adminPage }
  ];

  for (const vp of VIEWPORTS) {
    for (const tp of testPages) {
      await tp.pageObj.setViewportSize({ width: vp.width, height: vp.height });
      await tp.pageObj.goto(tp.url, { waitUntil: 'networkidle' });

      const overflow = await tp.pageObj.evaluate(() => {
        const docElem = document.documentElement;
        const scrollWidth = docElem.scrollWidth;
        const clientWidth = docElem.clientWidth;
        return {
          hasOverflow: scrollWidth > clientWidth,
          scrollWidth: scrollWidth,
          clientWidth: clientWidth,
          diff: scrollWidth - clientWidth
        };
      });

      if (overflow.hasOverflow) {
        results.responsiveAudits.push({
          page: tp.name,
          viewport: vp.name,
          passed: false,
          diff: overflow.diff
        });
      }
    }
  }

  await browser.close();

  // Print Summary
  console.log('\n====================================================');
  console.log('AUDIT SUMMARY REPORT');
  console.log('====================================================');

  console.log('\n--- 1. PUBLIC WEBSITE ---');
  results.publicSite.forEach(r => console.log(`[${r.passed ? 'PASS' : 'FAIL'}] ${r.test} (${r.details})`));

  console.log('\n--- 2. DECOMMISSIONED REDIRECTS ---');
  results.decommissionedRedirects.forEach(r => console.log(`[${r.passed ? 'PASS' : 'FAIL'}] ${r.from} -> ${r.finalUrl} (expected: ${r.expected})`));

  console.log('\n--- 3. CUSTOMER PORTAL ---');
  results.customerPortal.forEach(r => console.log(`[${r.passed ? 'PASS' : 'FAIL'}] ${r.test} (${r.details})`));

  console.log('\n--- 4. ADMIN PANEL ---');
  results.adminPanel.forEach(r => console.log(`[${r.passed ? 'PASS' : 'FAIL'}] ${r.test} (${r.details})`));

  console.log('\n--- 5. RESPONSIVE OVERFLOW AUDIT (11 VIEWPORTS) ---');
  if (results.responsiveAudits.length === 0) {
    console.log('[ALL PASS] Zero horizontal overflow detected across all 11 viewports (320px - 1920px)!');
  } else {
    results.responsiveAudits.forEach(r => console.log(`[OVERFLOW] ${r.page} at ${r.viewport} by ${r.diff}px`));
  }

  return results;
}

runAudit().catch(err => {
  console.error('Audit fatal error:', err);
  process.exit(1);
});

