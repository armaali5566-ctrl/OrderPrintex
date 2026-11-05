const http = require('http');

function check(urlStr) {
  return new Promise((resolve) => {
    const url = new URL(urlStr);
    const req = http.request({
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: 'GET'
    }, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        resolve({
          url: urlStr,
          status: res.statusCode,
          contentType: res.headers['content-type'] || '',
          contentLength: data.length,
          snippet: data.substring(0, 80).replace(/\r?\n/g, ' ')
        });
      });
    });
    req.on('error', (err) => resolve({ url: urlStr, error: err.message }));
    req.end();
  });
}

async function run() {
  const targets = [
    'http://127.0.0.1:8080/sitemap.xml',
    'http://127.0.0.1:8080/sitemap_index.xml',
    'http://127.0.0.1:8080/llms.txt',
    'http://127.0.0.1:8080/schema.json',
    'http://127.0.0.1:8080/ORDERPRINTEX.md',
    'http://localhost/OrderPrintex/sitemap.xml',
    'http://localhost/OrderPrintex/llms.txt',
    'http://localhost/OrderPrintex/schema.json',
    'http://localhost/OrderPrintex/ORDERPRINTEX.md'
  ];

  console.log('Testing HTTP access on Built-in Server & Apache...\n');
  for (const t of targets) {
    const res = await check(t);
    if (res.error) {
      console.log(`❌ ${res.url} -> Error: ${res.error}`);
    } else {
      console.log(`✅ ${res.url} -> HTTP ${res.status} | ${res.contentType} | Length: ${res.contentLength}`);
      console.log(`   Preview: "${res.snippet}"\n`);
    }
  }
}

run();

