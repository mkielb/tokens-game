<?php

namespace App\TokensGame;

use Throwable;
use App\TokensGame\Exception\BoardException;

class Board
{
    private const DEFAULT_NUMBER_OF_FIELDS_HORIZONTALLY = 5;
    private const DEFAULT_NUMBER_OF_FIELDS_VERTICALLY = 5;

    /**
     * @var PositionReader
     */
    private $positionReader;

    /**
     * @var int
     */
    private $numberOfFieldsHorizontally;

    /**
     * @var int
     */
    private $numberOfFieldsVertically;

    /**
     * @var array
     */
    private $fields = [];

    public function __construct(
        PositionReader $positionReader,
        int $numberOfFieldsHorizontally = self::DEFAULT_NUMBER_OF_FIELDS_HORIZONTALLY,
        int $numberOfFieldsVertically = self::DEFAULT_NUMBER_OF_FIELDS_VERTICALLY
    ) {
        $this->validateSizes($numberOfFieldsHorizontally, $numberOfFieldsVertically);

        $this->positionReader = $positionReader;
        $this->numberOfFieldsHorizontally = $numberOfFieldsHorizontally;
        $this->numberOfFieldsVertically = $numberOfFieldsVertically;

        $this->initializeFields();
    }

    private function validateSizes(int $numberOfFieldsHorizontally, int $numberOfFieldsVertically)
    {
        if ($numberOfFieldsHorizontally < 1) {
            throw new BoardException('Number of fields horizontally must be greater than 0');
        }

        if ($numberOfFieldsVertically < 1) {
            throw new BoardException('Number of fields vertically must be greater than 0');
        }
    }

    private function initializeFields(): void
    {
        $randomPosition = $this->positionReader->getRandom(
            $this->getNumberOfFieldsHorizontally(),
            $this->getNumberOfFieldsVertically()
        );

        for ($x = 1; $x <= $this->getNumberOfFieldsHorizontally(); $x++) {
            for ($y = 1; $y <= $this->getNumberOfFieldsVertically(); $y++) {
                $this->fields[$x - 1][$y - 1] = $this->initializeField(new Position($x, $y), $randomPosition);
            }
        }
    }

    private function initializeField(Position $position, Position $randomPosition): Field
    {
        return new Field(
            new Token(
                $position->getX() === $randomPosition->getX()
                && $position->getY() === $randomPosition->getY()
            )
        );
    }

    public function getNumberOfFieldsHorizontally(): int
    {
        return $this->numberOfFieldsHorizontally;
    }

    public function getNumberOfFieldsVertically(): int
    {
        return $this->numberOfFieldsVertically;
    }

    public function getField(Position $position): Field
    {
        $field = null;

        try {
            $field = $this->fields[$position->getX() - 1][$position->getY() - 1];
        } catch (Throwable $e) {
        }

        if (!$field instanceof Field) {
            throw new BoardException('The field does not exist');
        }

        return $field;
    }
}
