<?php

namespace App\TokensGame;

use App\TokensGame\Exception\PositionException;

class Position
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->validate($x, $y);

        $this->x = $x;
        $this->y = $y;
    }

    private function validate(int $x, int $y)
    {
        if ($x < 1) {
            throw new PositionException('X must be greater than 0');
        }

        if ($y < 1) {
            throw new PositionException('Y must be greater than 0');
        }
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
