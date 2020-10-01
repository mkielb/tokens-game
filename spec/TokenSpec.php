<?php

namespace spec\App\TokensGame;

use App\TokensGame\Exception\TokenException;
use PhpSpec\ObjectBehavior;

class TokenSpec extends ObjectBehavior
{
    function it_is_not_revealed_after_initialization()
    {
        $this->isRevealed()->shouldReturn(false);
    }

    function it_is_revealed_after_reveal()
    {
        $this->reveal()->isRevealed()->shouldReturn(true);
    }

    function it_throws_an_exception_after_a_double_reveal_because_token_already_revealed()
    {
        $this->reveal()
            ->shouldThrow(
                new TokenException('Token is already revealed')
            )->during('reveal');
    }

    function it_throws_an_exception_because_token_is_not_revealed()
    {
        $this->shouldThrow(
            new TokenException('Token is not revealed')
        )->during('isWinner');
    }

    function it_is_not_winner()
    {
        $this->reveal()->isWinner()->shouldReturn(false);
    }

    function it_is_winner()
    {
        $this->beConstructedWith(true);
        $this->reveal()->isWinner()->shouldReturn(true);
    }
}
