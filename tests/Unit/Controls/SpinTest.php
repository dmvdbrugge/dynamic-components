<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Spin;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class SpinTest extends TestCase
{
    public function testSpinWorks(): void
    {
        $spin = new Spin(0, 10, function (/** @var Spin $control */ $control = null) {
            // The spin should be given as first param to the callback
            self::assertInstanceOf(Spin::class, $control);

            // The value should've been passed through
            self::assertEquals(5, $control->getValue());
        }, 5);

        ActionSimulator::act($spin);

        self::assertEquals(2, self::getCount());
    }

    public function testSpinWithoutCallbackAndSetOnChange(): void
    {
        $spin = new Spin(0, 10);

        // This should just do nothing, successfully
        ActionSimulator::act($spin);

        $actual = 0;
        $spin->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($spin);

        self::assertEquals(1, $actual);

        $spin->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($spin);

        self::assertEquals(2, $actual);
    }

    public function testSpinQuirks(): void
    {
        // Default value is the lowest of min and max
        $spin = new Spin(5, 10);
        self::assertEquals(5, $spin->getValue());

        // Lower than min becomes min
        $spin->setValue(2);
        self::assertEquals(5, $spin->getValue());

        // Lower than max becomes max
        $spin->setValue(12);
        self::assertEquals(10, $spin->getValue());

        // If you switch min and max, it will correct it
        // And everything then works the same
        $spin2 = new Spin(10, 5);
        self::assertEquals(5, $spin2->getValue());

        $spin2->setValue(2);
        self::assertEquals(5, $spin2->getValue());

        $spin2->setValue(12);
        self::assertEquals(10, $spin2->getValue());

        // Negative values, all the same
        $spin3 = new Spin(-10, -5);
        self::assertEquals(-10, $spin3->getValue());

        $spin3->setValue(2);
        self::assertEquals(-5, $spin3->getValue());

        $spin3->setValue(-12);
        self::assertEquals(-10, $spin3->getValue());

        // Negative values but switched, same behaviour
        $spin4 = new Spin(-5, -10);
        self::assertEquals(-10, $spin4->getValue());

        $spin4->setValue(2);
        self::assertEquals(-5, $spin4->getValue());

        $spin4->setValue(-12);
        self::assertEquals(-10, $spin4->getValue());
    }
}
