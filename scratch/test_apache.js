const { execSync } = require('child_process');

async function testApache() {
  const APACHE_URL = 'http://localhost/OrderPrintex';
  console.log(`Checking Apache endpoints at ${APACHE_URL}...`);

  const urls = [
    `${APACHE_URL}/`,
    `${APACHE_URL}/products/`,
    `${APACHE_URL}/cart/`,
    `${APACHE_URL}/checkout/`,
    `${APACHE_URL}/account/dashboard.php`,
    `${APACHE_URL}/admin/dashboard.php`,
    `${APACHE_URL}/admin/gallery.php`,
    `${APACHE_URL}/admin/activity.php`,
    `${APACHE_URL}/sitemap.xml`,
    `${APACHE_URL}/llms.txt`
  ];

  for (const u of urls) {
    try {
      const res = await fetch(u, { redirect: 'manual' });
      console.log(`  [${res.status}] ${u.replace(APACHE_URL, '') || '/'} -> Location: ${res.headers.get('location') || 'none'}`);
    } catch(e) {
      console.log(`  [FAIL] ${u} -> ${e.message}`);
    }
  }
}

testApache();

