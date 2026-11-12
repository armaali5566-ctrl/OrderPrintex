const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

    // Test on Admin Enquiries
    console.log('--- Testing Admin Enquiries Element Picking ---');
    await page.goto('http://127.0.0.1:8080/admin/login.php');
    await page.fill('input[name="email"]', 'admin@orderprintex.com');
    await page.fill('input[name="password"]', 'admin123');
    await Promise.all([
        page.waitForNavigation(),
        page.click('button[type="submit"]')
    ]);

    await page.goto('http://127.0.0.1:8080/admin/enquiries.php');
    await page.waitForTimeout(500);

    const adminHits = await page.evaluate(() => {
        const testPoints = [
            [300, 200], [500, 300], [700, 400], [900, 250], [600, 600]
        ];
        return testPoints.map(([x, y]) => {
            const el = document.elementFromPoint(x, y);
            const allElements = document.elementsFromPoint(x, y);
            return {
                point: [x, y],
                topElement: { tag: el?.tagName, id: el?.id, cls: el?.className },
                allStack: allElements.map(e => ({ tag: e.tagName, id: e.id, cls: e.className })).slice(0, 4)
            };
        });
    });
    console.log('ADMIN ENQUIRIES HITS:', JSON.stringify(adminHits, null, 2));

    // Test on Homepage
    console.log('\n--- Testing Homepage Element Picking ---');
    await page.goto('http://127.0.0.1:8080/');
    await page.waitForTimeout(500);

    const homeHits = await page.evaluate(() => {
        const testPoints = [
            [200, 100], [400, 250], [720, 400], [1100, 300], [500, 600], [1200, 800]
        ];
        return testPoints.map(([x, y]) => {
            const el = document.elementFromPoint(x, y);
            const allElements = document.elementsFromPoint(x, y);
            return {
                point: [x, y],
                topElement: { tag: el?.tagName, id: el?.id, cls: el?.className },
                allStack: allElements.map(e => ({ tag: e.tagName, id: e.id, cls: e.className })).slice(0, 4)
            };
        });
    });
    console.log('HOMEPAGE HITS:', JSON.stringify(homeHits, null, 2));

    await browser.close();
})();
