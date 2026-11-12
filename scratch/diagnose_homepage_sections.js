const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

    await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });
    
    // Check all sections
    const sections = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('section, .section, footer, header, nav')).map(el => {
            const rect = el.getBoundingClientRect();
            const s = window.getComputedStyle(el);
            return {
                tag: el.tagName,
                id: el.id,
                className: el.className,
                rect: { top: rect.top, height: rect.height, width: rect.width },
                opacity: s.opacity,
                display: s.display,
                visibility: s.visibility,
                bgColor: s.backgroundColor,
                color: s.color
            };
        });
    });
    console.log('SECTIONS ON HOMEPAGE:', JSON.stringify(sections, null, 2));

    // Also check reveals that are still opacity 0
    const unrevealed = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('.reveal')).map(el => {
            const s = window.getComputedStyle(el);
            const rect = el.getBoundingClientRect();
            return {
                tag: el.tagName,
                id: el.id,
                className: el.className,
                opacity: s.opacity,
                top: rect.top,
                active: el.classList.contains('active')
            };
        });
    });
    console.log('REVEAL ELEMENTS:', JSON.stringify(unrevealed, null, 2));

    // Scroll down to trigger any observers
    await page.evaluate(async () => {
        await new Promise(resolve => {
            let totalHeight = 0;
            const distance = 400;
            const timer = setInterval(() => {
                window.scrollBy(0, distance);
                totalHeight += distance;
                if (totalHeight >= document.body.scrollHeight) {
                    clearInterval(timer);
                    resolve();
                }
            }, 100);
        });
    });

    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'scratch/home_scrolled_full.png', fullPage: true });

    await browser.close();
})();

