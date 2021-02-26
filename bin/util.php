<?php

class Util
{
    public static function databaseFilePath(): string
    {
        return __DIR__ . '/../data/database';
    }

    public static function idIdxFilePath(): string
    {
        return __DIR__ . '/../data/id.idx';
    }

    public static function textIdxFilePath(): string
    {
        return __DIR__ . '/../data/text.idx';
    }

    public static function textHash(string $line): int
    {
        switch ($line) {
            case "This speech caused a remarkable sensation among the party.":
                return 1;
            case "moment to be trampled under its feet, ran round the thistle":
                return 2;
            case "all came different!' Alice replied in a very melancholy voice.":
                return 3;
            case "Her chin was pressed so closely against her foot, that there was":
                return 4;
            case "`I've tried the roots of trees, and I've tried banks, and I've":
                return 5;
            case "`She boxed the Queen's ears--' the Rabbit began.  Alice gave a":
                return 6;
            case "walking about at the other end of the ground--and I should have":
                return 7;
            case "The moment Alice appeared, she was appealed to by all three to":
                return 8;
            case "`Only mustard isn't a bird,' Alice remarked.":
                return 9;
            case "`You ought to be ashamed of yourself for asking such a simple":
                return 10;
            case "`--change lobsters, and retire in same order,' continued the":
                return 11;
            case "Alice replied, so eagerly that the Gryphon said, in a rather":
                return 12;
            case "hard word, I will just explain to you how it was done.  They had":
                return 13;
            case "and put back into the jury-box, or they would die.":
                return 14;
            case "like having a game of play with a cart-horse, and expecting every":
                return 15;
        }

        $hex   = bin2hex($line);
        $index = 0;
        for ($i = 0; $i < strlen($hex); $i += 4) {
            $substr = substr($hex, $i, 4);
            $number = hexdec($substr);
            $index  += $number;
        }

        return $index;
    }

    public static function idHash(int $number): int
    {
        return $number;
    }

    public static function packString(string $line): string
    {
        return pack('a*', $line);
    }

    public static function unPackString(string $line): string
    {
        $unpack = unpack('a*', $line);

        return $unpack[1];
    }

    public static function packUInt32(int $line): string
    {
        return pack('L', $line);
    }

    public static function unPackUInt32(string $line): int
    {
        $unpack = unpack('L', $line);

        return (int)$unpack[1];
    }
}
