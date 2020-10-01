<?php

namespace spec\App\TokensGame;

use App\TokensGame\Board;
use App\TokensGame\Field;
use App\TokensGame\Position;
use App\TokensGame\PositionReader;
use App\TokensGame\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class GameSpec extends ObjectBehavior
{
    function let(
        Board $board,
        PositionReader $positionReader,
        OutputInterface $output,
        Stopwatch $stopwatch,
        StopwatchEvent $stopwatchEvent
    ) {
        $board->beADoubleOf('App\TokensGame\Board');

        $positionReader->beADoubleOf('App\TokensGame\PositionReader');

        $output->beADoubleOf('Symfony\Component\Console\Output\ConsoleOutput');
        $output->writeln(Argument::type('string'))->willReturn('');

        $stopwatchEvent->beADoubleOf('Symfony\Component\Stopwatch\StopwatchEvent');

        $stopwatch->beADoubleOf('Symfony\Component\Stopwatch\Stopwatch');
    }

    function it_is_lost_because_it_has_run_out_of_time(
        $board,
        $positionReader,
        $stopwatch,
        $stopwatchEvent,
        $output
    ) {
        $board->getField(Argument::type('App\TokensGame\Position'))->willReturn(
            new Field(new Token())
        );

        $positionReader->getRandom(Argument::type('integer'), Argument::type('integer'))->willReturn(
            new Position(1,2)
        );
        $positionReader->readFromConsole()->willReturn(
            new Position(1,1)
        );

        $stopwatchEvent->getDuration()->willReturn(60000);

        $stopwatch->start(Argument::type('string'))->willReturn(true);
        $stopwatch->stop(Argument::type('string'))->willReturn($stopwatchEvent);
        $stopwatch->getEvent(Argument::type('string'))->willReturn($stopwatchEvent);

        $this->beConstructedWith(
            $board,
            $positionReader,
            $stopwatch,
            $output
        );

        $this->play()->shouldReturn(false);
    }

    function it_is_lost_because_the_number_of_attempts_ended(
        $board,
        $positionReader,
        $stopwatch,
        $stopwatchEvent,
        $output
    ) {
        $board->getField(Argument::type('App\TokensGame\Position'))->willReturn(
            new Field(new Token()),
            new Field(new Token()),
            new Field(new Token()),
            new Field(new Token()),
            new Field(new Token())
        );

        $positionReader->getRandom(Argument::type('integer'), Argument::type('integer'))->willReturn(
            new Position(1,2)
        );
        $positionReader->readFromConsole()->willReturn(
            new Position(1, 1),
            new Position(2, 2),
            new Position(3, 3),
            new Position(4, 4),
            new Position(5, 5)
        );

        $stopwatchEvent->getDuration()->willReturn(10000);

        $stopwatch->start(Argument::type('string'))->willReturn(true);
        $stopwatch->stop(Argument::type('string'))->willReturn($stopwatchEvent);
        $stopwatch->getEvent(Argument::type('string'))->willReturn($stopwatchEvent);

        $this->beConstructedWith(
            $board,
            $positionReader,
            $stopwatch,
            $output
        );

        $this->play()->shouldReturn(false);
    }

    function it_is_won(
        $board,
        $positionReader,
        $stopwatch,
        $stopwatchEvent,
        $output
    ) {
        $board->getField(Argument::type('App\TokensGame\Position'))->willReturn(
            new Field(new Token(true))
        );

        $positionReader->getRandom(Argument::type('integer'), Argument::type('integer'))->willReturn(
            new Position(1,2)
        );
        $positionReader->readFromConsole()->willReturn(
            new Position(1,1)
        );

        $stopwatchEvent->getDuration()->willReturn(0);

        $stopwatch->start(Argument::type('string'))->willReturn(true);
        $stopwatch->stop(Argument::type('string'))->willReturn($stopwatchEvent);
        $stopwatch->getEvent(Argument::type('string'))->willReturn($stopwatchEvent);

        $this->beConstructedWith(
            $board,
            $positionReader,
            $stopwatch,
            $output
        );

        $this->play()->shouldReturn(true);
    }
}
