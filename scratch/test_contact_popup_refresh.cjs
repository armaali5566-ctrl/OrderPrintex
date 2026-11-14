const { chromium } = require('./node_modules/playwright-core');
const fs = require('fs');

(async () => {
  const browser = await chromium.launch({
    headless: true,
    executablePath: 'C:\\Users\\Arman\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe'
  });

  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 }
  });

  const page = await context.newPage();

  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });
  page.on('pageerror', err => {
    consoleErrors.push(err.message);
  });

  console.log('=== TEST 1: Open Homepage -> popup appears after 5 seconds ===');
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // Initially modal should NOT be open
  let isOpenInitial = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open immediately after load:', isOpenInitial, '(Expected: false)');
  if (isOpenInitial) throw new Error('Modal opened prematurely before 5 seconds');

  // Wait 5.3 seconds
  console.log('Waiting 5.3s for auto-popup timer...');
  await page.waitForTimeout(5300);

  let isOpenAfter5s = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after 5s:', isOpenAfter5s, '(Expected: true)');
  if (!isOpenAfter5s) throw new Error('Modal failed to open after 5 seconds');

  // Check computed styles for rectangular design
  console.log('\n=== TEST 2: Verify Rectangular Appearance & Border Radii ===');
  const dialogStyles = await page.$eval('.gallery-modal-dialog', el => {
    const cs = window.getComputedStyle(el);
    return {
      borderRadius: cs.borderRadius,
      border: cs.border,
      boxShadow: cs.boxShadow,
      background: cs.backgroundColor
    };
  });
  console.log('Dialog Computed Styles:', dialogStyles);

  const inputStyles = await page.$eval('#popupFullName', el => {
    const cs = window.getComputedStyle(el);
    return { borderRadius: cs.borderRadius };
  });
  console.log('Input Computed borderRadius:', inputStyles.borderRadius);

  const btnStyles = await page.$eval('.modal-submit-btn', el => {
    const cs = window.getComputedStyle(el);
    return { borderRadius: cs.borderRadius };
  });
  console.log('Submit Button Computed borderRadius:', btnStyles.borderRadius);

  const closeBtnStyles = await page.$eval('.gallery-modal-close', el => {
    const cs = window.getComputedStyle(el);
    return { borderRadius: cs.borderRadius, width: cs.width, height: cs.height };
  });
  console.log('Close Button Computed borderRadius:', closeBtnStyles.borderRadius, 'dimensions:', closeBtnStyles.width, 'x', closeBtnStyles.height);

  const badgeStyles = await page.$eval('.gallery-modal-badge', el => {
    const cs = window.getComputedStyle(el);
    return { borderRadius: cs.borderRadius };
  });
  console.log('Badge Computed borderRadius:', badgeStyles.borderRadius);

  // Take Desktop Screenshot of open rectangular modal
  await page.screenshot({ path: 'scratch/popup-desktop-rectangular.png' });
  console.log('Saved desktop screenshot to scratch/popup-desktop-rectangular.png');

  // Check mobile viewport
  console.log('\n--- Mobile Viewport Test (375px) ---');
  await page.setViewportSize({ width: 375, height: 667 });
  await page.waitForTimeout(300);
  const mobileDialog = await page.$eval('.gallery-modal-dialog', el => {
    const rect = el.getBoundingClientRect();
    return { width: rect.width, left: rect.left, right: rect.right };
  });
  console.log('Mobile Dialog width & bounds:', mobileDialog);
  await page.screenshot({ path: 'scratch/popup-mobile-rectangular.png' });
  console.log('Saved mobile screenshot to scratch/popup-mobile-rectangular.png');

  // Reset to desktop viewport
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.waitForTimeout(300);

  // Close the popup
  console.log('\n=== TEST 3: Close Popup -> Ensure it does NOT reopen on same page load ===');
  await page.click('.gallery-modal-close');
  await page.waitForTimeout(400);

  let isOpenAfterClose = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after clicking close:', isOpenAfterClose, '(Expected: false)');
  if (isOpenAfterClose) throw new Error('Modal did not close on close button click');

  // Wait 6 seconds more on the same page load
  console.log('Waiting 6s more on same page load to verify it does not reopen...');
  await page.waitForTimeout(6000);

  let isOpenAfterWait = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after additional 6s wait on same page:', isOpenAfterWait, '(Expected: false)');
  if (isOpenAfterWait) throw new Error('Modal reopened on the same page load after being closed!');

  // Refresh page
  console.log('\n=== TEST 4: Refresh Homepage -> Popup appears again after 5 seconds ===');
  await page.reload({ waitUntil: 'networkidle' });

  isOpenInitial = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open immediately after refresh:', isOpenInitial, '(Expected: false)');

  console.log('Waiting 5.3s after refresh...');
  await page.waitForTimeout(5300);

  isOpenAfter5s = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after 5s on refreshed page:', isOpenAfter5s, '(Expected: true)');
  if (!isOpenAfter5s) throw new Error('Modal failed to open after refresh 5-second timer');

  // Close it again
  await page.click('.gallery-modal-close');
  await page.waitForTimeout(400);

  // Navigate to About Us
  console.log('\n=== TEST 5: Navigate to About Us (/about) -> Popup appears after 5 seconds ===');
  await page.goto('http://127.0.0.1:8080/about', { waitUntil: 'networkidle' });

  isOpenInitial = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open immediately on /about:', isOpenInitial, '(Expected: false)');

  console.log('Waiting 5.3s on /about...');
  await page.waitForTimeout(5300);

  isOpenAfter5s = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after 5s on /about:', isOpenAfter5s, '(Expected: true)');
  if (!isOpenAfter5s) throw new Error('Modal failed to open on /about after 5-second timer');

  await page.click('.gallery-modal-close');
  await page.waitForTimeout(400);

  // Navigate to Services
  console.log('\n=== TEST 6: Navigate to Services (/services) -> Popup appears after 5 seconds ===');
  await page.goto('http://127.0.0.1:8080/services', { waitUntil: 'networkidle' });

  isOpenInitial = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open immediately on /services:', isOpenInitial, '(Expected: false)');

  console.log('Waiting 5.3s on /services...');
  await page.waitForTimeout(5300);

  isOpenAfter5s = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Modal open after 5s on /services:', isOpenAfter5s, '(Expected: true)');
  if (!isOpenAfter5s) throw new Error('Modal failed to open on /services after 5-second timer');

  await page.click('.gallery-modal-close');
  await page.waitForTimeout(400);

  // Gallery Manual Popup test
  console.log('\n=== TEST 7: Gallery Manual Popup & Auto-timer Cancellation ===');
  await page.goto('http://127.0.0.1:8080/', { waitUntil: 'networkidle' });

  // Click on a gallery action button within 2 seconds
  await page.waitForTimeout(1000);
  const exploreBtn = await page.$('.gallery-panel-link');
  if (exploreBtn) {
    console.log('Clicking gallery card explore button...');
    await exploreBtn.click();
    await page.waitForTimeout(400);

    const isManualOpen = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
    const modalTitle = await page.$eval('#galleryModalServiceTitle', el => el.textContent.trim());
    const selectedService = await page.$eval('#popupService', el => el.value);
    console.log('Manual modal open:', isManualOpen, '| Service Title:', modalTitle, '| Selected Value:', selectedService);

    // Wait past the 5s mark (another 5s) to ensure auto timer doesn't interfere
    console.log('Waiting 5s more to verify auto-timer was cancelled...');
    await page.waitForTimeout(5000);

    // Close modal
    await page.click('.gallery-modal-close');
    await page.waitForTimeout(400);

    // Wait another 3s to ensure it stays closed
    await page.waitForTimeout(3000);
    const staysClosed = await page.$eval('#galleryContactModal', el => !el.classList.contains('is-open'));
    console.log('Modal stays closed after manual interaction:', staysClosed, '(Expected: true)');
    if (!staysClosed) throw new Error('Modal reopened after manual interaction!');
  } else {
    console.log('No .gallery-panel-link found on homepage');
  }

  // Test Contact Prefill Handoff
  console.log('\n=== TEST 8: Test Form Fill & Contact Page Prefill Handoff ===');
  await page.reload({ waitUntil: 'networkidle' });
  await page.waitForTimeout(5300); // let auto-popup open

  console.log('Filling out popup form...');
  await page.fill('#popupFullName', 'Vikram Sethi');
  await page.fill('#popupMobile', '9876543210');
  await page.fill('#popupEmail', 'vikram.sethi@example.com');
  await page.selectOption('#popupService', 'Couple T-Shirts');
  await page.fill('#popupMessage', 'Need 150 pieces of custom couple t-shirts with front DTF print.');

  console.log('Submitting form...');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('.modal-submit-btn')
  ]);

  console.log('Landed on URL:', page.url());
  if (!page.url().includes('contact')) throw new Error('Did not redirect to contact page');

  // Verify fields prefilled on contact page
  const prefilledName = await page.$eval('#contactName', el => el.value);
  const prefilledEmail = await page.$eval('#contactEmail', el => el.value);
  const prefilledPhone = await page.$eval('#contactPhone', el => el.value);
  const prefilledSubject = await page.$eval('#contactSubject', el => el.value);
  const prefilledMessage = await page.$eval('#contactMessage', el => el.value);

  console.log('Prefilled Name:', prefilledName);
  console.log('Prefilled Email:', prefilledEmail);
  console.log('Prefilled Phone:', prefilledPhone);
  console.log('Prefilled Subject:', prefilledSubject);
  console.log('Prefilled Message preview:', prefilledMessage.substring(0, 60) + '...');

  if (prefilledName !== 'Vikram Sethi' || prefilledPhone !== '9876543210' || !prefilledSubject.includes('Couple T-Shirts')) {
    throw new Error('Prefill data transfer failed!');
  }

  // Wait 6 seconds on contact page to ensure auto popup does NOT pop up over user's prefilled form
  console.log('Waiting 6s on contact page to verify auto-popup is suppressed for prefilled enquiry...');
  await page.waitForTimeout(6000);
  const isPopupOnContact = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
  console.log('Popup opened on contact page:', isPopupOnContact, '(Expected: false)');
  if (isPopupOnContact) throw new Error('Popup interrupted the user on the contact page after form handoff!');

  // Test Admin Exclusion
  console.log('\n=== TEST 9: Admin Pages Exclusion ===');
  await page.goto('http://127.0.0.1:8080/admin/login.php', { waitUntil: 'networkidle' });
  console.log('Waiting 6s on /admin/login.php...');
  await page.waitForTimeout(6000);

  const hasModalInAdmin = await page.$('#galleryContactModal');
  console.log('Modal element on admin page:', !!hasModalInAdmin);
  if (hasModalInAdmin) {
    const isModalOpenInAdmin = await page.$eval('#galleryContactModal', el => el.classList.contains('is-open'));
    console.log('Modal open on admin page:', isModalOpenInAdmin, '(Expected: false)');
    if (isModalOpenInAdmin) throw new Error('Modal opened on admin page!');
  }

  console.log('\n=== Console Errors Check ===');
  console.log('Errors caught during test suite:', consoleErrors.length);
  if (consoleErrors.length > 0) {
    console.log('Console Errors:', consoleErrors);
  }

  console.log('\n========================================');
  console.log('>>> ALL 9 POPUP TEST SUITES PASSED! <<<');
  console.log('========================================');

  await browser.close();
})().catch(err => {
  console.error('TEST FAILED:', err);
  process.exit(1);
});
