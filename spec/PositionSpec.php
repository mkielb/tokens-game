<?php

namespace spec\App\TokensGame;

use App\TokensGame\Exception\PositionException;
use PhpSpec\ObjectBehavior;

class PositionSpec extends ObjectBehavior
{
    function it_throws_exception_when_x_is_less_than_1()
    {
        $this->beConstructedWith(0, 2);
        $this->shouldThrow(
            new PositionException('X must be greater than 0')
        )->duringInstantiation();
    }

    function it_throws_exception_when_y_is_less_than_1()
    {
        $this->beConstructedWith(2, 0);
        $this->shouldThrow(
            new PositionException('Y must be greater than 0')
        )->duringInstantiation();
    }

    function it_returns_correct_position()
    {
        $this->beConstructedWith(1, 2);
        $this->getX()->shouldReturn(1);
        $this->getY()->shouldReturn(2);
    }
}
