const BASE_URL = 'http://127.0.0.1:8080';

async function diagnose() {
  // 1. Customer quote 4 fetch
  // Login customer first
  const loginRes = await fetch(`${BASE_URL}/account/login.php`);
  const loginHtml = await loginRes.text();
  const csrf = loginHtml.match(/name="csrf_token"\s+value="([^"]+)"/i)[1];
  const cookie = loginRes.headers.get('set-cookie').split(';')[0];

  const postRes = await fetch(`${BASE_URL}/account/login.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': cookie
    },
    body: `csrf_token=${csrf}&email=customer@orderprintex.com&password=customer123`,
    redirect: 'manual'
  });
  const authCookie = postRes.headers.get('set-cookie') ? postRes.headers.get('set-cookie').split(';')[0] : cookie;

  const quoteRes = await fetch(`${BASE_URL}/account/quotations.php?id=4`, {
    headers: { 'Cookie': authCookie }
  });
  const quoteHtml = await quoteRes.text();
  console.log('Quote 4 HTML preview (first 500 chars):');
  console.log(quoteHtml.substring(0, 500));
  console.log('Does quoteHtml include "Grand Total"?', quoteHtml.includes('Grand Total'));
  console.log('Does quoteHtml include "QP-"?', quoteHtml.includes('QP-'));

  // 2. Admin product edit
  const adminGet = await fetch(`${BASE_URL}/admin/login.php`);
  const adminHtml = await adminGet.text();
  const adminCsrf = adminHtml.match(/name="csrf_token"\s+value="([^"]+)"/i)[1];
  const adminCookie = adminGet.headers.get('set-cookie').split(';')[0];

  const adminPost = await fetch(`${BASE_URL}/admin/login.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': adminCookie
    },
    body: `csrf_token=${adminCsrf}&email=admin@orderprintex.com&password=admin123`,
    redirect: 'manual'
  });
  const authAdminCookie = adminPost.headers.get('set-cookie') ? adminPost.headers.get('set-cookie').split(';')[0] : adminCookie;

  const prodRes = await fetch(`${BASE_URL}/admin/products.php?action=edit&id=2`, {
    headers: { 'Cookie': authAdminCookie },
    redirect: 'manual'
  });
  console.log('\nAdmin Product 2 Edit Status:', prodRes.status, 'Location:', prodRes.headers.get('location'));
  const prodHtml = await prodRes.text();
  console.log('Admin Product Edit HTML preview (first 500 chars):');
  console.log(prodHtml.substring(0, 500));
  console.log('Contains #tab-basic?', prodHtml.includes('#tab-basic'));
  console.log('Contains "Edit Product"?', prodHtml.includes('Edit Product'));
}

diagnose();

