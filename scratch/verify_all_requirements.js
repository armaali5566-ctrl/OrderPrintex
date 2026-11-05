const http = require('http');
const fs = require('fs');
const path = require('path');
const { chromium } = require('./node_modules/playwright-core');

const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';
const ROOT_DIR = 'C:\\Movies\\Internship\\OrderPrintex';

function httpGet(urlStr) {
  return new Promise((resolve) => {
    const url = new URL(urlStr);
    const req = http.request({
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: 'GET'
    }, (res) => {
      let data = '';
      res.on('data', c => data += c);
      res.on('end', () => resolve({ status: res.statusCode, headers: res.headers, body: data }));
    });
    req.on('error', (err) => resolve({ error: err.message }));
    req.end();
  });
}

async function main() {
  console.log('================================================================');
  console.log('ORDERPRINTEX — COMPREHENSIVE VERIFICATION & METRICS REPORT');
  console.log('================================================================\n');

  let passedAssertions = 0;
  let totalAssertions = 0;

  function assert(condition, message) {
    totalAssertions++;
    if (condition) {
      console.log(`  [PASS] ${message}`);
      passedAssertions++;
    } else {
      console.error(`  [FAIL] ${message}`);
    }
  }

  // TEST 1: Admin Personnel Page (admin/users.php)
  console.log('1. VERIFYING ADMIN PERSONNEL PAGE (admin/users.php)...');
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  const usersRes = await page.goto(BASE_URL + '/admin/users.php');
  assert(usersRes.status() === 200, 'admin/users.php returns HTTP 200');

  const usersHtml = await page.content();
  assert(!usersHtml.includes('Fatal error'), 'admin/users.php has zero PHP Fatal errors');
  assert(!usersHtml.includes('Warning: Undefined variable $admins'), 'admin/users.php has no undefined variable $admins warning');
  assert(!usersHtml.includes('TypeError: count()'), 'admin/users.php has no TypeError on count()');
  assert(usersHtml.includes('Admin Personnel ('), 'admin/users.php displays Admin Personnel tab count');
  assert(usersHtml.includes('Authorized Personnel ('), 'admin/users.php displays Authorized Personnel card title');

  // TEST 2: File 1 - sitemap.xml
  console.log('\n2. VERIFYING FILE 1: sitemap.xml...');
  const sitemapPath = path.join(ROOT_DIR, 'sitemap.xml');
  assert(fs.existsSync(sitemapPath), 'sitemap.xml exists on disk');
  const sitemapContent = fs.readFileSync(sitemapPath, 'utf8');
  assert(sitemapContent.startsWith('<?xml version="1.0" encoding="UTF-8"?>'), 'sitemap.xml has valid XML declaration');
  assert(sitemapContent.includes('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'), 'sitemap.xml has valid urlset schema');

  const urlMatches = sitemapContent.match(/<loc>/g) || [];
  assert(urlMatches.length >= 35, `sitemap.xml contains full website index (${urlMatches.length} URLs >= 35)`);

  const sitemapHttp = await httpGet(`${BASE_URL}/sitemap.xml`);
  assert(sitemapHttp.status === 200, 'sitemap.xml accessible via HTTP 200');
  assert(sitemapHttp.headers['content-type'].includes('xml'), 'sitemap.xml served as XML');

  // Check exclusion of admin/auth/cart
  assert(!sitemapContent.includes('/admin/'), 'sitemap.xml excludes admin routes');
  assert(!sitemapContent.includes('/cart/'), 'sitemap.xml excludes cart routes');
  assert(!sitemapContent.includes('/checkout/'), 'sitemap.xml excludes checkout routes');
  assert(!sitemapContent.includes('/account/'), 'sitemap.xml excludes private customer account routes');

  // TEST 3: File 2 - llms.txt
  console.log('\n3. VERIFYING FILE 2: llms.txt...');
  const llmsPath = path.join(ROOT_DIR, 'llms.txt');
  assert(fs.existsSync(llmsPath), 'llms.txt exists on disk');
  const llmsContent = fs.readFileSync(llmsPath, 'utf8');
  assert(llmsContent.includes('# OrderPrintex — Commercial Printing'), 'llms.txt has standard H1 title');
  assert(llmsContent.includes('B2B & Bespoke Enquiry-to-Quotation Workflow'), 'llms.txt documents B2B bespoke workflow');
  assert(llmsContent.includes('## Industrial Equipment & Machinery Fleet'), 'llms.txt covers industrial equipment fleet');
  assert(llmsContent.includes('## Core Services Directory'), 'llms.txt lists all services');
  assert(llmsContent.includes('## Exhaustive Product Catalog'), 'llms.txt details product catalog');
  assert(llmsContent.includes('## Prepress & Artwork Submission Standards'), 'llms.txt details prepress standards');
  assert(llmsContent.includes('## Production Showcase & Factory Gallery'), 'llms.txt details gallery showcase');
  assert(llmsContent.includes('## Frequently Asked Questions'), 'llms.txt details curated FAQs');
  assert(llmsContent.includes('## Corporate Directory & Contact Details'), 'llms.txt includes contact and facility location');

  const llmsHttp = await httpGet(`${BASE_URL}/llms.txt`);
  assert(llmsHttp.status === 200, 'llms.txt accessible via HTTP 200');

  // TEST 4: File 3 - schema.json
  console.log('\n4. VERIFYING FILE 3: schema.json (JSON-LD)...');
  const schemaPath = path.join(ROOT_DIR, 'schema.json');
  assert(fs.existsSync(schemaPath), 'schema.json exists on disk');
  let schemaJson = null;
  try {
    schemaJson = JSON.parse(fs.readFileSync(schemaPath, 'utf8'));
    assert(schemaJson !== null, 'schema.json parses as valid JSON');
  } catch (e) {
    assert(false, `schema.json JSON parse error: ${e.message}`);
  }

  assert(schemaJson['@context'] === 'https://schema.org', 'schema.json has @context https://schema.org');
  assert(Array.isArray(schemaJson['@graph']), 'schema.json contains @graph array');
  assert(schemaJson['@graph'].length >= 30, `schema.json @graph contains ${schemaJson['@graph'].length} structured nodes`);

  const types = schemaJson['@graph'].map(n => n['@type']);
  assert(types.includes('ProfessionalService'), 'schema.json contains ProfessionalService/LocalBusiness');
  assert(types.includes('WebSite'), 'schema.json contains WebSite');
  assert(types.includes('WebPage'), 'schema.json contains WebPage nodes');
  assert(types.includes('Service'), 'schema.json contains Service nodes');
  assert(types.includes('Product'), 'schema.json contains Product nodes');
  assert(types.includes('BlogPosting'), 'schema.json contains BlogPosting nodes');
  assert(types.includes('CreativeWork'), 'schema.json contains CreativeWork nodes');
  assert(types.includes('FAQPage'), 'schema.json contains FAQPage node');

  const schemaHttp = await httpGet(`${BASE_URL}/schema.json`);
  assert(schemaHttp.status === 200, 'schema.json accessible via HTTP 200');

  // TEST 5: File 4 - ORDERPRINTEX.md
  console.log('\n5. VERIFYING FILE 4: ORDERPRINTEX.md...');
  const mdPath = path.join(ROOT_DIR, 'ORDERPRINTEX.md');
  assert(fs.existsSync(mdPath), 'ORDERPRINTEX.md exists on disk');
  const mdContent = fs.readFileSync(mdPath, 'utf8');
  assert(mdContent.includes('# ORDERPRINTEX — Complete Commercial Printing'), 'ORDERPRINTEX.md has master title');
  assert(mdContent.includes('## Table of Contents'), 'ORDERPRINTEX.md has Table of Contents');
  assert(mdContent.includes('## 1. Executive Summary & Corporate Profile'), 'ORDERPRINTEX.md has Section 1');
  assert(mdContent.includes('## 2. B2B Commercial Business Model'), 'ORDERPRINTEX.md has Section 2');
  assert(mdContent.includes('## 3. Industrial Machinery Fleet'), 'ORDERPRINTEX.md has Section 3');
  assert(mdContent.includes('## 4. Commercial Printing Services Directory'), 'ORDERPRINTEX.md has Section 4');
  assert(mdContent.includes('## 5. Exhaustive Product Catalog by Category'), 'ORDERPRINTEX.md has Section 5');
  assert(mdContent.includes('## 6. Prepress Engineering & Artwork Standards'), 'ORDERPRINTEX.md has Section 6');
  assert(mdContent.includes('## 7. Production Portfolio & Specimen Gallery'), 'ORDERPRINTEX.md has Section 7');
  assert(mdContent.includes('## 8. Industry Knowledge Base & Educational Guides'), 'ORDERPRINTEX.md has Section 8');
  assert(mdContent.includes('## 9. Frequently Asked Questions'), 'ORDERPRINTEX.md has Section 9');
  assert(mdContent.includes('## 10. Corporate Directory'), 'ORDERPRINTEX.md has Section 10');
  assert(mdContent.includes('## 11. Master Website Sitemap & Public URL Directory'), 'ORDERPRINTEX.md has Section 11');

  const mdHttp = await httpGet(`${BASE_URL}/ORDERPRINTEX.md`);
  assert(mdHttp.status === 200, 'ORDERPRINTEX.md accessible via HTTP 200');

  // TEST 6: Apache Serving Check
  console.log('\n6. VERIFYING APACHE (Port 80) ACCESS...');
  const apacheTargets = [
    'http://localhost/OrderPrintex/sitemap.xml',
    'http://localhost/OrderPrintex/llms.txt',
    'http://localhost/OrderPrintex/schema.json',
    'http://localhost/OrderPrintex/ORDERPRINTEX.md'
  ];
  for (const at of apacheTargets) {
    const res = await httpGet(at);
    assert(res.status === 200, `${at} returns HTTP 200 on Apache`);
  }

  await browser.close();

  console.log('\n================================================================');
  console.log(`AUDIT RESULTS: ${passedAssertions} / ${totalAssertions} assertions passed (${Math.round((passedAssertions/totalAssertions)*100)}%)`);
  console.log('================================================================');

  if (passedAssertions === totalAssertions) {
    console.log('🎉 ALL ASSERTIONS PASSED PERFECTLY!');
  } else {
    process.exit(1);
  }
}

main().catch(err => {
  console.error(err);
  process.exit(1);
});

