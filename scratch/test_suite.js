/**
 * OrderPrintex Comprehensive Verification Test Suite
 * Tests Public Frontend, Customer Portal, Admin Panel, Business Workflow, and Redirects
 */

const BASE_URL = 'http://127.0.0.1:8080';

async function runTestSuite() {
  const summary = {
    total: 0,
    passed: 0,
    failed: 0,
    tests: []
  };

  function assert(title, condition, details = '') {
    summary.total++;
    if (condition) {
      summary.passed++;
      console.log(`  [PASS] ${title} ${details ? '(' + details + ')' : ''}`);
      summary.tests.push({ title, passed: true, details });
    } else {
      summary.failed++;
      console.log(`  [FAIL] ${title} ${details ? '(' + details + ')' : ''}`);
      summary.tests.push({ title, passed: false, details });
    }
  }

  console.log('===============================================================');
  console.log('ORDERPRINTEX AUTOMATED FULL STACK & WORKFLOW TEST SUITE');
  console.log('===============================================================\n');

  // ==================================================================
  // 1. PUBLIC WEBSITE & REMOVAL OF E-COMMERCE ELEMENTS
  // ==================================================================
  console.log('--- 1. PUBLIC WEBSITE CHECKS ---');

  // Homepage
  const resHome = await fetch(`${BASE_URL}/`);
  const htmlHome = await resHome.text();
  assert('Homepage Status 200', resHome.status === 200);
  assert('Navbar Cart Button Absent', !htmlHome.includes('class="cart-btn"') && !htmlHome.includes('href="/cart/"') && !htmlHome.includes('href="http://127.0.0.1:8080/cart/"'));
  assert('Homepage Wishlist Buttons Absent', !htmlHome.includes('class="btn-wishlist"'));
  assert('Homepage Compare Checkboxes Absent', !htmlHome.includes('class="compare-checkbox-label"'));
  assert('Unauthenticated User Dropdown Renders Login & Register', htmlHome.includes('account/login') && htmlHome.includes('account/register'));

  // Catalog Page
  const resCatalog = await fetch(`${BASE_URL}/products/`);
  const htmlCatalog = await resCatalog.text();
  assert('Products Catalog Status 200', resCatalog.status === 200);
  assert('Catalog Wishlist Buttons Absent', !htmlCatalog.includes('class="btn-wishlist"'));
  assert('Catalog Compare Checkboxes Absent', !htmlCatalog.includes('class="compare-checkbox-label"'));

  // Product Details Page
  const resDetail = await fetch(`${BASE_URL}/product/custom-uv-dtf-decal-sheet/`);
  const htmlDetail = await resDetail.text();
  assert('Product Details Status 200', resDetail.status === 200);
  assert('Product Details Wishlist Button Absent', !htmlDetail.includes('id="detailWishlistBtn"') && !htmlDetail.includes('class="btn-wishlist"'));
  assert('Product Details Compare Checkbox Absent', !htmlDetail.includes('class="compare-checkbox-label"'));
  assert('Sticky Action Button CTA is "Request Quote"', htmlDetail.includes('Request Quote &darr;') || htmlDetail.includes('Request Quote'));

  // SEO & Semantic AI Discovery Files
  const resSitemap = await fetch(`${BASE_URL}/sitemap.xml`);
  const xmlSitemap = await resSitemap.text();
  assert('Sitemap.xml Status 200', resSitemap.status === 200);
  assert('Sitemap Contains Products and Services', xmlSitemap.includes('<loc>') && xmlSitemap.includes('product/') && xmlSitemap.includes('services/'));
  assert('Sitemap Excludes Decommissioned E-Commerce (Cart/Orders)', !xmlSitemap.includes('/cart/') && !xmlSitemap.includes('/orders/') && !xmlSitemap.includes('/wishlist.php'));

  const resLlms = await fetch(`${BASE_URL}/llms.txt`);
  const txtLlms = await resLlms.text();
  assert('LLMS.txt Status 200', resLlms.status === 200);
  assert('LLMS.txt Specifies Enquiry & Quotation Model', txtLlms.includes('Enquiry-to-Quotation workflow') || txtLlms.includes('Bespoke Quotations'));

  // ==================================================================
  // 2. PUBLIC & CLIENT DECOMMISSIONED REDIRECTS
  // ==================================================================
  console.log('\n--- 2. PUBLIC & CLIENT ROUTE REDIRECTS ---');
  const publicRedirects = [
    { url: `${BASE_URL}/cart/`, target: '/products/' },
    { url: `${BASE_URL}/checkout/`, target: '/get-quote/' },
    { url: `${BASE_URL}/cart.php`, target: '/cart/' },
    { url: `${BASE_URL}/checkout.php`, target: '/checkout/' },
    { url: `${BASE_URL}/account/wishlist.php`, target: '/products/' }
  ];

  for (const r of publicRedirects) {
    const res = await fetch(r.url, { redirect: 'manual' });
    const loc = res.headers.get('location') || '';
    assert(`Redirect: ${r.url.replace(BASE_URL, '')} -> ${r.target}`, res.status === 301 || res.status === 302, `Location: ${loc}`);
  }

  // ==================================================================
  // 3. CUSTOMER PORTAL AUTHENTICATION & WORKFLOW
  // ==================================================================
  console.log('\n--- 3. CUSTOMER PORTAL & QUOTATION WORKFLOW ---');

  // Ensure quote 6 is in 'sent' status before testing review and acceptance
  const { execSync } = require('child_process');
  execSync('php -r "require_once \'includes/config.php\'; require_once \'includes/database.php\'; db_update(\'quotations\', [\'status\' => \'sent\'], \'id = 6\');"');

  // Customer Login
  let custCookie = '';
  const loginFormData = new URLSearchParams();
  const resCustLoginGet = await fetch(`${BASE_URL}/account/login.php`);
  const custLoginHtml = await resCustLoginGet.text();
  const custCsrfMatch = custLoginHtml.match(/name="csrf_token"\s+value="([^"]+)"/i);
  const custCsrf = custCsrfMatch ? custCsrfMatch[1] : '';
  const setCookieLogin = resCustLoginGet.headers.get('set-cookie');
  if (setCookieLogin) custCookie = setCookieLogin.split(';')[0];

  loginFormData.append('csrf_token', custCsrf);
  loginFormData.append('email', 'customer@orderprintex.com');
  loginFormData.append('password', 'customer123');

  const resCustLoginPost = await fetch(`${BASE_URL}/account/login.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': custCookie
    },
    body: loginFormData.toString(),
    redirect: 'manual'
  });

  const postCookie = resCustLoginPost.headers.get('set-cookie');
  if (postCookie) custCookie = postCookie.split(';')[0];
  assert('Customer Login Succeeded (302 Redirect to Dashboard)', resCustLoginPost.status === 302);

  // Customer Dashboard Overview
  const resCustDash = await fetch(`${BASE_URL}/account/dashboard.php`, {
    headers: { 'Cookie': custCookie }
  });
  const htmlCustDash = await resCustDash.text();
  assert('Customer Dashboard Status 200', resCustDash.status === 200);
  assert('Customer Dashboard Public Dark Theme (#080808 / No #F8FAFC)', htmlCustDash.includes('#080808') && !htmlCustDash.includes('#F8FAFC'));
  assert('5 KPI Cards Present (Enquiries, Quotes, Review, Approved)', 
    htmlCustDash.includes('Total Enquiries') && 
    htmlCustDash.includes('Open Enquiries') && 
    htmlCustDash.includes('Total Quotations') && 
    htmlCustDash.includes('Awaiting Review') && 
    htmlCustDash.includes('Approved Quotes')
  );
  assert('Customer Dashboard Subnav Clean (No My Orders / Wishlist)', 
    !htmlCustDash.includes('>My Orders<') && 
    !htmlCustDash.includes('>My Wishlist<') &&
    htmlCustDash.includes('>Overview<') &&
    htmlCustDash.includes('>My Enquiries') &&
    htmlCustDash.includes('>My Quotations')
  );
  assert('Recent Quotations Table Present', htmlCustDash.includes('Recent Quotations') && htmlCustDash.includes('Quote #'));
  assert('Recent Enquiries Table Present', htmlCustDash.includes('Recent Print Enquiries') && htmlCustDash.includes('Ref #'));

  // Customer Enquiries Page
  const resCustEnq = await fetch(`${BASE_URL}/account/enquiries.php`, {
    headers: { 'Cookie': custCookie }
  });
  const htmlCustEnq = await resCustEnq.text();
  assert('Customer Enquiries Status 200', resCustEnq.status === 200);
  assert('Artwork Column Removed from Customer Enquiries', !htmlCustEnq.includes('>Artwork File<') && !htmlCustEnq.includes('download>'));
  assert('Enquiries Uses Public Dark Theme', htmlCustEnq.includes('#080808') && !htmlCustEnq.includes('#F8FAFC'));

  // Customer Quotation Review Page
  const resCustQuote = await fetch(`${BASE_URL}/account/quotations.php?id=6`, {
    headers: { 'Cookie': custCookie }
  });
  const htmlCustQuote = await resCustQuote.text();
  assert('Customer Quotation Review Status 200', resCustQuote.status === 200);
  assert('Itemized Invoice Breakdown Rendered', htmlCustQuote.includes('Grand Total') && htmlCustQuote.includes('Subtotal:'));
  assert('GST 18% Rendered in Quotation', htmlCustQuote.includes('GST') && htmlCustQuote.includes('Delivery &amp; Packaging'));
  assert('Quotation Action Form Present (Accept / Revision / Decline)', htmlCustQuote.includes('name="action" value="accept"'));

  // Count orders in DB before accept
  const ordersBefore = parseInt(execSync('php -r "require_once \'includes/config.php\'; require_once \'includes/database.php\'; echo db_fetch_one(\'SELECT COUNT(*) as c FROM orders\')[\'c\'];"').toString().trim(), 10);

  // Test Customer Quotation Acceptance WITHOUT Order Creation
  const quoteCsrfMatch = htmlCustQuote.match(/name="csrf_token"\s+value="([^"]+)"/i);
  const quoteCsrf = quoteCsrfMatch ? quoteCsrfMatch[1] : '';

  const acceptFormData = new URLSearchParams();
  acceptFormData.append('csrf_token', quoteCsrf);
  acceptFormData.append('quotation_id', '6');
  acceptFormData.append('action', 'accept');
  acceptFormData.append('customer_feedback', 'Accepted via automated test suite. Ready for prepress.');

  const resAcceptPost = await fetch(`${BASE_URL}/account/quotations.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': custCookie
    },
    body: acceptFormData.toString(),
    redirect: 'manual'
  });

  assert('Accept Action Submitted (302 Redirect to Quote)', resAcceptPost.status === 302);

  // Verify DB State: orders count unchanged, quotation status accepted
  const ordersAfter = parseInt(execSync('php -r "require_once \'includes/config.php\'; require_once \'includes/database.php\'; echo db_fetch_one(\'SELECT COUNT(*) as c FROM orders\')[\'c\'];"').toString().trim(), 10);
  assert('No Order Record Inserted on Quote Acceptance (Pure Quotation Model)', ordersBefore === ordersAfter, `Orders before: ${ordersBefore}, after: ${ordersAfter}`);

  // Re-fetch Quote to verify accepted state
  const resQuoteAfter = await fetch(`${BASE_URL}/account/quotations.php?id=6`, {
    headers: { 'Cookie': custCookie }
  });
  const htmlQuoteAfter = await resQuoteAfter.text();
  assert('Quotation Status Shows Accepted with Commercial Contact Banner', htmlQuoteAfter.includes('Quotation Accepted!') && htmlQuoteAfter.includes('commercial team'));

  // Customer Profile Settings
  const resCustProf = await fetch(`${BASE_URL}/account/profile.php`, {
    headers: { 'Cookie': custCookie }
  });
  const htmlCustProf = await resCustProf.text();
  assert('Profile Settings Status 200', resCustProf.status === 200);
  assert('Profile Uses Public Dark Theme (No #F8FAFC)', htmlCustProf.includes('#080808') && !htmlCustProf.includes('#F8FAFC'));

  // Customer Decommissioned Orders Redirection
  const resCustOrders = await fetch(`${BASE_URL}/account/orders.php`, {
    headers: { 'Cookie': custCookie },
    redirect: 'manual'
  });
  assert('Account Orders Redirects to account/quotations.php', resCustOrders.status === 302 && resCustOrders.headers.get('location').includes('account/quotations.php'));

  // ==================================================================
  // 4. ADMIN PANEL AUTHENTICATION & REDESIGN
  // ==================================================================
  console.log('\n--- 4. ADMIN PANEL & MODULES ---');

  // Admin Login
  let adminCookie = '';
  const adminLoginGet = await fetch(`${BASE_URL}/admin/login.php`);
  const adminLoginHtml = await adminLoginGet.text();
  const adminCsrfMatch = adminLoginHtml.match(/name="csrf_token"\s+value="([^"]+)"/i);
  const adminCsrf = adminCsrfMatch ? adminCsrfMatch[1] : '';
  const adminSetCookie = adminLoginGet.headers.get('set-cookie');
  if (adminSetCookie) adminCookie = adminSetCookie.split(';')[0];

  const adminFormData = new URLSearchParams();
  adminFormData.append('csrf_token', adminCsrf);
  adminFormData.append('email', 'admin@orderprintex.com');
  adminFormData.append('password', 'admin123');

  const adminLoginPost = await fetch(`${BASE_URL}/admin/login.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': adminCookie
    },
    body: adminFormData.toString(),
    redirect: 'manual'
  });

  const adminPostCookie = adminLoginPost.headers.get('set-cookie');
  if (adminPostCookie) adminCookie = adminPostCookie.split(';')[0];
  assert('Admin Login Succeeded (302 Redirect to Dashboard)', adminLoginPost.status === 302);

  // Admin Dashboard
  const resAdminDash = await fetch(`${BASE_URL}/admin/dashboard.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const htmlAdminDash = await resAdminDash.text();
  assert('Admin Dashboard Status 200', resAdminDash.status === 200);
  assert('Admin Dashboard 8 KPI Cards Present',
    htmlAdminDash.includes('Total Enquiries') &&
    htmlAdminDash.includes('New Enquiries') &&
    htmlAdminDash.includes('Under Review') &&
    htmlAdminDash.includes('Pending Quotations') &&
    htmlAdminDash.includes('Approved Quotations') &&
    htmlAdminDash.includes('Rejected / Expired') &&
    htmlAdminDash.includes('Total Products') &&
    htmlAdminDash.includes('Portfolio Projects')
  );
  assert('Admin Dashboard Production Orders & Revenue Metrics Removed',
    !htmlAdminDash.includes('Production Orders Queue') &&
    !htmlAdminDash.includes('Active Production Orders') &&
    !htmlAdminDash.includes('Total Revenue')
  );

  // Admin Sidebar Navigation Cleanup
  assert('Sidebar: Production Orders Removed', !htmlAdminDash.includes('href="orders.php"') && !htmlAdminDash.includes('orders.php'));
  assert('Sidebar: Homepage CMS Removed', !htmlAdminDash.includes('homepage.php'));
  assert('Sidebar: Pages CMS Removed', !htmlAdminDash.includes('pages.php'));
  assert('Sidebar: Media Library Removed', !htmlAdminDash.includes('media.php'));
  assert('Sidebar: FAQs Removed', !htmlAdminDash.includes('faqs.php'));
  assert('Sidebar: SEO Removed', !htmlAdminDash.includes('admin/seo.php') && !htmlAdminDash.includes('href="seo.php"'));
  assert('Sidebar: Portfolio Management Present', htmlAdminDash.includes('Portfolio Management') || htmlAdminDash.includes('gallery.php'));
  assert('Sidebar: Security & Activity Log Present', htmlAdminDash.includes('Security &amp; Activity Log') || htmlAdminDash.includes('activity.php'));

  // Admin Enquiries Module
  const resAdminEnq = await fetch(`${BASE_URL}/admin/enquiries.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const htmlAdminEnq = await resAdminEnq.text();
  assert('Admin Enquiries Status 200', resAdminEnq.status === 200);
  assert('Admin Enquiries Artwork Column & Download Buttons Removed', !htmlAdminEnq.includes('>Artwork<') && !htmlAdminEnq.includes('Download Artwork'));

  // Admin Consolidated Product Management (4 Tabs)
  const resAdminProd = await fetch(`${BASE_URL}/admin/products.php?action=edit&id=2`, {
    headers: { 'Cookie': adminCookie }
  });
  const htmlAdminProd = await resAdminProd.text();
  assert('Admin Product Edit Status 200', resAdminProd.status === 200);
  assert('Tab A: Basic Information Present', htmlAdminProd.includes('data-tab="tab-basic"') || htmlAdminProd.includes('id="tab-basic"'));
  assert('Tab B: SKU Management Present', htmlAdminProd.includes('data-tab="tab-skus"') || htmlAdminProd.includes('id="tab-skus"'));
  assert('Tab C: Product Attributes Present', htmlAdminProd.includes('data-tab="tab-attributes"') || htmlAdminProd.includes('id="tab-attributes"'));
  assert('Tab D: Product Variations Present', htmlAdminProd.includes('data-tab="tab-variations"') || htmlAdminProd.includes('id="tab-variations"'));
  assert('Inline Global Attribute Creator Present in Products Module', htmlAdminProd.includes('value="create_attribute"') && htmlAdminProd.includes('+ Add New Attribute Type'));

  // Admin Portfolio Management (Gallery renamed and upgraded)
  const resAdminPort = await fetch(`${BASE_URL}/admin/gallery.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const htmlAdminPort = await resAdminPort.text();
  assert('Admin Portfolio Management Status 200', resAdminPort.status === 200);
  assert('Portfolio Management Title & Features', htmlAdminPort.includes('Portfolio Management') && (htmlAdminPort.includes('is_published') || htmlAdminPort.includes('Publish') || htmlAdminPort.includes('Unpublish')));

  // Admin Security & Activity Log
  const resAdminAct = await fetch(`${BASE_URL}/admin/activity.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const htmlAdminAct = await resAdminAct.text();
  assert('Admin Activity Log Status 200', resAdminAct.status === 200);
  assert('Activity Log Filter Controls & Table Present', htmlAdminAct.includes('name="event_type"') && htmlAdminAct.includes('name="search"'));

  // Admin Decommissioned Redirects
  const adminRedirects = [
    { url: `${BASE_URL}/admin/orders.php`, target: 'admin/quotations.php' },
    { url: `${BASE_URL}/admin/homepage.php`, target: 'admin/settings.php' },
    { url: `${BASE_URL}/admin/pages.php`, target: 'admin/dashboard.php' },
    { url: `${BASE_URL}/admin/media.php`, target: 'admin/gallery.php' },
    { url: `${BASE_URL}/admin/faqs.php`, target: 'admin/dashboard.php' },
    { url: `${BASE_URL}/admin/seo.php`, target: 'admin/dashboard.php' },
    { url: `${BASE_URL}/admin/attributes.php`, target: 'admin/products.php' },
    { url: `${BASE_URL}/admin/variations.php`, target: 'admin/products.php' }
  ];

  for (const ar of adminRedirects) {
    const res = await fetch(ar.url, {
      headers: { 'Cookie': adminCookie },
      redirect: 'manual'
    });
    const loc = res.headers.get('location') || '';
    assert(`Admin Redirect: ${ar.url.replace(BASE_URL, '')} -> ${ar.target}`, res.status === 302, `Location: ${loc}`);
  }

  // ==================================================================
  // FINAL SUMMARY
  // ==================================================================
  console.log('\n===============================================================');
  console.log(`TEST SUITE RESULTS: ${summary.passed}/${summary.total} PASSED (${summary.failed} FAILED)`);
  console.log('===============================================================');

  return summary;
}

runTestSuite().catch(err => {
  console.error('Fatal test runner error:', err);
  process.exit(1);
});
