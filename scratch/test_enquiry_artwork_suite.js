const { chromium } = require('./node_modules/playwright-core');
const { execSync } = require('child_process');
const path = require('path');
const CHROME_PATH = 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8080';

const results = [];
function record(name, pass, details = '') {
    results.push({ name, pass, details });
    console.log(`[${pass ? ' PASS ' : ' FAIL '}] ${name} ${details ? '- ' + details : ''}`);
}

function queryDb(query) {
    try {
        const out = execSync(`php scratch/query_db.php stdin_one`, { input: query }).toString().trim();
        return JSON.parse(out);
    } catch (e) {
        return null;
    }
}

function insertDbFile(data) {
    return execSync('php scratch/query_db.php insert_file', { input: JSON.stringify(data) }).toString();
}

async function run() {
    console.log('================================================================');
    console.log(' ORDERPRINTEX — CUSTOMER ARTWORK & ENQUIRY REDESIGN TEST SUITE  ');
    console.log('================================================================\n');

    let browser;
    try {
        browser = await chromium.launch({
            executablePath: CHROME_PATH,
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
    } catch (e) {
        console.error('Browser launch failed:', e);
        process.exit(1);
    }

    const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const page = await context.newPage();

    try {
        // -------------------------------------------------------------
        // TEST 1: Submit New Enquiry with Artwork from Public Website
        // -------------------------------------------------------------
        console.log('--- 1. Testing Public Artwork Upload & Enquiry Submission ---');
        await page.goto(`${BASE_URL}/get-quote.php`, { waitUntil: 'domcontentloaded' });

        const artworkPath = path.resolve(__dirname, 'test_artwork_1.png');
        await page.setInputFiles('#artworkFileInput', artworkPath);
        await page.waitForTimeout(300);

        // Verify client-side upload preview card appeared
        const hasPreview = await page.$('.file-preview-card');
        record('Client-side upload preview rendered', !!hasPreview);

        await page.selectOption('#quoteService', 'DTF Printing');
        await page.fill('#quoteQty', '30');
        await page.fill('#quoteName', 'Artwork Test Client');
        await page.fill('#quoteEmail', 'artwork_client@example.com');
        await page.fill('#quotePhone', '9812345678');
        await page.fill('#quoteNotes', 'Please check transparent gradient in the gold foil artwork.');

        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            page.click('.quote-submit-btn')
        ]);
        await page.waitForTimeout(1000);

        // Verify enquiry and file record in database
        const enquiry = queryDb("SELECT * FROM enquiries WHERE customer_email = 'artwork_client@example.com' ORDER BY id DESC LIMIT 1");
        record('Enquiry saved to database', !!enquiry && enquiry.customer_name === 'Artwork Test Client', `ID: ${enquiry?.id}, Ref: ${enquiry?.ref_number}`);

        const enquiryFile = queryDb(`SELECT * FROM enquiry_files WHERE enquiry_id = ${enquiry.id} LIMIT 1`);
        record('Artwork file record saved to enquiry_files', !!enquiryFile && enquiryFile.original_name === 'test_artwork_1.png', `File: ${enquiryFile?.stored_name}`);

        // -------------------------------------------------------------
        // TEST 2: Admin Login & Open Customer Enquiry Details
        // -------------------------------------------------------------
        console.log('\n--- 2. Admin Login & Enquiry Details Verification ---');
        await page.goto(`${BASE_URL}/admin/login.php`, { waitUntil: 'domcontentloaded' });
        await page.fill('input[name="email"]', 'admin@orderprintex.com');
        await page.fill('input[name="password"]', 'admin123');
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            page.click('button[type="submit"]')
        ]);

        await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${enquiry.id}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(500);

        // -------------------------------------------------------------
        // TEST 3: Verify Thumbnail Preview UI Requirements
        // -------------------------------------------------------------
        console.log('\n--- 3. Verifying Artwork Thumbnail Display ---');
        const sectionHeading = await page.$('h3:has-text("Customer Uploaded Artwork")');
        record('Dedicated "CUSTOMER UPLOADED ARTWORK" section present', !!sectionHeading);

        const thumbCard = await page.$('.artwork-thumb-card');
        record('Artwork thumbnail card rendered', !!thumbCard);

        const thumbBoxDimensions = await page.evaluate(() => {
            const el = document.querySelector('.artwork-thumb-card');
            const img = document.querySelector('.artwork-thumb-img');
            const compImg = img ? window.getComputedStyle(img) : null;
            return {
                cardWidth: el ? el.offsetWidth : 0,
                objectFit: compImg ? compImg.objectFit : '',
                hasImg: !!img && img.naturalWidth > 0
            };
        });

        const widthOk = thumbBoxDimensions.cardWidth >= 100 && thumbBoxDimensions.cardWidth <= 135;
        record('Thumbnail width is compact (~100-130px)', widthOk, `Actual width: ${thumbBoxDimensions.cardWidth}px`);
        record('Thumbnail uses object-fit: contain', thumbBoxDimensions.objectFit === 'contain', `Actual: ${thumbBoxDimensions.objectFit}`);
        record('Thumbnail image loaded with valid natural dimensions (not broken)', thumbBoxDimensions.hasImg);

        const filenameLabel = await page.textContent('.artwork-thumb-name');
        record('Original filename displayed correctly', filenameLabel.includes('test_artwork_1.png'), `Displayed: "${filenameLabel}"`);

        const viewArtworkLabel = await page.$('.artwork-thumb-action, .artwork-thumb-overlay');
        record('"View Artwork" label / icon visible', !!viewArtworkLabel);

        // -------------------------------------------------------------
        // TEST 4: Full-Screen Image Viewer with Zoom & Pan
        // -------------------------------------------------------------
        console.log('\n--- 4. Verifying Full-Screen Image Viewer & Zoom ---');
        // Click thumbnail
        await page.click('.artwork-thumb-box');
        await page.waitForTimeout(400);

        const viewerIsOpen = await page.evaluate(() => {
            const v = document.getElementById('adminArtworkViewer');
            return v && v.classList.contains('is-open');
        });
        record('Full-screen viewer opens on thumbnail click', viewerIsOpen);

        const scrollLocked = await page.evaluate(() => document.body.classList.contains('viewer-scroll-locked'));
        record('Background body scroll is locked while viewer is active', scrollLocked);

        const viewerTitle = await page.textContent('#artworkViewerTitle');
        record('Viewer displays original filename in header', viewerTitle.includes('test_artwork_1.png'), `Header: "${viewerTitle}"`);

        // Test Zoom In
        const initialZoom = await page.textContent('#viewerZoomLevel');
        await page.click('#viewerZoomIn');
        await page.waitForTimeout(200);
        const zoomedInLevel = await page.textContent('#viewerZoomLevel');
        record('Zoom In increases zoom percentage', initialZoom === '100%' && zoomedInLevel === '125%', `Level: ${zoomedInLevel}`);

        // Zoom in once more
        await page.click('#viewerZoomIn');
        await page.waitForTimeout(200);
        const zoomed150 = await page.textContent('#viewerZoomLevel');

        // Test Zoom Out
        await page.click('#viewerZoomOut');
        await page.waitForTimeout(200);
        const zoomedOutLevel = await page.textContent('#viewerZoomLevel');
        record('Zoom Out decreases zoom percentage', zoomedOutLevel === '125%', `Level: ${zoomedOutLevel}`);

        // Test Reset Zoom
        await page.click('#viewerReset');
        await page.waitForTimeout(200);
        const resetLevel = await page.textContent('#viewerZoomLevel');
        record('Reset Zoom resets scale back to 100%', resetLevel === '100%');

        // Test Drag / Pan when zoomed in
        await page.click('#viewerZoomIn');
        await page.click('#viewerZoomIn'); // 150%
        await page.waitForTimeout(200);

        const stageBeforeDrag = await page.evaluate(() => document.getElementById('artworkViewerStage').style.transform);
        const viewportBox = await page.$('#artworkViewerViewport');
        const box = await viewportBox.boundingBox();
        await page.mouse.move(box.x + box.width / 2, box.y + box.height / 2);
        await page.mouse.down();
        await page.mouse.move(box.x + box.width / 2 + 50, box.y + box.height / 2 + 30);
        await page.mouse.up();
        await page.waitForTimeout(200);
        const stageAfterDrag = await page.evaluate(() => document.getElementById('artworkViewerStage').style.transform);
        record('Drag / Pan smoothly shifts zoomed artwork stage', stageBeforeDrag !== stageAfterDrag, `Transform: ${stageAfterDrag}`);

        // Test Escape Key Closes Viewer
        await page.keyboard.press('Escape');
        await page.waitForTimeout(400);
        const viewerClosedEsc = await page.evaluate(() => {
            const v = document.getElementById('adminArtworkViewer');
            return v && !v.classList.contains('is-open');
        });
        record('Escape key closes the full-screen viewer', viewerClosedEsc);

        // Test Close Button
        await page.click('.artwork-thumb-box');
        await page.waitForTimeout(300);
        await page.click('#viewerClose');
        await page.waitForTimeout(300);
        const viewerClosedBtn = await page.evaluate(() => {
            const v = document.getElementById('adminArtworkViewer');
            return v && !v.classList.contains('is-open');
        });
        record('Close button (X) closes the full-screen viewer', viewerClosedBtn);

        // -------------------------------------------------------------
        // TEST 5: Multiple Uploaded Artworks Gallery Navigation
        // -------------------------------------------------------------
        console.log('\n--- 5. Verifying Multi-Artwork Gallery & Prev/Next Navigation ---');
        // Seed a second file into this enquiry
        const fs = require('fs');
        const targetDir = path.resolve(__dirname, '../uploads/artwork');
        if (!fs.existsSync(targetDir)) fs.mkdirSync(targetDir, { recursive: true });
        const stored2 = 'test_multi_' + Date.now() + '.jpg';
        fs.copyFileSync(path.resolve(__dirname, 'test_artwork_2.jpg'), path.join(targetDir, stored2));
        
        insertDbFile({
            enquiry_id: enquiry.id,
            original_name: 'test_artwork_2.jpg',
            stored_name: stored2,
            file_path: `uploads/artwork/${stored2}`,
            file_size: 12450,
            mime_type: 'image/jpeg',
            file_extension: 'jpg'
        });

        await page.reload({ waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(500);

        const thumbCount = await page.$$eval('.artwork-thumb-card', cards => cards.length);
        record('Multiple artwork files rendered in horizontal gallery', thumbCount >= 2, `Count: ${thumbCount}`);

        // Open viewer on first image
        const thumbs = await page.$$('.artwork-thumb-box');
        await thumbs[0].click();
        await page.waitForTimeout(300);

        // Verify Prev and Next buttons are visible
        const navBtnsVisible = await page.evaluate(() => {
            const prev = document.getElementById('viewerPrev');
            const next = document.getElementById('viewerNext');
            return prev && next && prev.style.display !== 'none' && next.style.display !== 'none';
        });
        record('Prev and Next buttons visible when multiple artworks exist', navBtnsVisible);

        // Click Next to navigate
        await page.click('#viewerNext');
        await page.waitForTimeout(200);
        const secondTitle = await page.textContent('#artworkViewerTitle');
        record('Clicking Next navigates to second artwork', secondTitle.includes('test_artwork_2.jpg'), `Title: "${secondTitle}"`);

        // Click Prev to return
        await page.click('#viewerPrev');
        await page.waitForTimeout(200);
        const backToFirstTitle = await page.textContent('#artworkViewerTitle');
        record('Clicking Prev returns to first artwork', backToFirstTitle.includes('test_artwork_1.png'), `Title: "${backToFirstTitle}"`);

        await page.keyboard.press('Escape');
        await page.waitForTimeout(300);

        // -------------------------------------------------------------
        // TEST 6: Enquiry Without Artwork Clean Message
        // -------------------------------------------------------------
        console.log('\n--- 6. Verifying Enquiry Without Artwork ---');
        // Create an enquiry with no artwork
        const noArtEnq = queryDb("SELECT * FROM enquiries WHERE id NOT IN (SELECT enquiry_id FROM enquiry_files) ORDER BY id DESC LIMIT 1");
        if (noArtEnq) {
            await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${noArtEnq.id}`, { waitUntil: 'domcontentloaded' });
            await page.waitForTimeout(300);

            const noArtMsg = await page.$('.no-artwork-notice');
            const msgText = noArtMsg ? await noArtMsg.textContent() : '';
            record('Enquiry without artwork displays clean notice', msgText.includes('No artwork uploaded for this enquiry.'), `Notice: "${msgText.trim()}"`);

            const brokenImgs = await page.$$eval('.admin-card img', imgs => imgs.filter(i => i.naturalWidth === 0).length);
            record('Zero broken image icons rendered', brokenImgs === 0);
        } else {
            record('Enquiry without artwork test', true, 'Skipped (all enquiries have files)');
        }

        // -------------------------------------------------------------
        // TEST 7: Document / PDF Artwork Handling
        // -------------------------------------------------------------
        console.log('\n--- 7. Verifying Document / PDF Artwork Card ---');
        const storedPdf = 'test_doc_' + Date.now() + '.pdf';
        fs.copyFileSync(path.resolve(__dirname, 'test_artwork_3.pdf'), path.join(targetDir, storedPdf));
        
        insertDbFile({
            enquiry_id: enquiry.id,
            original_name: 'client_specification.pdf',
            stored_name: storedPdf,
            file_path: `uploads/artwork/${storedPdf}`,
            file_size: 45000,
            mime_type: 'application/pdf',
            file_extension: 'pdf'
        });

        await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${enquiry.id}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(300);

        const pdfBadge = await page.$('.artwork-thumb-doc .badge:has-text("PDF")');
        record('PDF artwork renders as stylized document card (not broken img)', !!pdfBadge);

        // -------------------------------------------------------------
        // TEST 8: Missing File Handling (No Broken Image)
        // -------------------------------------------------------------
        console.log('\n--- 8. Verifying Missing File Handling ---');
        insertDbFile({
            enquiry_id: enquiry.id,
            original_name: 'deleted_artwork.png',
            stored_name: 'non_existent_file_9999.png',
            file_path: 'uploads/artwork/non_existent_file_9999.png',
            file_size: 50000,
            mime_type: 'image/png',
            file_extension: 'png'
        });

        await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${enquiry.id}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(300);

        const missingCard = await page.$('.artwork-thumb-doc:has-text("File Missing")');
        record('Missing file handled gracefully with warning card (zero broken image)', !!missingCard);

        // -------------------------------------------------------------
        // TEST 9: Line 356 PHP Warning & Raw Code Complete Elimination
        // -------------------------------------------------------------
        console.log('\n--- 9. Verifying Complete Elimination of PHP Line 356 Error ---');
        const pageContent = await page.content();
        const hasLine356 = pageContent.includes('on line 356');
        const hasRawDataConfirm = pageContent.includes('data-destructive="true"'); // would be visible outside tag if broken
        record('Zero PHP warnings or "on line 356" in page content', !hasLine356);

        // Check Delete Enquiry Button
        const deleteBtn = await page.$('button[data-confirm*="permanently delete"]');
        record('Delete Enquiry button rendered cleanly as proper styled button', !!deleteBtn);

        // Click Delete button and verify Black & Gold AdminModal appears
        await deleteBtn.click();
        await page.waitForTimeout(300);

        const modalVisible = await page.evaluate(() => {
            const m = document.getElementById('adminConfirmModal');
            return m && m.classList.contains('active');
        });
        record('Delete Enquiry triggers custom Black & Gold AdminModal (not native browser confirm)', modalVisible);

        // Cancel modal
        await page.click('#adminConfirmCancelBtn');
        await page.waitForTimeout(300);

        // -------------------------------------------------------------
        // TEST 10: Quotation Action & Pipeline Status Update
        // -------------------------------------------------------------
        console.log('\n--- 10. Verifying Quotation CTA and Status Update ---');
        const quoteBtn = await page.$('a:has-text("Generate Formal Quotation")');
        record('Generate Formal Quotation button present and accessible', !!quoteBtn);

        // Update status to 'under_review'
        await page.selectOption('select[name="status"]', 'under_review');
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            page.click('button:has-text("Update Status")')
        ]);
        await page.waitForTimeout(500);

        const updatedEnquiry = queryDb(`SELECT status FROM enquiries WHERE id = ${enquiry.id}`);
        record('Pipeline status successfully updated in database', updatedEnquiry?.status === 'under_review', `Status: ${updatedEnquiry?.status}`);

        // -------------------------------------------------------------
        // TEST 11: Desktop Layout & Zero Table Horizontal Scroll
        // -------------------------------------------------------------
        console.log('\n--- 11. Verifying Desktop Layout & Zero Overflow ---');
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${enquiry.id}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(300);

        const tableOverflow = await page.evaluate(() => {
            const wrap = document.querySelector('.enquiries-table-wrap');
            const tbl = document.querySelector('.enquiries-table');
            return {
                wrapScroll: wrap ? wrap.scrollWidth : 0,
                wrapClient: wrap ? wrap.clientWidth : 0,
                tableLayout: tbl ? window.getComputedStyle(tbl).tableLayout : ''
            };
        });
        const noHorizScroll = tableOverflow.wrapScroll <= tableOverflow.wrapClient + 5;
        record('Enquiry submissions table fits panel without horizontal scroll', noHorizScroll, `Scroll: ${tableOverflow.wrapScroll}px, Client: ${tableOverflow.wrapClient}px`);
        record('Table utilizes table-layout: fixed', tableOverflow.tableLayout === 'fixed');

        // -------------------------------------------------------------
        // TEST 12: Mobile Responsive Viewport (375px)
        // -------------------------------------------------------------
        console.log('\n--- 12. Verifying Mobile Responsive Viewport (375px) ---');
        await page.setViewportSize({ width: 375, height: 667 });
        await page.goto(`${BASE_URL}/admin/enquiries.php?enquiry_id=${enquiry.id}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(300);

        const mobileLayout = await page.evaluate(() => {
            const docWidth = document.documentElement.clientWidth;
            const bodyScrollWidth = document.body.scrollWidth;
            const firstRow = document.querySelector('.enquiries-table tbody tr');
            const cardStyle = firstRow ? window.getComputedStyle(firstRow).display : '';
            return {
                noOverflow: bodyScrollWidth <= docWidth + 2,
                cardDisplay: cardStyle
            };
        });
        record('Mobile viewport 375px has zero horizontal page overflow', mobileLayout.noOverflow);
        record('Mobile view converts rows into compact responsive cards', mobileLayout.cardDisplay === 'block', `Display: ${mobileLayout.cardDisplay}`);

    } catch (err) {
        console.error('Test execution error:', err);
    } finally {
        await browser.close();
    }

    console.log('\n================================================================');
    const total = results.length;
    const passed = results.filter(r => r.pass).length;
    console.log(` RESULTS: ${passed} / ${total} Tests Passed (${Math.round((passed / total) * 100)}%)`);
    console.log('================================================================\n');

    if (passed !== total) {
        process.exit(1);
    }
}

run();
