<?php
function cartesian_product(array $arrays): array {
    $result = [[]];
    foreach ($arrays as $key => $values) {
        $append = [];
        foreach ($result as $product) {
            foreach ($values as $item) {
                $product[$key] = $item;
                $append[] = $product;
            }
        }
        $result = $append;
    }
    return $result;
}

$input = [
    'size' => [['id' => 1, 'val' => 'S'], ['id' => 2, 'val' => 'M']],
    'color' => [['id' => 18, 'val' => 'Black'], ['id' => 19, 'val' => 'White']]
];

$combos = cartesian_product($input);
echo "Total combinations: " . count($combos) . "\n";
foreach ($combos as $c) {
    echo $c['size']['val'] . ' + ' . $c['color']['val'] . "\n";
}
