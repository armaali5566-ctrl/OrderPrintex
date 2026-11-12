import sys
import re
import subprocess
import json

sys.stdout.reconfigure(encoding='utf-8')

with open(r'c:\Movies\Internship\OrderPrintex\scratch\extracted_prompt.txt', 'r', encoding='utf-8') as f:
    prompt_text = f.read()

sections = re.split(r'={5,}\n(\d+\..*?)\n={5,}', prompt_text)
# sections:
# 1: "1. SERVICES OVERVIEW PAGE..." -> 2: content
# 3: "2. DTF PRINTING DETAIL PAGE..." -> 4: content
# 5: "3. UV DTF PRINTING DETAIL PAGE..." -> 6: content
# 7: "4. CORPORATE PRINTING DETAIL PAGE..." -> 8: content
# 9: "5. PRODUCTS / SHOP PAGE..." -> 10: content
# 11: "6. PORTFOLIO / PRINT GALLERY PAGE..." -> 12: content
# 13: "7. BLOG LISTING PAGE..." -> 14: content
# 15: "8. CONTACT, GET-QUOTE & CART PAGES..." -> 16: content
# 17: "9. BLOG ARTICLES..." -> 18: content

failures = []

def check(file_path, search_str, label):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    if search_str not in content:
        failures.append(f"FAILED [{label}] in {file_path}:\n  Missing: '{search_str[:80]}...'")
    else:
        print(f"PASSED [{label}]")

print("--- VERIFYING SECTION 1: SERVICES OVERVIEW ---")
check('services.php', 'Printing Services in Delhi | DTF, UV DTF, T-Shirt | Printex', 'S1 Meta Title')
check('services.php', "DTF Printing, UV DTF Stickers, T-Shirt, Hoodie, and Mug Printing in Delhi - We've got you covered. Printex is the go to for brands, shops and businesses across India . We'll get you a quote in no time.", 'S1 Meta Desc')
check('services.php', 'Our Printing Services in Delhi', 'S1 Hero Eyebrow')
check('services.php', 'DTF, UV DTF &amp; T-Shirt Printing Services in Delhi', 'S1 Hero H1')
check('services.php', "At Printex we print DTF transfers, UV DTF stickers, t-shirts, hoodies and mugs from our studio in Pankha Road, Delhi. Whether you're looking to order a small sample or a bulk batch , we deliver across India.", 'S1 Hero Desc')
check('services.php', '1.DTF Printing in Delhi', 'S1 Card 1 Title')
check('services.php', 'Prints on Fabric with Bright, Soft, and Long Lasting Results', 'S1 Card 1 Tagline')
check('services.php', '2. UV DTF Printing - A Popular Choice', 'S1 Card 2 Title')
check('services.php', '3. T-Shirt Printing for Bulk Orders', 'S1 Card 3 Title')
check('services.php', '4. Corporate Printing Services for Businesses', 'S1 Card 4 Title')

print("\n--- VERIFYING SECTION 2: DTF PRINTING DETAIL ---")
check('services/dtf-printing.php', 'DTF Printing in Delhi | Bulk DTF Transfers & Gang Sheets – Printex', 'S2 Meta Title')
check('services/dtf-printing.php', 'DTF printing in Delhi by Printex, a DTF printing manufacturer in India. Bulk DTF transfers and gang sheets on 22 & 24 inch rolls. Upload your design for a quote.', 'S2 Meta Desc')
check('services/dtf-printing.php', 'DTF Printing Services in Delhi', 'S2 Eyebrow')
check('services/dtf-printing.php', 'DTF Printing in Delhi - Making Your Designs Pop with Long-Lasting Results', 'S2 H1')
check('services/dtf-printing.php', 'What\'s All this About DTF Printing (and how does it work?)', 'S2 H2 What\'s All')
check('services/dtf-printing.php', 'DTF vs Screen Printing vs Vinyl: Which Is Better?', 'S2 Table H2')
check('services/dtf-printing.php', 'Ten pieces in six colours? DTF is the clear winner.', 'S2 Table Note')
check('services/dtf-printing.php', 'DTF Gang Sheets &amp; Bulk DTF Transfers - Make the Most of Your Ink', 'S2 Gang Sheets')
check('services/dtf-printing.php', 'How Printex Do DTF', 'S2 How Printex Do')
check('services/dtf-printing.php', 'Why Choose Printex for DTF Printing in Delhi - We\'ve Got You Covered', 'S2 Why Choose')
check('services/dtf-printing.php', 'How to Press a DTF Transfer - Heat, Time and a Bit of Pressure', 'S2 How to Press')
check('services/dtf-printing.php', 'What\'s the minimum order I need to place?', 'S2 FAQ 1')
check('services/dtf-printing.php', 'What\'s the difference between DTF and UV DTF printing?', 'S2 FAQ 8')
check('services/dtf-printing.php', 'Get a Quote for DTF Printing in Delhi', 'S2 CTA Heading')
check('services/dtf-printing.php', 'DTF Printing in Delhi: Come visit us', 'S2 Visit Studio')
check('services/dtf-printing.php', 'Shop E-6, Chanakya Palace, 21 Street No, Pankha Rd, next to 40 Feet Road, Delhi 110059', 'S2 Studio Address')

print("\n--- VERIFYING SECTION 3: UV DTF PRINTING DETAIL ---")
check('services/uv-dtf-printing.php', 'Hard-Surface Decal Innovation', 'S3 Eyebrow')
check('services/uv-dtf-printing.php', 'Industrial UV DTF Printing in Delhi', 'S3 H1')
check('services/uv-dtf-printing.php', 'Take Your Designs To Any Surface with UV DTF Printing', 'S3 H2 Take Your Designs')
check('services/uv-dtf-printing.php', 'How Does UV DTF Printing Work?', 'S3 How Does It Work')
check('services/uv-dtf-printing.php', 'UV DTF Stickers vs Vinyl Stickers vs Regular DTF', 'S3 Comparison Table')
check('services/uv-dtf-printing.php', 'UV DTF Stickers for Glass, Tumblers, Acrylic &amp; Packaging - Where Quality Meets Flexibility', 'S3 Applications')
check('services/uv-dtf-printing.php', 'Who Turns to Printex for UV DTF Stickers', 'S3 Who Turns')
check('services/uv-dtf-printing.php', 'The Benefits of Printing with UV DTF', 'S3 Benefits')
check('services/uv-dtf-printing.php', 'How to Prepare Your Artwork for UV DTF Printing', 'S3 Prepress Guide')
check('services/uv-dtf-printing.php', 'UV DTF Decals in Any Size - One Sheet to Bulk', 'S3 Order Scale')
check('services/uv-dtf-printing.php', 'The Lowdown on UV DTF Printing at Printex', 'S3 Lowdown')
check('services/uv-dtf-printing.php', 'Why We\'re the Ones to Trust for UV DTF Printing in Delhi', 'S3 Why Trust')
check('services/uv-dtf-printing.php', 'How to Apply a UV DTF Sticker Like a Pro', 'S3 Application')
check('services/uv-dtf-printing.php', 'Frequently Asked Questions About UV DTF Printing', 'S3 FAQs')
check('services/uv-dtf-printing.php', '[MOQ confirm karo]', 'S3 MOQ Placeholder')
check('services/uv-dtf-printing.php', 'Get an Instant UV DTF Printing Quote', 'S3 CTA Heading')
check('services/uv-dtf-printing.php', 'UV DTF Printing in Delhi: Visit Our Studio', 'S3 Studio Heading')
check('services/uv-dtf-printing.php', 'Call / WhatsApp:', 'S3 Call WhatsApp Placeholder')

print("\n--- VERIFYING SECTION 4: CORPORATE PRINTING DETAIL ---")
check('services/corporate-printing.php', 'B2B Promotional Solutions', 'S4 Eyebrow')
check('services/corporate-printing.php', 'Bringing Your Brand to Life in Delhi with Custom Merchandise', 'S4 H1')
check('services/corporate-printing.php', 'Your Brand on Every Desk, Bag and Bottle - and Loving It', 'S4 H2 Your Brand')
check('services/corporate-printing.php', 'Our Core Corporate Print Categories', 'S4 Core Categories')
check('services/corporate-printing.php', '1. Custom Ceramic Mugs - A Great Way to Start the Day', 'S4 Category 1')
check('services/corporate-printing.php', '2. Onboarding Gift Kits - A Warm Welcome', 'S4 Category 2')
check('services/corporate-printing.php', '3. Uniforms and Polos - Team Spirit', 'S4 Category 3')
check('services/corporate-printing.php', '4. Event and Summit Giveaways - Memorable Swag', 'S4 Category 4')
check('services/corporate-printing.php', 'Which Corporate Merchandise Is Right for You?', 'S4 Table')
check('services/corporate-printing.php', 'Promotional Merchandise in Delhi for Every Occasion Where Quality Really Does Meet Flexibility', 'S4 Occasions')
check('services/corporate-printing.php', 'Who Turns to Printex for Their Corporate Printing in Delhi', 'S4 Who Turns')
check('services/corporate-printing.php', 'The Benefits of Choosing Printex for Corporate Merchandise Printing', 'S4 Benefits')
check('services/corporate-printing.php', 'How to Prepare Your Logo for Corporate Printing', 'S4 Prepare Logo')
check('services/corporate-printing.php', 'Corporate Printing in Delhi in Any Quantity - From Small Orders to Big', 'S4 Capacity')
check('services/corporate-printing.php', 'The Lowdown on Corporate Printing at Printex - The Inside Scoop', 'S4 Lowdown')
check('services/corporate-printing.php', 'Why You Can Trust Printex for Corporate Printing in Delhi', 'S4 Trust')
check('services/corporate-printing.php', 'Looking After Your Branded Swag', 'S4 Maintenance')
check('services/corporate-printing.php', 'Frequently Asked Questions About Corporate Printing - We\'ve Got Answers', 'S4 FAQs')
check('services/corporate-printing.php', 'Get an Instant Corporate Printing Quote', 'S4 Quote Form')
check('services/corporate-printing.php', 'Corporate Printing in Delhi: Visit Our Studio', 'S4 Studio Heading')

print("\n--- VERIFYING SECTION 5: PRODUCTS CATALOG ---")
check('products.php', 'OUR PRODUCTS', 'S5 Eyebrow')
check('products.php', 'DTF Transfers, UV Decals &amp; Custom Merchandise', 'S5 H1')
check('products.php', 'Browse ready-to-order prints, tees and branded gifts made in our Delhi workshop. Pick a product, or ask us for a custom quote.', 'S5 Hero Text')
check('products.php', 'placeholder="Search products..."', 'S5 Search Placeholder')
check('products.php', 'Get Quote', 'S5 Get Quote Button')
check('products.php', 'FOR PRINT SHOPS &amp; RESELLERS', 'S5 Wholesale Eyebrow')
check('products.php', 'Need Regular Gang Rolls for Your Print Shop?', 'S5 Wholesale Heading')
check('products.php', 'We supply screen printers, DTG studios, merchandisers and clothing labels across India with ongoing roll orders. Share your artwork and we’ll get it print-ready fast.', 'S5 Wholesale Text')
check('products.php', 'Ask About Wholesale Rates', 'S5 Wholesale Button 1')
check('products.php', 'Chat on WhatsApp', 'S5 Wholesale Button 2')
check('includes/catalog_helper.php', 'DTF Gang Sheet (22" Wide)', 'S5 Product 1')
check('includes/catalog_helper.php', '3D Gloss UV DTF Decal Sheet', 'S5 Product 2')
check('includes/catalog_helper.php', 'Oversized Streetwear T-Shirt (240 GSM)', 'S5 Product 3')
check('includes/catalog_helper.php', 'Custom Steel Thermal Bottle (750 ml)', 'S5 Product 4')
check('includes/catalog_helper.php', 'Branded Apparel Mailer Box', 'S5 Product 5')
check('includes/catalog_helper.php', 'Custom Die-Cut Sticker Pack', 'S5 Product 6')
check('includes/catalog_helper.php', 'Everyday Cotton T-Shirt (180 GSM)', 'S5 Product 7')
check('includes/catalog_helper.php', 'Corporate Welcome Gift Kit', 'S5 Product 8')
check('includes/catalog_helper.php', 'Custom Printed Bookmarks', 'S5 Product 9')
check('includes/catalog_helper.php', '350 GSM card • Double-sided print • Custom sizes', 'S5 Product 9 Specs')

print("\n--- VERIFYING SECTION 6: GALLERY ---")
check('gallery.php', 'OUR WORK', 'S6 Eyebrow')
check('gallery.php', 'Print Gallery', 'S6 H1')
check('gallery.php', 'A look at the DTF transfers, UV decals, t-shirts and corporate gifts we’ve made for brands, agencies and businesses across India.', 'S6 Hero Text')
check('gallery.php', 'Some images are sample prints. Every custom order is priced by artwork, size and quantity.', 'S6 Bottom Note')
check('gallery.php', 'PRINT QUALITY UP CLOSE', 'S6 Inspector Eyebrow')
check('gallery.php', 'Take a Closer Look at Our Prints', 'S6 Inspector Heading')
check('gallery.php', 'Check the detail, color and finish of each print. Tap a thumbnail to switch the preview.', 'S6 Inspector Subtext')
check('gallery.php', 'Detailed DTF Print on a Dark T-Shirt', 'S6 Specimen 1 Title')
check('gallery.php', 'A DTF transfer with smooth color blends, a bright white base and a soft, stretchy feel that doesn’t turn stiff.', 'S6 Specimen 1 Desc')
check('gallery.php', 'Print detail', 'S6 Specimen 1 Label 1')
check('gallery.php', 'Inks', 'S6 Specimen 1 Label 2')
check('gallery.php', 'Works on', 'S6 Specimen 1 Label 3')
check('gallery.php', 'Durability', 'S6 Specimen 1 Label 4')
check('gallery.php', 'Get a Similar Print &rarr;', 'S6 Specimen 1 Button 1')
check('gallery.php', 'View Full Screen', 'S6 Specimen 1 Button 2')
check('gallery.php', 'Tap to enlarge', 'S6 Image Button Text')
check('gallery.php', 'CHOOSE A PRINT TO PREVIEW:', 'S6 Thumbnail Label')

print("\n--- VERIFYING SECTION 7: BLOG LISTING ---")
check('blog.php', 'PRINTING GUIDES &amp; TIPS', 'S7 Eyebrow')
check('blog.php', 'Printing Guides &amp; Tips', 'S7 H1')
check('blog.php', 'Simple, practical guides on DTF, UV DTF, t-shirt and corporate printing from our Delhi team. Learn what works before you order.', 'S7 Hero Text')
check('blog.php', 'Search guides, e.g. DTF, UV decals, t-shirts', 'S7 Search Placeholder')
check('blog.php', 'READY TO PRINT?', 'S7 CTA Eyebrow')
check('blog.php', 'Ready to Start Your Print Order?', 'S7 CTA Heading')
check('blog.php', 'Send us your design and our team will check the lines, colors and background for free. There’s no obligation to order.', 'S7 CTA Text')
check('blog.php', 'Upload Your Design', 'S7 CTA Button 1')
check('blog.php', 'See Our Products', 'S7 CTA Button 2')

print("\n--- VERIFYING SECTION 8: CONTACT, QUOTE, CART ---")
check('contact.php', 'GET IN TOUCH', 'S8 Contact Eyebrow')
check('contact.php', 'Talk to Our Delhi Team', 'S8 Contact H1')
check('contact.php', 'Need a quote, help with your artwork, or a sample before a bulk order? Call, WhatsApp or send us a message, and we’ll get back to you.', 'S8 Contact Text')
check('get-quote.php', 'GET A QUOTE', 'S8 Quote Eyebrow')
check('get-quote.php', 'Get a Quote for Your Print Order', 'S8 Quote H1')
check('get-quote.php', 'Send your design and order details. We’ll check your file and reply with a clear price breakdown within 24 hours. Free, with no obligation.', 'S8 Quote Text')
check('cart.php', 'YOUR ORDER', 'S8 Cart Eyebrow')
check('cart.php', 'Your Cart', 'S8 Cart H1')
check('cart.php', 'Check your items, sizes and quantities before you checkout. Need a change? Message us on WhatsApp for help.', 'S8 Cart Text')
check('cart.php', 'GST invoice available', 'S8 Cart Badge 1')
check('cart.php', 'Delivery across India', 'S8 Cart Badge 2')
check('cart.php', 'Free file check before printing', 'S8 Cart Badge 3')
check('cart.php', 'Your cart is empty', 'S8 Cart Empty Heading')
check('cart.php', 'Looks like you haven’t added anything yet. Browse our DTF transfers, UV decals and custom tees, or send us your own design for a quote.', 'S8 Cart Empty Text')

if failures:
    print(f"\nTOTAL FAILURES: {len(failures)}")
    for f in failures:
        print(f)
else:
    print("\nALL VERIFICATION CHECKS PASSED WITH ZERO ERRORS!")

