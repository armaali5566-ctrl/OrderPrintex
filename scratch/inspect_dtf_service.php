<?php
$lines = file('services/dtf-printing.php');
echo "Total lines: " . count($lines) . "\n";
echo "First 50 lines:\n";
for ($i = 0; $i < 50; $i++) {
    echo ($i + 1) . ': ' . $lines[$i];
}
