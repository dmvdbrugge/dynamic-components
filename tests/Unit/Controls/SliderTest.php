<?php declare(strict_types=1);

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Slider;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class SliderTest extends TestCase
{
    public function testSliderWorks(): void
    {
        $slider = new Slider(0, 10, function (/** @var Slider $control */ $control = null) {
            // The slider should be given as first param to the callback
            self::assertInstanceOf(Slider::class, $control);

            // The value should've been passed through
            self::assertEquals(5, $control->getValue());
        }, 5);

        ActionSimulator::act($slider);

        self::assertEquals(2, self::getCount());
    }

    public function testMinMaxWorkBothWays(): void
    {
        $slider = new Slider(0, 100);

        self::assertEquals(0, $slider->getMin());
        self::assertEquals(100, $slider->getMax());

        $flipped = new Slider(100, 0);

        self::assertEquals(0, $flipped->getMin());
        self::assertEquals(100, $flipped->getMax());
    }

    public function testSliderWithoutCallbackAndSetOnChange(): void
    {
        $slider = new Slider(0, 10);

        // This should just do nothing, successfully
        ActionSimulator::act($slider);

        $actual = 0;
        $slider->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($slider);

        self::assertEquals(1, $actual);

        $slider->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($slider);

        self::assertEquals(2, $actual);
    }

    public function testSliderQuirks(): void
    {
        // Default value is whichever in range is closest to 0
        $slider = new Slider(5, 10);
        self::assertEquals(5, $slider->getValue());
        self::assertEquals(5, $slider->getMin());
        self::assertEquals(10, $slider->getMax());

        // Lower than min becomes min
        $slider->setValue(2);
        self::assertEquals(5, $slider->getValue());

        // Lower than max becomes max
        $slider->setValue(12);
        self::assertEquals(10, $slider->getValue());

        // If you switch min and max, it will correct it
        // And everything then works the same
        $slider2 = new Slider(10, 5);
        self::assertEquals(5, $slider2->getValue());
        self::assertEquals(5, $slider2->getMin());
        self::assertEquals(10, $slider2->getMax());

        $slider2->setValue(2);
        self::assertEquals(5, $slider2->getValue());

        $slider2->setValue(12);
        self::assertEquals(10, $slider2->getValue());

        // Negative values, all the same
        $slider3 = new Slider(-10, -5);
        self::assertEquals(-5, $slider3->getValue());
        self::assertEquals(-10, $slider3->getMin());
        self::assertEquals(-5, $slider3->getMax());

        $slider3->setValue(2);
        self::assertEquals(-5, $slider3->getValue());

        $slider3->setValue(-12);
        self::assertEquals(-10, $slider3->getValue());

        // Negative values but switched, same behaviour
        $slider4 = new Slider(-5, -10);
        self::assertEquals(-5, $slider4->getValue());
        self::assertEquals(-10, $slider4->getMin());
        self::assertEquals(-5, $slider4->getMax());

        $slider4->setValue(2);
        self::assertEquals(-5, $slider4->getValue());

        $slider4->setValue(-12);
        self::assertEquals(-10, $slider4->getValue());
    }
}
