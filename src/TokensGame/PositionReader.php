<?php

namespace App\TokensGame;

use App\TokensGame\Exception\PositionReaderException;

class PositionReader
{
    public function getRandom(int $maxX, int $maxY): Position
    {
        return new Position(
            rand(1, $maxX),
            rand(1, $maxY)
        );
    }

    public function readFromConsole(): Position
    {
        fscanf(STDIN, "%d,%d" . PHP_EOL, $x, $y);

        if (gettype($x) !== 'integer' || gettype($y) !== 'integer') {
            throw new PositionReaderException('Position reading error');
        }

        return new Position($x, $y);
    }
}
