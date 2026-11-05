const http = require('http');

const urls = [
  'http://127.0.0.1:8080/robots.txt',
  'http://127.0.0.1:8080/llms.txt',
  'http://127.0.0.1:8080/sitemap.xml',
  'http://127.0.0.1:8080/sitemap_index.xml',
  'http://127.0.0.1:8080/example.md',
  'http://127.0.0.1:8080/schema.json'
];

(async () => {
  let allPass = true;
  for (const u of urls) {
    await new Promise((resolve) => {
      http.get(u, (resp) => {
        const ct = resp.headers['content-type'] || '';
        const cd = resp.headers['content-disposition'] || '';
        const is200 = resp.statusCode === 200;
        const isInline = cd.includes('inline');
        console.log(`[${is200 && isInline ? 'PASS' : 'FAIL'}] ${u} -> HTTP ${resp.statusCode} | Type: ${ct} | Disposition: ${cd}`);
        if (!is200 || !isInline) allPass = false;
        resolve();
      });
    });
  }
  if (!allPass) {
    console.error('Some SEO URLs failed verification!');
    process.exit(1);
  } else {
    console.log('\nAll 6 Technical & SEO URLs verified successfully with HTTP 200 and inline display!');
  }
})();

