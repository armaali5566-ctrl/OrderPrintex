<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

$productId = 3;

$variations = db_fetch_all("
    SELECT * FROM product_variations
    WHERE product_id = :pid AND is_active = 1
    ORDER BY id ASC
", ['pid' => $productId]);

$productAttributes = [];
$variationData = [];

if (!empty($variations)) {
    $attrs = db_fetch_all("
        SELECT a.id, a.name, a.slug
        FROM product_attributes pa
        JOIN attributes a ON pa.attribute_id = a.id
        WHERE pa.product_id = :pid
        ORDER BY pa.display_order ASC, a.id ASC
    ", ['pid' => $productId]);

    foreach ($attrs as $attr) {
        $values = db_fetch_all("
            SELECT DISTINCT av.id, av.value, av.color_code, av.display_order
            FROM attribute_values av
            JOIN product_variation_values pvv ON av.id = pvv.attribute_value_id
            JOIN product_variations pv ON pvv.variation_id = pv.id
            WHERE pv.product_id = :pid AND av.attribute_id = :aid AND pv.is_active = 1
            ORDER BY av.display_order ASC, av.id ASC
        ", ['pid' => $productId, 'aid' => $attr['id']]);

        if (!empty($values)) {
            $productAttributes[] = [
                'id' => $attr['id'],
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'values' => $values
            ];
        }
    }

    foreach ($variations as $var) {
        $valRows = db_fetch_all("
            SELECT av.attribute_id, av.id as val_id
            FROM product_variation_values pvv
            JOIN attribute_values av ON pvv.attribute_value_id = av.id
            WHERE pvv.variation_id = :vid
        ", ['vid' => $var['id']]);

        $attrMap = [];
        foreach ($valRows as $vr) {
            $attrMap[$vr['attribute_id']] = (int)$vr['val_id'];
        }

        $variationData[] = [
            'id' => (int)$var['id'],
            'sku' => $var['sku'],
            'regular_price' => (float)$var['regular_price'],
            'sale_price' => $var['sale_price'] !== null ? (float)$var['sale_price'] : null,
            'stock_quantity' => (int)$var['stock_quantity'],
            'is_in_stock' => (int)$var['is_in_stock'],
            'image_url' => $var['image_url'],
            'attributes' => $attrMap
        ];
    }
}

echo "Found " . count($productAttributes) . " attributes and " . count($variationData) . " variations.\n";
foreach ($productAttributes as $pa) {
    echo " - Attribute: " . $pa['name'] . " (" . count($pa['values']) . " values)\n";
    foreach ($pa['values'] as $v) {
        echo "   * {$v['value']} (Color: " . ($v['color_code'] ?? 'none') . ")\n";
    }
}
echo "Sample variation: " . json_encode($variationData[0]) . "\n";
