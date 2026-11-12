const { chromium } = require('./node_modules/playwright-core');

(async () => {
    const browser = await chromium.launch({ executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe', headless: true });
    const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
    await page.goto('http://127.0.0.1:8080/admin/login.php');
    await page.fill('input[name="email"]', 'admin@orderprintex.com');
    await page.fill('input[name="password"]', 'admin123');
    await Promise.all([
        page.waitForNavigation(),
        page.click('button[type="submit"]')
    ]);
    await page.goto('http://127.0.0.1:8080/admin/enquiries.php?enquiry_id=15');
    
    const info = await page.evaluate(() => {
        const wrap = document.querySelector('.enquiries-table-wrap');
        const tbl = document.querySelector('.enquiries-table');
        const ths = Array.from(tbl.querySelectorAll('th')).map(th => ({ text: th.innerText, w: th.offsetWidth, sw: th.scrollWidth }));
        const cells = Array.from(tbl.querySelectorAll('tbody tr:first-child td')).map(td => ({
            cls: td.className,
            w: td.offsetWidth,
            sw: td.scrollWidth,
            innerHTML: td.innerHTML.slice(0, 100)
        }));
        return {
            wrapScroll: wrap.scrollWidth,
            wrapClient: wrap.clientWidth,
            tblWidth: tbl.offsetWidth,
            ths,
            cells
        };
    });
    console.log(JSON.stringify(info, null, 2));
    await browser.close();
})();

