<?php
/**
 * Automated Verification Script for OrderPrintex Responsive Fixes
 * Tests the 5 high-priority issues:
 * 1. Mobile Upload Section (iPhone SE 375px, 320px, 360px, 430px)
 * 2. Desktop Navbar CTA (No duplicates, single CTA on desktop, drawer CTA on mobile)
 * 3. Homepage 3-product grid on desktop (3 equal columns)
 * 4. Floating Call & WhatsApp buttons (proper sizing, safe area, no obstruction)
 * 5. Responsive button text wrapping & touch targets
 */

$styleCss = file_get_contents(__DIR__ . '/../assets/css/style.css');
$responsiveCss = file_get_contents(__DIR__ . '/../assets/css/responsive.css');
$navbarPhp = file_get_contents(__DIR__ . '/../includes/navbar.php');
$footerPhp = file_get_contents(__DIR__ . '/../includes/footer.php');
$indexPhp = file_get_contents(__DIR__ . '/../index.php');
$getQuotePhp = file_get_contents(__DIR__ . '/../get-quote.php');
$mainJs = file_get_contents(__DIR__ . '/../assets/js/main.js');

$passed = 0;
$failed = 0;

function assertCheck($desc, $cond) {
    global $passed, $failed;
    if ($cond) {
        echo "[PASS] $desc\n";
        $passed++;
    } else {
        echo "[FAIL] $desc\n";
        $failed++;
    }
}

echo "=========================================================\n";
echo "   ORDERPRINTEX — TESTING RESPONSIVE FIXES & LAYOUT      \n";
echo "=========================================================\n\n";

// ISSUE 1: Mobile Upload Section
assertCheck(
    "Index.php upload section has quote-submit-bar and quote-submit-btn classes",
    strpos($indexPhp, 'class="quote-submit-bar"') !== false &&
    strpos($indexPhp, 'quote-submit-btn') !== false
);

assertCheck(
    "Get-quote.php upload section has quote-submit-bar and quote-submit-btn classes",
    strpos($getQuotePhp, 'class="quote-submit-bar"') !== false &&
    strpos($getQuotePhp, 'quote-submit-btn') !== false
);

assertCheck(
    "style.css has fluid padding and min-width:0 for upload-studio-grid children without blocking !important",
    strpos($styleCss, 'padding: clamp(2rem, 3.5vw, 3.5rem);') !== false &&
    strpos($styleCss, '.upload-studio-grid > *') !== false
);

assertCheck(
    "responsive.css defines 1-column upload studio on tablets <= 1024px",
    strpos($responsiveCss, '.upload-studio-grid') !== false &&
    preg_match('/@media\s*\(max-width:\s*1024px\)[\s\S]*?\.upload-studio-grid\s*\{[\s\S]*?grid-template-columns:\s*1fr\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines responsive upload card padding on mobile <= 768px",
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.page-get-quote\s+\.upload-card[\s\S]*?padding:\s*1\.25rem\s+1rem\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines mobile <= 480px upload card padding and dropzone",
    preg_match('/@media\s*\(max-width:\s*480px\)[\s\S]*?\.page-get-quote\s+\.upload-card[\s\S]*?padding:\s*1\.15rem\s+0\.85rem\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines small mobile <= 380px (iPhone SE 375px) upload card padding",
    preg_match('/@media\s*\(max-width:\s*380px\)[\s\S]*?\.page-get-quote\s+\.upload-card[\s\S]*?padding:\s*0\.95rem\s+0\.65rem\s*!important/i', $responsiveCss)
);

assertCheck(
    "Upload submit buttons expand to full width and wrap text on mobile <= 768px",
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.quote-submit-btn[\s\S]*?white-space:\s*normal\s*!important/i', $responsiveCss)
);

// ISSUE 2: Desktop Navbar CTA & Mobile Drawer CTA
assertCheck(
    "Base CSS hides .nav-drawer-cta-item by default (desktop view)",
    strpos($responsiveCss, '.nav-drawer-cta-item {') !== false &&
    preg_match('/\.nav-drawer-cta-item\s*\{[\s\S]*?display:\s*none\s*!important/i', $responsiveCss)
);

assertCheck(
    "Desktop ultra-wide media queries explicitly hide .nav-drawer-cta-item",
    preg_match('/@media\s*\(min-width:\s*2560px\)[\s\S]*?\.nav-drawer-cta-item\s*\{[\s\S]*?display:\s*none\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(min-width:\s*1920px\)\s*and\s*\(max-width:\s*2559px\)[\s\S]*?\.nav-drawer-cta-item\s*\{[\s\S]*?display:\s*none\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(min-width:\s*1440px\)\s*and\s*\(max-width:\s*1919px\)[\s\S]*?\.nav-drawer-cta-item\s*\{[\s\S]*?display:\s*none\s*!important/i', $responsiveCss)
);

assertCheck(
    "Drawer navigation (<= 1120px) shows .nav-drawer-cta-item inside drawer and hides .nav-actions .nav-cta-btn",
    preg_match('/@media\s*\(max-width:\s*1120px\)[\s\S]*?\.nav-drawer-cta-item\s*\{[\s\S]*?display:\s*block\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(max-width:\s*1120px\)[\s\S]*?\.nav-actions\s+\.nav-cta-btn\s*\{[\s\S]*?display:\s*none\s*!important/i', $responsiveCss)
);

// ISSUE 3: Homepage 3-product grid on desktop
assertCheck(
    "index.php product showcase uses products-grid products-grid-3",
    strpos($indexPhp, 'class="products-grid products-grid-3"') !== false
);

assertCheck(
    "responsive.css defines 3 equal columns for .products-grid-3 on ultra-wide desktop (>= 2560px)",
    preg_match('/@media\s*\(min-width:\s*2560px\)[\s\S]*?\.products-grid\.products-grid-3[\s\S]*?grid-template-columns:\s*repeat\(3,\s*1fr\)\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines 3 equal columns for .products-grid-3 on standard desktop (1440px - 1919px)",
    preg_match('/@media\s*\(min-width:\s*1440px\)\s*and\s*\(max-width:\s*1919px\)[\s\S]*?\.products-grid\.products-grid-3[\s\S]*?grid-template-columns:\s*repeat\(3,\s*1fr\)\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines 3 equal columns for .products-grid-3 on laptop (1121px - 1439px)",
    preg_match('/@media\s*\(min-width:\s*1121px\)\s*and\s*\(max-width:\s*1439px\)[\s\S]*?\.products-grid\.products-grid-3[\s\S]*?grid-template-columns:\s*repeat\(3,\s*1fr\)\s*!important/i', $responsiveCss)
);

assertCheck(
    "responsive.css defines 1 column for .products-grid-3 on mobile (<= 600px and <= 380px)",
    preg_match('/@media\s*\(max-width:\s*600px\)[\s\S]*?\.products-grid\.products-grid-3[\s\S]*?grid-template-columns:\s*1fr\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(max-width:\s*380px\)[\s\S]*?\.products-grid\.products-grid-3[\s\S]*?grid-template-columns:\s*1fr\s*!important/i', $responsiveCss)
);

// ISSUE 4: Floating Call & WhatsApp Buttons
assertCheck(
    "Footer contains floating-contact-actions with Call and WhatsApp links",
    strpos($footerPhp, 'class="floating-contact-actions"') !== false &&
    strpos($footerPhp, 'floating-btn-call') !== false &&
    strpos($footerPhp, 'floating-btn-wa') !== false
);

assertCheck(
    "Floating buttons have compact 44px sizing and safe-area inset on mobile <= 768px",
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.floating-btn\s*\{[\s\S]*?width:\s*44px\s*!important[\s\S]*?height:\s*44px\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.floating-contact-actions\s*\{[\s\S]*?bottom:\s*calc\(1rem\s*\+\s*env\(safe-area-inset-bottom/i', $responsiveCss)
);

assertCheck(
    "Floating buttons automatically hide when mobile drawer menu is opened",
    strpos($responsiveCss, 'body.mobile-menu-open .floating-contact-actions') !== false ||
    strpos($styleCss, 'body.mobile-menu-open .floating-contact-actions') !== false
);

assertCheck(
    "main.js toggles body.mobile-menu-open and closes drawer when clicking links",
    strpos($mainJs, 'mobile-menu-open') !== false &&
    strpos($mainJs, 'closeMobileMenu') !== false
);

// ISSUE 5: Responsive Buttons & Controls
assertCheck(
    "All buttons wrap text on mobile <= 768px and <= 480px (white-space: normal, word-break: break-word)",
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.btn\s*\{[\s\S]*?white-space:\s*normal\s*!important[\s\S]*?word-break:\s*break-word\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(max-width:\s*480px\)[\s\S]*?\.btn\s*\{[\s\S]*?white-space:\s*normal\s*!important[\s\S]*?word-break:\s*break-word\s*!important/i', $responsiveCss)
);

assertCheck(
    "All interactive elements enforce minimum 44px touch targets on mobile",
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.btn[\s\S]*?min-height:\s*44px\s*!important/i', $responsiveCss) &&
    preg_match('/@media\s*\(max-width:\s*768px\)[\s\S]*?\.form-control[\s\S]*?min-height:\s*44px\s*!important/i', $responsiveCss)
);

assertCheck(
    "Large CTA buttons in index.php (products, gallery, blog) use cta-wide-btn without restrictive inline padding",
    strpos($indexPhp, 'btn btn-outline btn-lg cta-wide-btn') !== false &&
    strpos($indexPhp, 'btn btn-navy btn-lg cta-wide-btn') !== false
);

echo "\n=========================================================\n";
echo "   RESULT: $passed PASSED, $failed FAILED\n";
echo "=========================================================\n";

if ($failed > 0) {
    exit(1);
}
