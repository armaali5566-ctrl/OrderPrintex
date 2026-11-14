<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo_helper.php';

// Let's test what base nodes look like
$baseNodes = [
    [
        '@type' => 'Organization',
        '@id' => url('#organization'),
        'name' => 'OrderPrintex',
        'alternateName' => 'PRINTEX Delhi',
        'url' => url(''),
        'logo' => [
            '@type' => 'ImageObject',
            '@id' => url('#logo'),
            'url' => url('assets/images/logo.png'),
            'caption' => 'OrderPrintex Logo'
        ],
        'image' => url('assets/images/dtf_fabric_print.jpg'),
        'telephone' => '+919354028199',
        'email' => 'sales@orderprintex.com',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh',
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'sameAs' => [
            'https://www.instagram.com/order_printex',
            'https://www.facebook.com/orderprintex',
            'https://www.youtube.com/@orderprintex',
            'https://www.linkedin.com/company/orderprintex'
        ]
    ],
    [
        '@type' => 'LocalBusiness',
        '@id' => url('#localbusiness'),
        'name' => 'OrderPrintex Delhi Printing Facility',
        'image' => url('assets/images/dtf_fabric_print.jpg'),
        'url' => url(''),
        'telephone' => '+919354028199',
        'email' => 'sales@orderprintex.com',
        'priceRange' => '₹₹',
        'parentOrganization' => [
            '@id' => url('#organization')
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh',
            'addressLocality' => 'New Delhi',
            'addressRegion' => 'Delhi',
            'postalCode' => '110059',
            'addressCountry' => 'IN'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 28.6180,
            'longitude' => 77.0805
        ],
        'openingHoursSpecification' => [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'opens' => '09:30',
                'closes' => '20:00'
            ]
        ]
    ],
    [
        '@type' => 'WebSite',
        '@id' => url('#website'),
        'name' => 'OrderPrintex',
        'url' => url(''),
        'publisher' => [
            '@id' => url('#organization')
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => url('products.php?search={search_term_string}')
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ]
];

echo "Base nodes count: " . count($baseNodes) . "\n";
echo "Types: " . implode(', ', array_column($baseNodes, '@type')) . "\n";
