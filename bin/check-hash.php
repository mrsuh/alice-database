<?php

require_once __DIR__ . '/util.php';

$f      = fopen($argv[1], 'r');
$unique = [];
fgetcsv($f);
while ($line = fgetcsv($f)) {
    $text          = $line[1];
    $unique[$text] = true;
}

$map = [];
foreach ($unique as $text => $val) {
    $index = Util::textHash($text);
    if (!array_key_exists($index, $map)) {
        $map[$index] = 0;
    }
    $map[$index]++;
}

function showPercentile(array $map, int $percentile): void
{
    $values = array_values($map);
    sort($values);
    $count        = count($values);
    $percentile50 = (int)floor($percentile * $count / 100);
    echo "duplicate key count percentile $percentile: {$values[$percentile50]}" . PHP_EOL;
}

showPercentile($map, 30);
showPercentile($map, 40);
showPercentile($map, 50);
showPercentile($map, 75);
showPercentile($map, 90);
showPercentile($map, 95);
showPercentile($map, 99);

echo PHP_EOL;
echo 'total unique keys: ' . count($map) . PHP_EOL;
echo 'max duplicate key count: ' . max($map) . PHP_EOL;
echo 'max hash number: ' . max(array_keys($map)) . PHP_EOL;
