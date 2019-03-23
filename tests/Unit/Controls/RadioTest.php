<?php declare(strict_types=1);

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Radio;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class RadioTest extends TestCase
{
    public function testRadioWorks(): void
    {
        $radio = new Radio(function (/** @var Radio $control */ $control = null) {
            // The radio should be given as first param to the callback
            self::assertInstanceOf(Radio::class, $control);
            self::assertEquals(1, $control->getSelected());
        });

        self::assertEquals(-1, $radio->getSelected());

        $radio->append('Zero');
        $radio->append('One');
        $radio->setSelected(1);

        ActionSimulator::act($radio);

        self::assertEquals(3, self::getCount());
    }

    public function testRadioWithoutCallbackAndSetOnSelected(): void
    {
        $radio = new Radio();

        // This should just do nothing, successfully
        ActionSimulator::act($radio);

        $actual = 0;
        $radio->setOnSelected(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($radio);

        self::assertEquals(1, $actual);

        $radio->setOnSelected(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($radio);

        self::assertEquals(2, $actual);
    }

    public function testRadioQuirks(): void
    {
        $radio = new Radio();
        self::assertEquals(-1, $radio->getSelected());

        // Setting anything out-of-bounds selected breaks stuff
        // This will SIGABRT on Mac if not handled properly
        $radio->setSelected(5);
        self::assertEquals(-1, $radio->getSelected());

        // Zero is out-of-bounds for empty array
        // So this will break as well
        $radio->setSelected(0);
        self::assertEquals(-1, $radio->getSelected());

        // As do all negative values except for -1
        $radio->setSelected(-5);
        self::assertEquals(-1, $radio->getSelected());

        // But we can always set -1
        $radio->setSelected(-1);
        self::assertEquals(-1, $radio->getSelected());

        // We have options now, but selected stays -1
        $radio->append('We have');
        $radio->append('Text now');
        self::assertEquals(-1, $radio->getSelected());

        // Of course we can always set the index of what we added
        $radio->setSelected(1);
        self::assertEquals(1, $radio->getSelected());

        // Now back to negative -1 should still work
        $radio->setSelected(-1);
        self::assertEquals(-1, $radio->getSelected());
    }
}
