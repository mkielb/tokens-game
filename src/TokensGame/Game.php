<?php

namespace App\TokensGame;

use App\TokensGame\Exception\WrongPositionException;
use Throwable;
use App\TokensGame\Exception\TimeIsUpException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class Game
{
    private const EVENT_STOPWATCH_NAME = 'tokensGame';
    private const MAX_TIME_IN_SECONDS = 60;
    private const MAX_NUMBER_OF_ATTEMPTS = 5;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var PositionReader
     */
    private $positionReader;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var int
     */
    private $numberOfAttemptsUsed = 0;

    /**
     * @var bool
     */
    private $isWinner = false;

    public function __construct(
        Board $board,
        PositionReader $positionReader,
        Stopwatch $stopwatch,
        OutputInterface $output
    ) {
        $this->board = $board;
        $this->positionReader = $positionReader;
        $this->stopwatch = $stopwatch;
        $this->output = $output;
    }

    public function play(): bool
    {
        $this->stopwatch->start(self::EVENT_STOPWATCH_NAME);

        while (!($this->isGameOver() || $this->isWinner)) {
            try {
                $this->process();
                $this->output->writeln('');
            } catch (TimeIsUpException $e) {
                break;
            } catch (Throwable $e) {
                $this->output->writeln($e->getMessage());
                $this->output->writeln('');
            }
        }

        return $this->isWinner;
    }

    private function isGameOver(): bool
    {
        return $this->isTimeUp() || $this->areNumberOfAttemptsOver();
    }

    private function isTimeUp(): bool
    {
        return $this->stopwatch->getEvent(self::EVENT_STOPWATCH_NAME)->getDuration() >= self::MAX_TIME_IN_SECONDS * 1000;
    }

    private function areNumberOfAttemptsOver(): bool
    {
        return $this->numberOfAttemptsUsed >= self::MAX_NUMBER_OF_ATTEMPTS;
    }

    private function process(): void
    {
        $this->output->writeln('Enter token position (format: "x,y"):');
        $tokenPositionFromConsole = $this->positionReader->readFromConsole();

        if ($this->isTimeUp()) {
            throw new TimeIsUpException('Time is up');
        }

        $token = $this->board
            ->getField($tokenPositionFromConsole)
            ->getToken();
        $token->reveal();

        $this->numberOfAttemptsUsed++;
        $this->isWinner = $token->isWinner();

        if (!$this->isWinner) {
            throw new WrongPositionException('Wrong, try again');
        }
    }

    public function complete(): void
    {
        if ($this->isWinner) {
            $this->completeAWinningGame();
        } else {
            $this->completeALostGame();
        }
    }

    private function completeAWinningGame(): void
    {
        $this->stopwatch->stop(self::EVENT_STOPWATCH_NAME);
        $this->output->writeln('You won');
        $this->getStats();
    }

    private function completeALostGame(): void
    {
        $this->stopwatch->stop(self::EVENT_STOPWATCH_NAME);
        $this->output->writeln(
            $this->areNumberOfAttemptsOver()
                ? 'Number of attempts are over'
                : 'Time is up'
        );
        $this->output->writeln('You lost');
        $this->getStats();
    }

    private function getStats(): void
    {
        $this->output->writeln(sprintf(
            'Number of attempts used: %d',
            $this->numberOfAttemptsUsed
        ));
        $this->output->writeln(sprintf(
            'Time elapsed: %d seconds',
            $this->stopwatch->getEvent(self::EVENT_STOPWATCH_NAME)->getDuration() / 1000
        ));
    }
}
