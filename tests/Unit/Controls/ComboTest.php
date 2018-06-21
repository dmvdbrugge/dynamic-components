<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Combo;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class ComboTest extends TestCase
{
    public function testComboWorks(): void
    {
        $combo = new Combo(function (/** @var Combo $control */ $control = null) {
            // The combo should be given as first param to the callback
            self::assertInstanceOf(Combo::class, $control);
            self::assertEquals(1, $control->getSelected());
        });

        self::assertEquals(0, $combo->getSelected());

        $combo->append('Zero');
        $combo->append('One');
        $combo->setSelected(1);

        ActionSimulator::act($combo);

        self::assertEquals(3, self::getCount());
    }

    public function testComboWithoutCallbackAndSetOnSelected(): void
    {
        $combo = new Combo();

        // This should just do nothing, successfully
        ActionSimulator::act($combo);

        $actual = 0;
        $combo->setOnSelected(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($combo);

        self::assertEquals(1, $actual);

        $combo->setOnSelected(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($combo);

        self::assertEquals(2, $actual);
    }

    public function testComboQuirks(): void
    {
        // Default selected is 0, even though it doesn't exist
        $combo = new Combo();
        self::assertEquals(0, $combo->getSelected());

        // Setting a positive out-of-bounds selected breaks stuff
        // This will SIGABRT on Mac if not handled properly
        $combo->setSelected(5);
        self::assertEquals(-1, $combo->getSelected());

        // Except for setting to 0, which works even though it still doesn't exist
        $combo->setSelected(0);
        self::assertEquals(0, $combo->getSelected());

        // Setting a negative out-of-bounds selected breaks stuff
        // This will SIGABRT on Mac if not handled properly
        $combo->setSelected(-5);
        self::assertEquals(-1, $combo->getSelected());

        // We have options now, but selected stays -1
        $combo->append('We have');
        $combo->append('Text now');
        self::assertEquals(-1, $combo->getSelected());

        // Now we select something
        $combo->setSelected(1);
        self::assertEquals(1, $combo->getSelected());

        // And back to nothing
        $combo->setSelected(-1);
        self::assertEquals(-1, $combo->getSelected());
    }
}
