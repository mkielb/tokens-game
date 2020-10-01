<?php

namespace spec\App\TokensGame;

use PhpSpec\ObjectBehavior;

class PositionReaderSpec extends ObjectBehavior
{
    function it_returns_correct_random_position()
    {
        $this->getRandom(4, 5)->beAnInstanceOf('App/TokensGame/Position');
    }
}
