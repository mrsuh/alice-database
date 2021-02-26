<?php

$bookContent = file_get_contents($argv[1]);
$bookLines   = [];
foreach (explode(PHP_EOL, $bookContent) as $line) {
    $line = trim(str_replace(PHP_EOL, '', $line));
    if (!empty($line)) {
        $bookLines[] = $line;
    }
}

$bookLinesCount = count($bookLines);

$f = fopen($argv[2], 'w+');

$buffer = fopen('php://memory', 'rw');
fputcsv($buffer, ['id', 'text']);

$limit  = 10000000;
$bucket = 1000000;
$range  = range(0, $limit);
shuffle($range);
for ($i = 0; $i < $limit; $i++) {
    fputcsv($buffer, [$range[$i], $bookLines[$i % $bookLinesCount]]);
    if ($i % $bucket === 0) {
        fseek($buffer, 0);
        stream_copy_to_stream($buffer, $f);
        fflush($f);
        ftruncate($buffer, 0);
        echo $i . PHP_EOL;
    }
}

fseek($buffer, 0);
stream_copy_to_stream($buffer, $f);
fclose($buffer);
fclose($f);
