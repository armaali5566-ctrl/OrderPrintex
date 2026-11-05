async function testEndpoints() {
  const urls = [
    'http://127.0.0.1:8080/',
    'http://localhost/OrderPrintex/',
    'http://127.0.0.1:8080/products/',
    'http://127.0.0.1:8080/admin/login.php',
    'http://127.0.0.1:8080/sitemap.xml',
    'http://127.0.0.1:8080/llms.txt',
    'http://127.0.0.1:8080/robots.txt'
  ];

  for (const u of urls) {
    try {
      const res = await fetch(u, { method: 'GET', redirect: 'manual' });
      console.log(`[${res.status}] ${u} -> Location: ${res.headers.get('location') || 'none'}`);
    } catch (e) {
      console.log(`[FAIL] ${u} -> ${e.message}`);
    }
  }
}

testEndpoints();

