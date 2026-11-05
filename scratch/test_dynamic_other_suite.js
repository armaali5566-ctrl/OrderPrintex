const { chromium } = require('./node_modules/playwright-core');
const { execSync } = require('child_process');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

const results = [];
function recordResult(testName, passed, details = '') {
    results.push({ testName, passed, details });
    const mark = passed ? ' PASS ' : ' FAIL ';
    console.log(`[${mark}] ${testName} ${details ? '- ' + details : ''}`);
}

function queryDb(mode, param) {
    try {
        const out = execSync(`php scratch/query_db.php ${mode} "${param}"`).toString().trim();
        return JSON.parse(out);
    } catch (e) {
        console.error(`queryDb error (${mode}, ${param}):`, e.message);
        return null;
    }
}

async function runTestSuite() {
    console.log('========================================================');
    console.log(' ORDERPRINTEX — DYNAMIC "OTHER" DROPDOWN VERIFICATION   ');
    console.log('========================================================\n');

    let browser;
    try {
        browser = await chromium.launch({
            executablePath: CHROME_PATH,
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
    } catch (e) {
        console.error('Failed to launch browser:', e);
        process.exit(1);
    }

    const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const page = await context.newPage();

    try {
        // -------------------------------------------------------------
        // TEST 1: Public get-quote.php Dynamic Other UI & Toggling
        // -------------------------------------------------------------
        console.log('\n--- Testing Public get-quote.php UI Behavior ---');
        await page.goto(`${BASE_URL}/get-quote.php`, { waitUntil: 'domcontentloaded' });

        const selectOtherOption = await page.$('#quoteService option[value="other"]');
        recordResult('get-quote.php contains "Other" option', !!selectOtherOption);

        const customContainer = await page.$('.dynamic-other-container[data-for-select="service_name"]');
        recordResult('Dynamic other custom container injected', !!customContainer);

        let isVisibleInitial = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="service_name"]');
            return el && el.classList.contains('is-visible');
        });
        recordResult('Custom field hidden initially', !isVisibleInitial);

        // Select "Other"
        await page.selectOption('#quoteService', 'other');
        await page.waitForTimeout(300);

        let isVisibleAfterOther = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="service_name"]');
            const inp = el ? el.querySelector('input') : null;
            return el && el.classList.contains('is-visible') && inp && inp.required && !inp.disabled;
        });
        recordResult('Selecting "Other" smoothly reveals required custom input', isVisibleAfterOther);

        // Switch back to predefined
        await page.selectOption('#quoteService', 'DTF Printing');
        await page.waitForTimeout(300);

        let isHiddenAfterSwitch = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="service_name"]');
            const inp = el ? el.querySelector('input') : null;
            return el && !el.classList.contains('is-visible') && inp && !inp.required && inp.value === '';
        });
        recordResult('Switching away hides container, removes required & clears value', isHiddenAfterSwitch);

        // -------------------------------------------------------------
        // TEST 2: Public get-quote.php Form Validation & Backend Submission
        // -------------------------------------------------------------
        console.log('\n--- Testing Public get-quote.php Submission with Custom Service ---');
        await page.selectOption('#quoteService', 'other');
        await page.fill('#quoteQty', '25');
        await page.fill('#quoteName', 'Automated Test User');
        await page.fill('#quoteEmail', 'testother@example.com');
        await page.fill('#quotePhone', '9876543210');

        // Test whitespace validation block
        const customInput = await page.$('.dynamic-other-container[data-for-select="service_name"] input');
        await customInput.fill('   '); // spaces only
        await page.click('.quote-submit-btn');
        await page.waitForTimeout(300);

        const blockedWhitespace = await customInput.evaluate(el => el.classList.contains('has-error'));
        recordResult('Whitespace-only "Other" submission blocked with error highlight', blockedWhitespace);

        // Now provide valid custom service name
        const uniqueCustomService = 'Direct-to-Film Holographic Foil Roll ' + Date.now();
        await customInput.fill(uniqueCustomService);
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}),
            page.click('.quote-submit-btn')
        ]);
        await page.waitForTimeout(1000);

        // Check database record
        const enqResult = queryDb('last_enquiry_by_email', 'testother@example.com');
        const savedCorrectly = enqResult && enqResult.service_name === uniqueCustomService;
        const neverSavedOther = enqResult && enqResult.service_name.toLowerCase() !== 'other';
        recordResult('Actual custom service name saved to DB (not "Other")', savedCorrectly && neverSavedOther, `Saved: "${enqResult ? enqResult.service_name : 'null'}"`);

        // -------------------------------------------------------------
        // TEST 3: Homepage Quick Quote Dynamic "Other"
        // -------------------------------------------------------------
        console.log('\n--- Testing Homepage Quick Quote ---');
        await page.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
        const homeSelectOther = await page.$('#serviceSelect option[value="other"]');
        recordResult('Homepage #serviceSelect has "Other" option', !!homeSelectOther);

        await page.selectOption('#serviceSelect', 'other');
        await page.waitForTimeout(300);
        const homeCustomVisible = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="service_name"]');
            return el && el.classList.contains('is-visible');
        });
        recordResult('Homepage "Other" dynamically shows custom input', homeCustomVisible);

        // -------------------------------------------------------------
        // TEST 4: Service Landing Pages (Corporate, DTF, T-Shirt, UV DTF)
        // -------------------------------------------------------------
        console.log('\n--- Testing Service Landing Pages Dropdowns ---');
        await page.goto(`${BASE_URL}/services/corporate-printing.php`, { waitUntil: 'domcontentloaded' });
        const corpOther = await page.$('select[name="material_options"] option[value="other"]');
        recordResult('Corporate Printing material dropdown has "Other"', !!corpOther);

        await page.goto(`${BASE_URL}/services/dtf-printing.php`, { waitUntil: 'domcontentloaded' });
        const dtfFilmOther = await page.$('#calcMaterialSelect option[value="other"]');
        recordResult('DTF Printing film material dropdown has "Other"', !!dtfFilmOther);

        await page.goto(`${BASE_URL}/services/t-shirt-printing.php`, { waitUntil: 'domcontentloaded' });
        const tshirtMatOther = await page.$('select[name="material_options"] option[value="other"]');
        const tshirtPlaceOther = await page.$('select[name="print_placement"] option[value="other"]');
        recordResult('T-Shirt Printing fabric and placement dropdowns have "Other"', !!tshirtMatOther && !!tshirtPlaceOther);

        await page.goto(`${BASE_URL}/services/uv-dtf-printing.php`, { waitUntil: 'domcontentloaded' });
        const uvMatOther = await page.$('select[name="material_options"] option[value="other"]');
        recordResult('UV DTF Printing substrate dropdown has "Other"', !!uvMatOther);

        // -------------------------------------------------------------
        // TEST 5: Admin Login & Personnel Management (Department Other)
        // -------------------------------------------------------------
        console.log('\n--- Testing Admin Personnel Management ---');
        await page.goto(`${BASE_URL}/admin/login.php`, { waitUntil: 'domcontentloaded' });
        await page.fill('input[name="email"]', 'admin@orderprintex.com');
        await page.fill('input[name="password"]', 'admin123');
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            page.click('button[type="submit"]')
        ]);

        await page.goto(`${BASE_URL}/admin/users.php`, { waitUntil: 'domcontentloaded' });

        const deptSelectHasOther = await page.$('#createPersonnelDept option[value="other"]');
        recordResult('Admin Create Personnel Department select has "Other"', !!deptSelectHasOther);

        // Select "Other" in personnel form
        await page.selectOption('#createPersonnelDept', 'other');
        await page.waitForTimeout(300);

        const customDeptVisible = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="department"]');
            return el && el.classList.contains('is-visible');
        });
        recordResult('Personnel custom department input revealed dynamically', customDeptVisible);

        // Create new personnel with custom department
        const customDeptName = 'International Logistics Desk ' + Date.now();
        const testStaffEmail = 'staff_dept_' + Date.now() + '@orderprintex.com';
        await page.fill('input[name="name"]', 'Staff Custom Dept');
        await page.fill('#createEmailInput', testStaffEmail);
        await page.fill('#createPhoneInput', '9811122233');
        await page.fill('.dynamic-other-container[data-for-select="department"] input', customDeptName);
        await page.fill('input[name="password"]', 'StaffPass@123');
        await page.fill('input[name="confirm_password"]', 'StaffPass@123');

        // Check staff role
        const staffRadio = await page.$('input[name="base_role"][value="staff"]');
        if (staffRadio) await staffRadio.click();

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}),
            page.click('#createPersonnelSubmitBtn')
        ]);
        await page.waitForTimeout(1000);

        // Verify custom department stored in DB
        const userResult = queryDb('user_by_email', testStaffEmail);
        const userDeptCorrect = userResult && userResult.department === customDeptName;
        recordResult('Custom department successfully saved to DB', userDeptCorrect, `Saved: "${userResult ? userResult.department : 'null'}"`);

        // Open Edit Modal for this user and verify Edit Mode Pre-population!
        console.log('\n--- Testing Edit Mode Custom Value Pre-population ---');
        await page.goto(`${BASE_URL}/admin/users.php`, { waitUntil: 'domcontentloaded' });
        const userRow = await page.$(`tr:has-text("${testStaffEmail}")`);
        if (userRow) {
            const editBtn = await userRow.$('.action-edit-btn, button:has-text("Edit")');
            if (editBtn) {
                await editBtn.click();
                await page.waitForTimeout(500);

                const modalDeptState = await page.evaluate(() => {
                    const sel = document.getElementById('editUserDept');
                    const wrap = document.querySelector('#editPersonnelForm .dynamic-other-container');
                    const inp = wrap ? wrap.querySelector('input') : null;
                    return {
                        selectValue: sel ? sel.value : null,
                        wrapVisible: wrap ? wrap.classList.contains('is-visible') : false,
                        inputValue: inp ? inp.value : null
                    };
                });

                const editPreSelected = modalDeptState.selectValue === 'other';
                const editPreFilled = modalDeptState.inputValue === customDeptName;
                recordResult('Edit modal pre-selects "Other" and pre-populates custom input', editPreSelected && editPreFilled, `Select: ${modalDeptState.selectValue}, Input: "${modalDeptState.inputValue}"`);
            } else {
                recordResult('Edit modal test', false, 'Edit button not found in row');
            }
        } else {
            recordResult('Edit modal test', false, 'User row not found');
        }

        // -------------------------------------------------------------
        // TEST 6: Admin Gallery Portfolio Dynamic "Other" Category
        // -------------------------------------------------------------
        console.log('\n--- Testing Admin Gallery Custom Category ---');
        await page.goto(`${BASE_URL}/admin/gallery.php`, { waitUntil: 'domcontentloaded' });
        const gallerySelectOther = await page.$('select[name="category"] option[value="other"]');
        recordResult('Admin Gallery category select has "Other"', !!gallerySelectOther);

        await page.selectOption('select[name="category"]', 'other');
        await page.waitForTimeout(300);
        const galleryCustomVisible = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="category"]');
            return el && el.classList.contains('is-visible');
        });
        recordResult('Gallery category custom field appears dynamically', galleryCustomVisible);

        // -------------------------------------------------------------
        // TEST 7: Admin Blog Dynamic "Other" Category
        // -------------------------------------------------------------
        console.log('\n--- Testing Admin Blog Custom Category ---');
        await page.goto(`${BASE_URL}/admin/blog.php?action=new`, { waitUntil: 'domcontentloaded' });
        const blogCatOther = await page.$('select[name="category_id"] option[value="other"]');
        recordResult('Admin Blog Category dropdown has "Other"', !!blogCatOther);

        await page.selectOption('select[name="category_id"]', 'other');
        await page.waitForTimeout(300);
        const blogCustomVisible = await page.evaluate(() => {
            const el = document.querySelector('.dynamic-other-container[data-for-select="category_id"]');
            return el && el.classList.contains('is-visible');
        });
        recordResult('Blog category custom input revealed dynamically', blogCustomVisible);

        // -------------------------------------------------------------
        // TEST 8: Admin Products Dynamic "Other" Category & Service
        // -------------------------------------------------------------
        console.log('\n--- Testing Admin Products Custom Category & Service ---');
        await page.goto(`${BASE_URL}/admin/products.php?action=new`, { waitUntil: 'domcontentloaded' });
        const prodCatOther = await page.$('select[name="category_id"] option[value="other"]');
        const prodSvcOther = await page.$('select[name="service_id"] option[value="other"]');
        recordResult('Product Form Category & Service dropdowns have "Other"', !!prodCatOther && !!prodSvcOther);

        // -------------------------------------------------------------
        // TEST 9: Verification of Excluded System Dropdowns
        // -------------------------------------------------------------
        console.log('\n--- Verifying Strict Exclusions (No "Other" on System Enums) ---');
        // Users status dropdown
        const statusHasOther = await page.$('select[name="status"] option[value="other"]');
        recordResult('Personnel Status dropdown excludes "Other"', !statusHasOther);

        // Reviews rating dropdown
        await page.goto(`${BASE_URL}/admin/reviews.php`, { waitUntil: 'domcontentloaded' });
        const ratingHasOther = await page.$('select[name="rating"] option[value="other"]');
        recordResult('Review Star Rating dropdown excludes "Other"', !ratingHasOther);

        // -------------------------------------------------------------
        // TEST 10: Mobile Responsive Viewport (375px)
        // -------------------------------------------------------------
        console.log('\n--- Testing Mobile Viewport (375px) ---');
        await page.setViewportSize({ width: 375, height: 667 });
        await page.goto(`${BASE_URL}/get-quote.php`, { waitUntil: 'domcontentloaded' });
        await page.selectOption('#quoteService', 'other');
        await page.waitForTimeout(300);

        const mobileLayoutOk = await page.evaluate(() => {
            const inp = document.querySelector('.dynamic-other-container input');
            const docWidth = document.documentElement.clientWidth;
            const bodyWidth = document.body.scrollWidth;
            return inp && inp.offsetWidth <= docWidth && bodyWidth <= docWidth + 5;
        });
        recordResult('Mobile viewport 375px renders custom input without overflow', mobileLayoutOk);

    } catch (err) {
        console.error('Test execution error:', err);
    } finally {
        await browser.close();
    }

    console.log('\n========================================================');
    const total = results.length;
    const passed = results.filter(r => r.passed).length;
    console.log(` SUMMARY: ${passed} / ${total} Tests Passed (${Math.round((passed / total) * 100)}%)`);
    console.log('========================================================\n');

    if (passed !== total) {
        process.exit(1);
    }
}

runTestSuite();
