<?php

namespace spec\App\TokensGame;

use App\TokensGame\Token;
use PhpSpec\ObjectBehavior;

class FieldSpec extends ObjectBehavior
{
    function let(Token $token, $position)
    {
        $this->beConstructedWith($token);
    }

    function it_returns_the_token($token)
    {
        $this->getToken()->shouldReturn($token);
    }
}
