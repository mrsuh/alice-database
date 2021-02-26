<?php

require_once __DIR__ . '/util.php';

$idIdxResource        = fopen(Util::idIdxFilePath(), 'wb');
$textIdxResource      = fopen(Util::textIdxFilePath(), 'wb');
$dataResource         = fopen(Util::databaseFilePath(), 'w');
$originalFileResource = fopen($argv[1], 'r');

$dataMap      = [];
$tmpDirectory = sys_get_temp_dir();

fgetcsv($originalFileResource);//skip first line
while ($line = fgetcsv($originalFileResource)) {
    if (!is_array($line)) {
        continue;
    }

    $id   = (int)$line[0];
    $text = $line[1];

    if (!array_key_exists($text, $dataMap)) {
        $filePath       = $tmpDirectory . '/' . Util::textHash($text);
        $dataMap[$text] = fopen($filePath, 'wb+');
    }

    fwrite($dataMap[$text], Util::packUInt32($id));
}

foreach ($dataMap as $text => $resource) {

    $textSize   = strlen($text);
    $idsCount   = ftell($resource) / 4;
    $dataOffset = ftell($dataResource);

    $dataOffsetBin = Util::packUInt32($dataOffset);

    fwrite($dataResource, Util::packUInt32($textSize));
    fwrite($dataResource, Util::packString($text));
    fwrite($dataResource, Util::packUInt32($idsCount));

    fseek($resource, 0);
    stream_copy_to_stream($resource, $dataResource);

    $textIdxOffset = Util::textHash($text) * 4;
    fseek($textIdxResource, $textIdxOffset);
    fwrite($textIdxResource, $dataOffsetBin);

    fseek($resource, 0);
    for ($i = 0; $i < $idsCount; $i++) {

        $id = Util::unPackUInt32(fread($resource, 4));

        $idIdxOffset = Util::idHash($id) * 4;
        fseek($idIdxResource, $idIdxOffset);
        fwrite($idIdxResource, $dataOffsetBin);
    }

    fclose($resource);
    $filePath = $tmpDirectory . '/' . Util::textHash($text);
    if (is_file($filePath)) {
        unlink($filePath);
    }
}

fclose($originalFileResource);
fclose($idIdxResource);
fclose($textIdxResource);
fclose($dataResource);
