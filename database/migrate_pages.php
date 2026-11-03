<?php
/**
 * Migration: Create and seed 'pages' table for CMS
 */
require_once __DIR__ . '/../includes/database.php';

try {
    $db = Database::getInstance();

    $db->exec("CREATE TABLE IF NOT EXISTS pages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        content TEXT NOT NULL,
        meta_title TEXT DEFAULT NULL,
        meta_description TEXT DEFAULT NULL,
        is_published INTEGER NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    $initialPages = [
        [
            'title' => 'About Us',
            'slug' => 'about-us',
            'meta_title' => 'About OrderPrintex | Industrial DTF & UV DTF Printing Delhi',
            'meta_description' => 'Discover OrderPrintex: Delhi premier DTF and UV DTF printing manufacturing facility equipped with dual-head printers, optical plotters, and X-Rite spectrophotometers.',
            'content' => 'OrderPrintex is India\'s premier industrial DTF and UV DTF contract printing facility based in Delhi NCR. We specialize in commercial-grade heat transfers, custom garment branding, holographic stickers, and hard-surface industrial decals.'
        ],
        [
            'title' => 'Contact Us',
            'slug' => 'contact',
            'meta_title' => 'Contact OrderPrintex Prepress & Production Desk Delhi',
            'meta_description' => 'Reach our prepress and production desk via phone +91 93540 28199, WhatsApp, or email sales@orderprintex.com. Factory located in Patparganj Industrial Area, Delhi.',
            'content' => 'Get in touch with our prepress technicians for custom volume quotes, sample kits, and artwork verification. Our factory operates Monday to Saturday from 9:30 AM to 7:00 PM IST.'
        ],
        [
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'meta_title' => 'Privacy Policy | OrderPrintex Data Protection',
            'meta_description' => 'Learn how OrderPrintex protects your personal information, design files, and order details.',
            'content' => 'At OrderPrintex, we respect your privacy and protect your intellectual property. All customer design files, vector logos, and proprietary artwork are held in strict commercial confidence and never shared with third parties.'
        ],
        [
            'title' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'meta_title' => 'Terms & Conditions | OrderPrintex Commercial Policies',
            'meta_description' => 'Review the official terms of service, payment milestones, prepress proofs, and delivery guidelines for OrderPrintex.',
            'content' => 'By placing a custom printing order with OrderPrintex, clients agree to digital prepress proof confirmation before production. All invoices include 18% GST and standard factory terms apply.'
        ],
        [
            'title' => 'Shipping & Delivery Policy',
            'slug' => 'shipping-policy',
            'meta_title' => 'PAN-India Shipping Policy & Dispatch | OrderPrintex',
            'meta_description' => 'Information on our 24-48 hour dispatch speed, express air cargo, protective tube packaging, and real-time courier tracking.',
            'content' => 'We dispatch gang sheets within 24–48 hours across PAN-India via BlueDart, DTDC, and Delhivery Air. Standard moisture-barrier packaging ensures zero damage during monsoon and transit.'
        ]
    ];

    foreach ($initialPages as $pg) {
        $exists = db_fetch_one("SELECT id FROM pages WHERE slug = :s", ['s' => $pg['slug']]);
        if (!$exists) {
            db_insert('pages', $pg);
            echo "Created page: {$pg['title']} ({$pg['slug']})\n";
        }
    }

    echo "Pages migration complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
