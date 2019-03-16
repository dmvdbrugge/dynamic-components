<?php

namespace Tests\Unit;

use DynamicComponents\Executor;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class ExecutorTest extends TestCase
{
    private const SIXTY_FPS = 16666; // int(1000000 / 60)

    public function testExecutorWorks(): void
    {
        $executor = new Executor(0, self::SIXTY_FPS, function (/** @var Executor $control */ $control = null) {
            // The Executor should be given as first param to the callback
            self::assertInstanceOf(Executor::class, $control);

            // And the interval should be set
            self::assertEquals([0, self::SIXTY_FPS], $control->getInterval());
        });

        ActionSimulator::executeExecutor($executor);

        self::assertEquals(2, self::getCount());
    }

    public function testExecutorWithoutCallbackAndSetOnExecute(): void
    {
        $executor = new Executor();

        // This should just do nothing, successfully
        ActionSimulator::executeExecutor($executor);

        $actual = 0;
        $executor->setOnExecute(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::executeExecutor($executor);

        self::assertEquals(1, $actual);

        $executor->setOnExecute(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::executeExecutor($executor);

        self::assertEquals(2, $actual);
    }

    public function testSecondsAsMicrosecondsWhenNoOrSingleParam(): void
    {
        $executor = new Executor();
        self::assertEquals([0, 0], $executor->getInterval());

        $executor->setInterval(self::SIXTY_FPS);
        self::assertEquals([0, self::SIXTY_FPS], $executor->getInterval());

        $executor = new Executor(self::SIXTY_FPS);
        self::assertEquals([0, self::SIXTY_FPS], $executor->getInterval());

        $executor->setInterval(5, 0);
        self::assertEquals([5, 0], $executor->getInterval());
    }
}
