<?php

require_once __DIR__ . '/util.php';

$dataResource = fopen(Util::databaseFilePath(), 'r');

$searchType = $argv[1];
$needle     = str_replace('\\', '', $argv[2]);

switch ($searchType) {
    case 'id':
        $idxOffset     = Util::idHash((int)$needle) * 4;
        $idIdxResource = fopen(Util::idIdxFilePath(), 'rb');
        fseek($idIdxResource, $idxOffset);
        $dataOffset = Util::unPackUInt32(fread($idIdxResource, 4));
        break;
    case 'text':
        $idxOffset       = Util::textHash($needle) * 4;
        $textIdxResource = fopen(Util::textIdxFilePath(), 'rb');
        fseek($textIdxResource, $idxOffset);
        $dataOffset = Util::unPackUInt32(fread($textIdxResource, 4));
        break;
    default:
        throw new \Exception('Invalid type');
}

fseek($dataResource, $dataOffset);

$textSize = Util::unPackUInt32(fread($dataResource, 4));
$text     = Util::unPackString(fread($dataResource, $textSize));

switch ($searchType) {
    case 'id':
        echo $text . PHP_EOL;
        break;
    case 'text':
        $idsCount = Util::unPackUInt32(fread($dataResource, 4));
        for ($i = 0; $i < $idsCount; $i++) {
            $id = Util::unPackUInt32(fread($dataResource, 4));
            echo $id . PHP_EOL;
        }
        break;
}
