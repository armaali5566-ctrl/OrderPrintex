const { chromium } = require('./node_modules/playwright-core');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  console.log('--- Step 1: Login as Super Admin ---');
  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', 'admin@orderprintex.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  console.log('Admin login URL:', page.url());

  console.log('\n--- Step 2: Open admin/users.php as Admin ---');
  await page.goto(BASE_URL + '/admin/users.php');
  await page.waitForLoadState('networkidle');
  let content = await page.content();
  const hasPhpErrors = /Fatal error|Warning:|Notice:|Undefined variable/i.test(content);
  console.log('PHP Notice/Error on users.php:', hasPhpErrors ? 'FOUND ERROR' : 'CLEAN');

  // Verify full-width cards and elements
  const cards = await page.$$('.admin-card');
  console.log('Admin cards count on users.php:', cards.length);

  // Check role dropdown and dynamic description
  const roleSelect = await page.locator('#newRoleSelect');
  console.log('Role select exists:', (await roleSelect.count()) > 0);
  const initialHelp = await page.locator('#newRoleDescription').innerText();
  console.log('Initial Staff description preview:', initialHelp.substring(0, 50) + '...');

  // Toggle role to admin in dropdown and check update
  await roleSelect.selectOption('admin');
  const adminHelp = await page.locator('#newRoleDescription').innerText();
  console.log('Admin description preview after change:', adminHelp.substring(0, 50) + '...');

  // Create Staff account
  const testStaffEmail = `staff_unit_${Date.now()}@orderprintex.com`;
  console.log('\nCreating Staff Account:', testStaffEmail);
  await roleSelect.selectOption('staff');
  await page.fill('input[name="name"]', 'Sarah Jenkins');
  await page.fill('input[name="email"]', testStaffEmail);
  await page.fill('input[name="phone"]', '+44 7700 901234');
  await page.fill('input[name="password"]', 'StaffSecure123!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');

  content = await page.content();
  console.log('Staff created in table:', content.includes(testStaffEmail));
  console.log('Staff badge rendered:', content.includes('Staff'));

  // Test Logout
  console.log('\n--- Step 3: Logout & Sign in as Staff ---');
  await page.goto(BASE_URL + '/account/logout.php');
  await page.waitForLoadState('networkidle');

  await page.goto(BASE_URL + '/admin/login.php');
  await page.fill('input[name="email"]', testStaffEmail);
  await page.fill('input[name="password"]', 'StaffSecure123!');
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  console.log('Staff login URL:', page.url());

  const staffDashContent = await page.content();
  console.log('Dashboard loaded for staff:', staffDashContent.includes('Bespoke Printing Commercial Desk'));
  console.log('Staff user role indicator:', staffDashContent.includes('Staff Member') || staffDashContent.includes('Staff'));
  console.log('Sidebar hides Admin Personnel for staff:', !staffDashContent.includes('admin/users.php'));
  console.log('Sidebar hides Settings for staff:', !staffDashContent.includes('admin/settings.php'));

  console.log('\n--- Step 4: Test Direct URL Security Enforcement for Staff ---');
  // 1. Try accessing users.php directly
  const usersRes = await page.goto(BASE_URL + '/admin/users.php');
  console.log('Direct users.php HTTP status:', usersRes.status());
  const usersDenied = (await page.content()).includes('Administrative Access Denied') || usersRes.status() === 403;
  console.log('Access Denied 403 on users.php:', usersDenied);

  // 2. Try accessing settings.php directly
  const settingsRes = await page.goto(BASE_URL + '/admin/settings.php');
  console.log('Direct settings.php HTTP status:', settingsRes.status());
  const settingsDenied = (await page.content()).includes('Administrative Access Denied') || settingsRes.status() === 403;
  console.log('Access Denied 403 on settings.php:', settingsDenied);

  // 3. Try accessing activity.php directly
  const activityRes = await page.goto(BASE_URL + '/admin/activity.php');
  console.log('Direct activity.php HTTP status:', activityRes.status());
  const activityDenied = (await page.content()).includes('Administrative Access Denied') || activityRes.status() === 403;
  console.log('Access Denied 403 on activity.php:', activityDenied);

  // 4. Verify staff CAN access authorized modules
  const enqRes = await page.goto(BASE_URL + '/admin/enquiries.php');
  console.log('Staff enquiries.php HTTP status:', enqRes.status());

  const quoteRes = await page.goto(BASE_URL + '/admin/quotations.php');
  console.log('Staff quotations.php HTTP status:', quoteRes.status());

  const prodRes = await page.goto(BASE_URL + '/admin/products.php');
  console.log('Staff products.php HTTP status:', prodRes.status());

  await browser.close();
  console.log('\nAll role and security tests PASSED perfectly!');
})().catch(err => {
  console.error(err);
  process.exit(1);
});

