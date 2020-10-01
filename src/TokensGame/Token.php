<?php

namespace App\TokensGame;

use App\TokensGame\Exception\TokenException;

class Token
{
    /**
     * @var bool
     */
    private $revealed = false;

    /**
     * @var bool
     */
    private $winner;

    public function __construct(bool $winner = false)
    {
        $this->winner = $winner;
    }

    public function reveal(): self
    {
        if ($this->isRevealed()) {
            throw new TokenException('Token is already revealed');
        }

        $this->revealed = true;

        return $this;
    }

    public function isRevealed(): bool
    {
        return $this->revealed;
    }

    public function isWinner(): bool
    {
        if (!$this->isRevealed()) {
            throw new TokenException('Token is not revealed');
        }

        return $this->winner;
    }
}
