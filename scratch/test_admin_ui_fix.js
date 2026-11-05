const BASE_URL = 'http://127.0.0.1:8080';

async function testAdminUIFix() {
  console.log('===============================================================');
  console.log('TESTING CATEGORIES REARRANGEMENT & DASHBOARD STACKED TABLES');
  console.log('===============================================================\n');

  // 1. Admin Login
  const loginGet = await fetch(`${BASE_URL}/admin/login.php`);
  const loginHtml = await loginGet.text();
  const csrfMatch = loginHtml.match(/name="csrf_token"\s+value="([^"]+)"/i);
  const csrf = csrfMatch[1];
  let adminCookie = loginGet.headers.get('set-cookie').split(';')[0];

  const loginPost = await fetch(`${BASE_URL}/admin/login.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': adminCookie
    },
    body: `csrf_token=${csrf}&email=admin@orderprintex.com&password=admin123`,
    redirect: 'manual'
  });
  if (loginPost.headers.get('set-cookie')) {
    adminCookie = loginPost.headers.get('set-cookie').split(';')[0];
  }

  // 2. Test Manage Categories Page
  console.log('--- 1. MANAGE CATEGORIES PAGE TESTS ---');
  const catRes = await fetch(`${BASE_URL}/admin/categories.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const catHtml = await catRes.text();

  console.log(`  [${catRes.status === 200 ? 'PASS' : 'FAIL'}] Categories Page Status 200`);

  // Verify form is ABOVE the table in HTML order
  const formIdx = catHtml.indexOf('id="category-form"');
  const tableIdx = catHtml.indexOf('Existing Product Categories');
  console.log(`  [${formIdx > 0 && tableIdx > formIdx ? 'PASS' : 'FAIL'}] Form appears at top BEFORE the categories listing (Form pos: ${formIdx}, Table pos: ${tableIdx})`);

  // Verify form is full-width (not inside .admin-split-grid)
  const isSplitGrid = catHtml.includes('class="admin-split-grid"');
  console.log(`  [${!isSplitGrid ? 'PASS' : 'FAIL'}] Side-by-side 2-column grid removed (isSplitGrid: ${isSplitGrid})`);

  // Verify form inputs
  const hasNameInput = catHtml.includes('name="name"');
  const hasSlugInput = catHtml.includes('name="slug"');
  const hasOrderInput = catHtml.includes('name="display_order"');
  const hasDescInput = catHtml.includes('name="description"');
  console.log(`  [${hasNameInput && hasSlugInput && hasOrderInput && hasDescInput ? 'PASS' : 'FAIL'}] Full-width form contains all required fields (Name, Slug, Order, Description)`);

  // Verify categories table columns
  const hasSeqTh = catHtml.includes('<th>Seq</th>') || catHtml.includes('>Seq<');
  const hasNameTh = catHtml.includes('Category Name');
  const hasSlugTh = catHtml.includes('URL Slug');
  const hasProductsTh = catHtml.includes('Assigned Products');
  const hasDateTh = catHtml.includes('Created Date');
  const hasActionsTh = catHtml.includes('Actions');
  console.log(`  [${hasSeqTh && hasNameTh && hasSlugTh && hasProductsTh && hasDateTh && hasActionsTh ? 'PASS' : 'FAIL'}] Categories listing table displays all required columns`);

  // Test Category CRUD: Create a temporary category
  const catCsrfMatch = catHtml.match(/name="csrf_token"\s+value="([^"]+)"/i);
  const catCsrf = catCsrfMatch ? catCsrfMatch[1] : '';

  const addCatBody = new URLSearchParams();
  addCatBody.append('csrf_token', catCsrf);
  addCatBody.append('action', 'save_category');
  addCatBody.append('category_id', '0');
  addCatBody.append('name', 'Automated Test Category');
  addCatBody.append('slug', 'automated-test-category');
  addCatBody.append('description', 'Created by automated test runner to verify full-width layout.');
  addCatBody.append('display_order', '99');

  const addCatRes = await fetch(`${BASE_URL}/admin/categories.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': adminCookie
    },
    body: addCatBody.toString(),
    redirect: 'manual'
  });
  console.log(`  [${addCatRes.status === 302 ? 'PASS' : 'FAIL'}] Add Category POST redirects (Status: ${addCatRes.status})`);

  // Verify added category exists
  const checkCatRes = await fetch(`${BASE_URL}/admin/categories.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const checkCatHtml = await checkCatRes.text();
  const addedCatFound = checkCatHtml.includes('Automated Test Category');
  console.log(`  [${addedCatFound ? 'PASS' : 'FAIL'}] New Category appears in full-width listing table`);

  // Extract ID of added category
  const editIdMatch = checkCatHtml.match(/admin\/categories\.php\?edit_id=(\d+)/);
  const newCatId = editIdMatch ? editIdMatch[1] : null;

  if (newCatId) {
    // Delete the test category to keep database clean
    const deleteCatBody = new URLSearchParams();
    deleteCatBody.append('csrf_token', catCsrf);
    deleteCatBody.append('action', 'delete_category');
    deleteCatBody.append('category_id', newCatId);

    const delCatRes = await fetch(`${BASE_URL}/admin/categories.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Cookie': adminCookie
      },
      body: deleteCatBody.toString(),
      redirect: 'manual'
    });
    console.log(`  [${delCatRes.status === 302 ? 'PASS' : 'FAIL'}] Delete Category cleanup redirects (Status: ${delCatRes.status})`);
  }

  // 3. Test Admin Dashboard Stacked Tables & KPI Cards
  console.log('\n--- 2. ADMIN DASHBOARD STACKED TABLES & KPI CARDS ---');
  const dashRes = await fetch(`${BASE_URL}/admin/dashboard.php`, {
    headers: { 'Cookie': adminCookie }
  });
  const dashHtml = await dashRes.text();

  console.log(`  [${dashRes.status === 200 ? 'PASS' : 'FAIL'}] Admin Dashboard Status 200`);

  // Verify 8 KPI Cards
  const kpis = [
    'Total Enquiries',
    'New Enquiries',
    'Under Review',
    'Pending Quotations',
    'Approved Quotations',
    'Rejected / Expired',
    'Total Products',
    'Portfolio Projects'
  ];
  const allKpisFound = kpis.every(k => dashHtml.includes(k));
  console.log(`  [${allKpisFound ? 'PASS' : 'FAIL'}] All 8 KPI cards present with labels: ${kpis.join(', ')}`);

  // Verify Stacked Tables Order
  const enqTableIdx = dashHtml.indexOf('Recent Customer Enquiries');
  const quoteTableIdx = dashHtml.indexOf('Itemized Quotation Desk');
  const catalogTableIdx = dashHtml.indexOf('Active Catalogue Highlights');

  const isStackedOrder = enqTableIdx > 0 && quoteTableIdx > enqTableIdx && catalogTableIdx > quoteTableIdx;
  console.log(`  [${isStackedOrder ? 'PASS' : 'FAIL'}] Tables are vertically stacked in correct order: Enquiries -> Quotations -> Catalogue`);

  // Verify NOT in side-by-side split grid
  const dashSplitGrid = dashHtml.includes('class="admin-split-grid"');
  console.log(`  [${!dashSplitGrid ? 'PASS' : 'FAIL'}] Side-by-side 2-column grid removed from dashboard tables (dashSplitGrid: ${dashSplitGrid})`);

  // Verify Recent Customer Enquiries Columns
  const enqHasRef = dashHtml.includes('Enquiry Ref #');
  const enqHasDate = dashHtml.includes('Submission Date');
  const enqHasCust = dashHtml.includes('Customer Information');
  const enqHasSpecs = dashHtml.includes('Service / Specifications');
  const enqHasQty = dashHtml.includes('Quantity');
  const enqHasStatus = dashHtml.includes('Status');
  const enqHasAction = dashHtml.includes('Action');
  console.log(`  [${enqHasRef && enqHasDate && enqHasCust && enqHasSpecs && enqHasQty && enqHasStatus && enqHasAction ? 'PASS' : 'FAIL'}] Recent Customer Enquiries displays all required columns including Submission Date`);

  // Verify Itemized Quotation Desk Columns
  const quoteHasRef = dashHtml.includes('Quote #');
  const quoteHasDate = dashHtml.includes('Date Issued');
  const quoteHasCust = dashHtml.includes('Customer');
  const quoteHasTotal = dashHtml.includes('Grand Total');
  const quoteHasStatus = dashHtml.includes('Status');
  const quoteHasAction = dashHtml.includes('Action');
  console.log(`  [${quoteHasRef && quoteHasDate && quoteHasCust && quoteHasTotal && quoteHasStatus && quoteHasAction ? 'PASS' : 'FAIL'}] Itemized Quotation Desk displays all required columns including Date Issued`);

  // 4. Test Sidebar Placement
  console.log('\n--- 3. SIDEBAR PLACEMENT TEST ---');
  const hasSidebarCategories = dashHtml.includes('Manage Categories') && dashHtml.includes('admin/categories.php');
  const prodIdx = dashHtml.indexOf('Product Management');
  const catIdx = dashHtml.indexOf('Manage Categories');
  const isNearProduct = catIdx > 0 && Math.abs(catIdx - prodIdx) < 500;
  console.log(`  [${hasSidebarCategories && isNearProduct ? 'PASS' : 'FAIL'}] Manage Categories present in sidebar directly beside Product Management`);

  console.log('\n===============================================================');
  console.log('ALL TESTS COMPLETED');
  console.log('===============================================================');
}

testAdminUIFix().catch(err => {
  console.error('Fatal error in test:', err);
  process.exit(1);
});

