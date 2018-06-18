<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Button;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class ButtonTest extends TestCase
{
    public function testButtonWorks(): void
    {
        $button = new Button('Click Me!', function (/** @var Button $btn */ $btn = null) {
            // The button should be given as first param to the callback
            self::assertInstanceOf(Button::class, $btn);

            // The text should've been passed through
            self::assertEquals('Click Me!', $btn->getText());
        });

        ActionSimulator::act($button);

        self::assertEquals(2, self::getCount());
    }

    public function testButtonWithoutCallbackAndSetOnClick(): void
    {
        $button = new Button('Please work...');

        // This should just do nothing, successfully
        ActionSimulator::act($button);

        $actual = 0;
        $button->setOnClick(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($button);

        self::assertEquals(1, $actual);

        $button->setOnClick(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($button);

        self::assertEquals(2, $actual);
    }
}
