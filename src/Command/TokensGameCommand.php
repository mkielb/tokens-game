<?php

namespace App\Command;

use App\TokensGame\Game;
use App\TokensGame\PositionReader;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\TokensGame\Board;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class TokensGameCommand extends Command
{
    protected static $defaultName = 'app:tokens-game';

    protected function configure()
    {
        $this->setDescription('Runs tokens game.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $positionReader = new PositionReader();
        $game = new Game(
            new Board($positionReader),
            $positionReader,
            new Stopwatch(),
            new ConsoleOutput()
        );

        $game->play();
        $game->complete();

        return Command::SUCCESS;
    }
}
