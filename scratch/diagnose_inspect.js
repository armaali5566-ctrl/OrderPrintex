const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });
    const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
    
    page.on('console', msg => console.log('BROWSER CONSOLE:', msg.type(), msg.text()));
    page.on('pageerror', err => console.log('PAGE ERROR:', err.message));

    console.log('--- Loading Homepage ---');
    await page.goto('http://127.0.0.1:8080/');
    await page.waitForTimeout(1000);

    const hitElements = await page.evaluate(() => {
        const points = [
            [100, 100],
            [300, 300],
            [640, 450],
            [900, 200],
            [640, 700]
        ];
        return points.map(([x, y]) => {
            const el = document.elementFromPoint(x, y);
            return {
                point: [x, y],
                tag: el?.tagName,
                id: el?.id,
                className: el?.className,
                outer: el?.outerHTML?.slice(0, 120)
            };
        });
    });
    console.log('HIT ELEMENTS AT POINTS (Homepage):', JSON.stringify(hitElements, null, 2));

    // Check fixed or absolute elements with high z-index
    const highZ = await page.evaluate(() => {
        const all = Array.from(document.querySelectorAll('*'));
        const fixed = [];
        for (const el of all) {
            const s = window.getComputedStyle(el);
            if ((s.position === 'fixed' || s.position === 'absolute') && parseInt(s.zIndex) > 10) {
                const rect = el.getBoundingClientRect();
                fixed.push({
                    tag: el.tagName,
                    id: el.id,
                    className: el.className,
                    position: s.position,
                    zIndex: s.zIndex,
                    pointerEvents: s.pointerEvents,
                    rect: { top: rect.top, left: rect.left, width: rect.width, height: rect.height },
                    display: s.display,
                    visibility: s.visibility,
                    opacity: s.opacity
                });
            }
        }
        return fixed;
    });
    console.log('HIGH Z-INDEX ELEMENTS:', JSON.stringify(highZ, null, 2));

    // Now test Admin Login and Admin pages
    console.log('\n--- Loading Admin Dashboard ---');
    await page.goto('http://127.0.0.1:8080/admin/login.php');
    await page.fill('input[name="email"]', 'admin@orderprintex.com');
    await page.fill('input[name="password"]', 'admin123');
    await Promise.all([
        page.waitForNavigation(),
        page.click('button[type="submit"]')
    ]);

    await page.goto('http://127.0.0.1:8080/admin/index.php');
    await page.waitForTimeout(1000);

    const adminHighZ = await page.evaluate(() => {
        const all = Array.from(document.querySelectorAll('*'));
        const fixed = [];
        for (const el of all) {
            const s = window.getComputedStyle(el);
            if ((s.position === 'fixed' || s.position === 'absolute') && parseInt(s.zIndex) > 10) {
                const rect = el.getBoundingClientRect();
                fixed.push({
                    tag: el.tagName,
                    id: el.id,
                    className: el.className,
                    position: s.position,
                    zIndex: s.zIndex,
                    pointerEvents: s.pointerEvents,
                    rect: { top: rect.top, left: rect.left, width: rect.width, height: rect.height },
                    display: s.display,
                    visibility: s.visibility,
                    opacity: s.opacity
                });
            }
        }
        return fixed;
    });
    console.log('ADMIN HIGH Z-INDEX ELEMENTS:', JSON.stringify(adminHighZ, null, 2));

    // Now check admin/enquiries.php
    console.log('\n--- Loading admin/enquiries.php ---');
    await page.goto('http://127.0.0.1:8080/admin/enquiries.php');
    await page.waitForTimeout(1000);

    const enquiriesHighZ = await page.evaluate(() => {
        const all = Array.from(document.querySelectorAll('*'));
        const fixed = [];
        for (const el of all) {
            const s = window.getComputedStyle(el);
            if ((s.position === 'fixed' || s.position === 'absolute') && parseInt(s.zIndex) > 10) {
                const rect = el.getBoundingClientRect();
                fixed.push({
                    tag: el.tagName,
                    id: el.id,
                    className: el.className,
                    position: s.position,
                    zIndex: s.zIndex,
                    pointerEvents: s.pointerEvents,
                    rect: { top: rect.top, left: rect.left, width: rect.width, height: rect.height },
                    display: s.display,
                    visibility: s.visibility,
                    opacity: s.opacity
                });
            }
        }
        return fixed;
    });
    console.log('ENQUIRIES HIGH Z-INDEX ELEMENTS:', JSON.stringify(enquiriesHighZ, null, 2));

    await browser.close();
})();

