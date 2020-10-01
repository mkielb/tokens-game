<?php

namespace spec\App\TokensGame;

use App\TokensGame\Exception\BoardException;
use App\TokensGame\Position;
use App\TokensGame\PositionReader;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BoardSpec extends ObjectBehavior
{
    function let(PositionReader $positionReader) {
        $positionReader->beADoubleOf('App\TokensGame\PositionReader');
        $positionReader->getRandom(Argument::type('integer'), Argument::type('integer'))->willReturn(
            new Position(1,2)
        );
    }

    function it_throws_exception_when_number_of_fields_horizontally_is_less_than_1($positionReader)
    {
        $this->beConstructedWith($positionReader, 0, 2);

        $this->shouldThrow(
            new BoardException('Number of fields horizontally must be greater than 0')
        )->duringInstantiation();
    }

    function it_throws_exception_when_number_of_fields_vertically_is_less_than_1($positionReader)
    {
        $this->beConstructedWith($positionReader, 2, 0);

        $this->shouldThrow(
            new BoardException('Number of fields vertically must be greater than 0')
        )->duringInstantiation();
    }

    function it_returns_default_size($positionReader)
    {
        $this->beConstructedWith($positionReader);
        $this->getNumberOfFieldsHorizontally()->shouldReturn(5);
        $this->getNumberOfFieldsVertically()->shouldReturn(5);
    }

    function it_returns_defined_size($positionReader)
    {
        $this->beConstructedWith($positionReader, 2, 2);

        $this->getNumberOfFieldsHorizontally()->shouldReturn(2);
        $this->getNumberOfFieldsVertically()->shouldReturn(2);
    }

    function it_throws_exception_when_the_field_does_not_exist($positionReader)
    {
        $this->beConstructedWith($positionReader, 2, 2);
        $position = new Position(3, 3);

        $this->shouldThrow(
            new BoardException('The field does not exist')
        )->during('getField', [$position]);
    }

    function it_returns_field_with_token($positionReader)
    {
        $this->beConstructedWith($positionReader, 2, 2);
        $position = new Position(1, 1);
        $field = $this->getField($position);

        $field->shouldReturnAnInstanceOf('App\TokensGame\Field');
        $field->getToken()->shouldReturnAnInstanceOf('App\TokensGame\Token');
    }

    function it_has_one_winning_token($positionReader)
    {
        $result = false;
        $numberOfFieldHorizontally = 2;
        $numberOfFieldVertically = 2;

        $this->beConstructedWith($positionReader, $numberOfFieldHorizontally, $numberOfFieldVertically);

        for ($x = 1; $x <= $numberOfFieldHorizontally; $x++) {
            for ($y = 1; $y <= $numberOfFieldVertically; $y++) {
                $position = new Position($x, $y);
                $token = $this->getField($position)->getToken();
                $token->reveal();

                if ($token->isWinner()) {
                    $result = true;
                    break 2;
                }
            }
        }

        if (!$result) {
            throw new FailureException('There is no winning token');
        }
    }
}
