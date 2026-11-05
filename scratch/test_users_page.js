const http = require('http');

function post(urlStr, data, cookies = '') {
  return new Promise((resolve, reject) => {
    const url = new URL(urlStr);
    const body = new URLSearchParams(data).toString();
    const req = http.request({
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': Buffer.byteLength(body),
        'Cookie': cookies
      }
    }, (res) => {
      let resData = '';
      res.on('data', chunk => resData += chunk);
      res.on('end', () => {
        const setCookies = res.headers['set-cookie'] || [];
        resolve({ statusCode: res.statusCode, headers: res.headers, body: resData, setCookies });
      });
    });
    req.on('error', reject);
    req.write(body);
    req.end();
  });
}

function get(urlStr, cookies = '') {
  return new Promise((resolve, reject) => {
    const url = new URL(urlStr);
    const req = http.request({
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: 'GET',
      headers: {
        'Cookie': cookies
      }
    }, (res) => {
      let resData = '';
      res.on('data', chunk => resData += chunk);
      res.on('end', () => {
        resolve({ statusCode: res.statusCode, headers: res.headers, body: resData });
      });
    });
    req.on('error', reject);
    req.end();
  });
}

async function run() {
  console.log('Testing Admin Users page...');
  
  // 1. Get login page to extract CSRF if needed
  const loginPage = await get('http://127.0.0.1:8080/admin/login.php');
  const csrfMatch = loginPage.body.match(/name="csrf_token"\s+value="([^"]+)"/);
  const csrfToken = csrfMatch ? csrfMatch[1] : '';
  
  // Collect initial cookies
  const cookieHeader = (loginPage.headers['set-cookie'] || []).map(c => c.split(';')[0]).join('; ');
  
  // 2. Perform admin login
  const loginRes = await post('http://127.0.0.1:8080/admin/login.php', {
    email: 'admin@orderprintex.com',
    password: 'Admin@Printex2026!',
    csrf_token: csrfToken
  }, cookieHeader);
  
  const allCookies = [
    ...(loginPage.headers['set-cookie'] || []),
    ...(loginRes.setCookies || [])
  ].map(c => c.split(';')[0]).join('; ');

  // 3. Request admin/users.php
  const usersPage = await get('http://127.0.0.1:8080/admin/users.php', allCookies);
  console.log('Status Code:', usersPage.statusCode);
  
  const hasError = usersPage.body.includes('Fatal error') || 
                   usersPage.body.includes('Warning:') || 
                   usersPage.body.includes('Notice:') ||
                   usersPage.body.includes('Undefined variable');
                   
  if (hasError) {
    console.error('FAIL: Error detected on page!');
    console.error(usersPage.body.substring(0, 500));
  } else {
    console.log('PASS: No PHP errors or warnings.');
  }

  const personnelTabMatch = usersPage.body.match(/Admin Personnel\s*\(\d+\)/);
  console.log('Personnel Tab Found:', personnelTabMatch ? personnelTabMatch[0] : 'NOT FOUND');

  const personnelTableCountMatch = usersPage.body.match(/Authorized Personnel\s*\(\d+\)/);
  console.log('Authorized Personnel Found:', personnelTableCountMatch ? personnelTableCountMatch[0] : 'NOT FOUND');
}

run().catch(console.error);
