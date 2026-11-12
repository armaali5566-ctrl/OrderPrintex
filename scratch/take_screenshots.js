const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

    console.log('Capturing homepage...');
    await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
    await page.screenshot({ path: 'scratch/current_home.png', fullPage: false });

    console.log('Capturing services page...');
    await page.goto('http://127.0.0.1:8080/services/dtf-printing.php', { waitUntil: 'networkidle' });
    await page.screenshot({ path: 'scratch/current_services.png', fullPage: false });

    console.log('Capturing admin login...');
    await page.goto('http://127.0.0.1:8080/admin/login.php', { waitUntil: 'networkidle' });
    await page.screenshot({ path: 'scratch/current_admin_login.png', fullPage: false });

    await browser.close();
    console.log('Done!');
})();

