const { chromium } = require('./node_modules/playwright-core');
const fs = require('fs');
const path = require('path');

(async () => {
    const browser = await chromium.launch({
        executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
        headless: true
    });

    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

    const errors = [];
    page.on('console', msg => {
        if (msg.type() === 'error') {
            errors.push(msg.text());
        }
    });

    console.log('1. Verifying Homepage Hero & Photo...');
    await page.goto('http://127.0.0.1:8080/');
    await page.waitForTimeout(1000);

    // Check Hero Image
    const heroImgInfo = await page.evaluate(() => {
        const img = document.querySelector('.hero-visual-card img');
        if (!img) return null;
        return {
            src: img.src,
            naturalWidth: img.naturalWidth,
            naturalHeight: img.naturalHeight,
            complete: img.complete,
            displayedWidth: img.clientWidth,
            displayedHeight: img.clientHeight
        };
    });
    console.log('Hero Image Status:', heroImgInfo);

    // Take screenshot of Hero Section
    const heroCard = await page.$('.hero-section');
    if (heroCard) {
        await heroCard.screenshot({ path: path.join(__dirname, 'verified_hero_section.png') });
        console.log('Saved verified_hero_section.png');
    }

    // Check theme consistency
    console.log('\n2. Verifying Black & Gold Dark Theme...');
    const currentTheme = await page.evaluate(() => document.documentElement.getAttribute('data-theme'));
    console.log('Current theme:', currentTheme);

    // Check full page scrolling and reveal animations
    console.log('\n3. Verifying Smooth Scrolling & Section Visibility...');
    await page.evaluate(async () => {
        const distance = 400;
        const totalHeight = document.body.scrollHeight;
        let scrolled = 0;
        while (scrolled < totalHeight) {
            window.scrollBy(0, distance);
            scrolled += distance;
            await new Promise(r => setTimeout(r, 100));
        }
    });
    await page.waitForTimeout(1000);

    const hiddenSections = await page.evaluate(() => {
        const reveals = Array.from(document.querySelectorAll('.reveal'));
        return reveals
            .filter(r => {
                const style = window.getComputedStyle(r);
                return style.opacity === '0' || style.visibility === 'hidden';
            })
            .map(r => ({
                tag: r.tagName,
                cls: r.className,
                text: r.innerText.slice(0, 30)
            }));
    });
    console.log('Hidden/Unrevealed Sections count:', hiddenSections.length);

    await page.screenshot({ path: path.join(__dirname, 'verified_homepage_full.png'), fullPage: true });
    console.log('Saved verified_homepage_full.png');

    // Test Admin Enquiries Artwork Viewer
    console.log('\n4. Verifying Admin Enquiries & Artwork Inspection Viewer...');
    await page.goto('http://127.0.0.1:8080/admin/login.php');
    await page.fill('input[name="email"]', 'admin@orderprintex.com');
    await page.fill('input[name="password"]', 'admin123');
    await Promise.all([
        page.waitForNavigation(),
        page.click('button[type="submit"]')
    ]);

    await page.goto('http://127.0.0.1:8080/admin/enquiries.php');
    await page.waitForTimeout(500);

    // Check if thumbnail exists
    const hasThumb = await page.$('.artwork-thumb-box[data-artwork-thumb="true"]');
    if (hasThumb) {
        console.log('Found artwork thumbnail, testing click to open viewer...');
        await hasThumb.click();
        await page.waitForTimeout(400);

        const viewerState = await page.evaluate(() => {
            const v = document.getElementById('adminArtworkViewer');
            const style = v ? window.getComputedStyle(v) : null;
            return {
                exists: !!v,
                isOpen: v ? v.classList.contains('is-open') : false,
                display: style ? style.display : null,
                visibility: style ? style.visibility : null,
                pointerEvents: style ? style.pointerEvents : null,
                activeImgSrc: document.getElementById('artworkViewerImg')?.src
            };
        });
        console.log('Viewer opened state:', viewerState);

        // Take screenshot of open viewer
        await page.screenshot({ path: path.join(__dirname, 'verified_artwork_viewer_open.png') });
        console.log('Saved verified_artwork_viewer_open.png');

        // Close viewer
        console.log('Closing viewer via Escape key...');
        await page.keyboard.press('Escape');
        await page.waitForTimeout(400);

        const viewerClosedState = await page.evaluate(() => {
            const v = document.getElementById('adminArtworkViewer');
            const style = v ? window.getComputedStyle(v) : null;
            return {
                isOpen: v ? v.classList.contains('is-open') : false,
                display: style ? style.display : null,
                visibility: style ? style.visibility : null,
                pointerEvents: style ? style.pointerEvents : null
            };
        });
        console.log('Viewer closed state:', viewerClosedState);
    } else {
        console.log('No artwork thumbnail found on first enquiry.');
    }

    // Check mobile viewport for homepage
    console.log('\n5. Verifying Mobile Responsiveness (375x812)...');
    const mobilePage = await browser.newPage({ viewport: { width: 375, height: 812 } });
    await mobilePage.goto('http://127.0.0.1:8080/');
    await mobilePage.waitForTimeout(500);

    const hasHorizontalScroll = await mobilePage.evaluate(() => {
        return document.documentElement.scrollWidth > document.documentElement.clientWidth;
    });
    console.log('Mobile horizontal scroll detected:', hasHorizontalScroll);

    await mobilePage.screenshot({ path: path.join(__dirname, 'verified_homepage_mobile.png'), fullPage: false });
    console.log('Saved verified_homepage_mobile.png');

    console.log('\nConsole Errors during testing:', errors.length ? errors : 'None');

    await browser.close();
    console.log('\nALL VERIFICATION TESTS COMPLETE!');
})();

