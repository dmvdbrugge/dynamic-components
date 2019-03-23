<?php declare(strict_types=1);

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\ColorButton;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Draw\Color;

class ColorButtonTest extends TestCase
{
    public function testColorButtonWorks(): void
    {
        $colorButton = new ColorButton(function (/** @var ColorButton $control */ $control = null) {
            // The colorButton should be given as first param to the callback
            self::assertInstanceOf(ColorButton::class, $control);

            // The color should've been passed through
            self::assertEquals(1, $control->getColor()->getChannel(Color::Red));
        }, new Color(0xFF000000));

        ActionSimulator::act($colorButton);

        self::assertEquals(2, self::getCount());
    }

    public function testColorButtonWithoutCallbackAndSetOnChange(): void
    {
        $colorButton = new ColorButton();

        // This should just do nothing, successfully
        ActionSimulator::act($colorButton);

        $actual = 0;
        $colorButton->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($colorButton);

        self::assertEquals(1, $actual);

        $colorButton->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($colorButton);

        self::assertEquals(2, $actual);
    }
}
