async function testRedirects() {
  const paths = [
    'cart.php',
    'checkout.php',
    'account/orders.php',
    'account/order-details.php',
    'account/wishlist.php',
    'admin/orders.php',
    'admin/homepage.php',
    'admin/pages.php',
    'admin/media.php',
    'admin/faqs.php',
    'admin/seo.php',
    'admin/variations.php',
    'admin/attributes.php'
  ];

  const bases = ['http://127.0.0.1:8080/', 'http://localhost/OrderPrintex/'];

  for (const base of bases) {
    console.log(`\nTesting on: ${base}`);
    for (const p of paths) {
      try {
        const u = base + p;
        const res = await fetch(u, { method: 'GET', redirect: 'manual' });
        const loc = res.headers.get('location') || '';
        console.log(`  ${p.padEnd(26)} -> Status: ${res.status} | Location: ${loc}`);
      } catch (e) {
        console.log(`  ${p.padEnd(26)} -> ERR: ${e.message}`);
      }
    }
  }
}

testRedirects();

