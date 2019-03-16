<?php

namespace Tests\HelperTests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Area;
use UI\Controls\Box;
use UI\Controls\Button;

class ActionSimulatorTest extends TestCase
{
    public function testActWorks(): void
    {
        $button = new class('Click me!', $this) extends Button {
            /** @var TestCase */
            private $test;

            public function __construct(string $text, TestCase $test)
            {
                parent::__construct($text);

                $this->test = $test;
            }

            protected function onClick(): void
            {
                $this->test->addToAssertionCount(1);
            }
        };

        ActionSimulator::act($button);

        self::assertEquals(1, $this->getNumAssertions());

        ActionSimulator::act($button);
        ActionSimulator::act($button);

        self::assertEquals(3, $this->getNumAssertions());
    }

    public function testCannotActOnArea(): void
    {
        $area = new Area();

        $message = 'Area has multiple possible actions, use the specific methods.';
        $this->expectExceptionObject(new InvalidArgumentException($message));

        ActionSimulator::act($area);
    }

    public function testCannotActOnUnActionableControl(): void
    {
        $box = new Box(Box::Horizontal);

        $message = 'UI\Controls\Box is not an actionable UI\Control.';
        $this->expectExceptionObject(new InvalidArgumentException($message));

        ActionSimulator::act($box);
    }
}
