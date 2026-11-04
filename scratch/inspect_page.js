const { chromium } = require('./node_modules/playwright-core');

(async () => {
  const browser = await chromium.launch({
    executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
    headless: true
  });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1032, height: 1376 });
  await page.goto('http://127.0.0.1:8080/products/custom-dtf-gang-sheet', { waitUntil: 'networkidle' });
  
  const sections = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('section, .product-content-block, [id$="-section"]')).map(el => {
      const rect = el.getBoundingClientRect();
      const style = window.getComputedStyle(el);
      return {
        tag: el.tagName,
        id: el.id,
        className: el.className,
        display: style.display,
        visibility: style.visibility,
        opacity: style.opacity,
        height: rect.height,
        width: rect.width,
        top: rect.top,
        y: window.scrollY + rect.top
      };
    });
  });
  console.log(JSON.stringify(sections, null, 2));
  await browser.close();
})();

