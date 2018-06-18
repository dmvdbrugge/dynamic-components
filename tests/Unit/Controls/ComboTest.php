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
}
