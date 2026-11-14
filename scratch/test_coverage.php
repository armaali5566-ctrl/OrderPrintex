<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

function test_build_nodes_for_all() {
    $expectedTable = [
        'home' => ['LocalBusiness', 'WebSite', 'ItemList', 'Service', 'Organization'],
        'about' => ['AboutPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Service', 'Organization'],
        'services-dtf-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'services-uv-dtf-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'services-t-shirt-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'services-corporate-printing' => ['Service', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'services' => ['CollectionPage', 'ItemList', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Service', 'Organization'],
        'products' => ['CollectionPage', 'ItemList', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Product', 'Organization'],
        'product-details' => ['Product', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'gallery' => ['CollectionPage', 'ImageGallery', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'blog' => ['Blog', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'blog-details' => ['BlogPosting', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'contact' => ['ContactPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization'],
        'faq' => ['FAQPage', 'BreadcrumbList', 'LocalBusiness', 'WebSite', 'Organization']
    ];

    echo "Testing coverage for all " . count($expectedTable) . " page types...\n";
}

test_build_nodes_for_all();
