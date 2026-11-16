<?php
require_once __DIR__ . '/test_independent_roots.php';

$allowedSubTypes = [
    'PostalAddress', 'ImageObject', 'Offer', 'GeoCoordinates', 
    'OpeningHoursSpecification', 'ListItem', 'Question', 'Answer', 
    'Brand', 'Country', 'SearchAction', 'EntryPoint', 'AggregateRating', 'Person'
];

$keys = [
    'home', 'about', 'services', 'services-dtf-printing', 'services-uv-dtf-printing',
    'services-t-shirt-printing', 'services-corporate-printing', 'services-custom-hoodie-printing',
    'services-uv-printing', 'services-mug-cup-printing', 'products', 'product-details',
    'gallery', 'blog', 'blog-details', 'contact', 'faq', 'pricing', 'get-quote'
];

function check_nested_entities($data, $path, &$violations, $allowedSubTypes) {
    if (!is_array($data)) return;
    foreach ($data as $k => $v) {
        $currPath = $path ? "$path.$k" : $k;
        if (is_array($v)) {
            if (isset($v['@type'])) {
                $subType = $v['@type'];
                if (!in_array($subType, $allowedSubTypes)) {
                    $violations[] = "Violating full nested entity: $currPath with @type: $subType";
                }
            }
            check_nested_entities($v, $currPath, $violations, $allowedSubTypes);
        }
    }
}

$allPass = true;
foreach ($keys as $k) {
    $entities = build_independent_entities($k);
    $violations = [];
    foreach ($entities as $idx => $entity) {
        $rootType = $entity['@type'] ?? 'Unknown';
        check_nested_entities($entity, "$k.[#$idx $rootType]", $violations, $allowedSubTypes);
    }
    if (!empty($violations)) {
        $allPass = false;
        echo "FAIL on $k:\n";
        foreach ($violations as $viol) {
            echo "  - $viol\n";
        }
    } else {
        echo "PASS: $k (0 nested full entities)\n";
    }
}

if ($allPass) {
    echo "\nALL 19 KEYS PASSED: ZERO nested full entities across all pages!\n";
}
