const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
    await page.goto('http://127.0.0.1:8080/');

    // Scroll smoothly all the way down
    await page.evaluate(async () => {
        for (let i = 0; i < document.body.scrollHeight; i += 300) {
            window.scrollTo(0, i);
            await new Promise(r => setTimeout(r, 50));
        }
        window.scrollTo(0, document.body.scrollHeight);
        await new Promise(r => setTimeout(r, 500));
    });

    const details = await page.evaluate(() => {
        const sections = Array.from(document.querySelectorAll('section'));
        return sections.map((sec, i) => {
            const h2 = sec.querySelector('h2')?.innerText || sec.className;
            const rect = sec.getBoundingClientRect();
            const reveals = Array.from(sec.querySelectorAll('.reveal')).map(r => ({
                tag: r.tagName,
                cls: r.className,
                opacity: window.getComputedStyle(r).opacity,
                transform: window.getComputedStyle(r).transform
            }));
            return {
                index: i + 1,
                title: h2,
                top: rect.top + window.scrollY,
                height: rect.height,
                revealsCount: reveals.length,
                allRevealsActive: reveals.every(r => r.opacity === '1'),
                inactiveReveals: reveals.filter(r => r.opacity !== '1')
            };
        });
    });

    console.log(JSON.stringify(details, null, 2));

    await page.screenshot({ path: 'scratch/home_after_real_scroll.png', fullPage: true });
    await browser.close();
})();
