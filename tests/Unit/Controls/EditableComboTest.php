<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\EditableCombo;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class EditableComboTest extends TestCase
{
    public function testEditableComboWorks(): void
    {
        $editableCombo = new EditableCombo(function (/** @var EditableCombo $control */ $control = null) {
            // The editableCombo should be given as first param to the callback
            self::assertInstanceOf(EditableCombo::class, $control);

            // The text should've been passed through
            self::assertEquals('Change Me!', $control->getText());
        }, 'Change Me!');

        ActionSimulator::act($editableCombo);

        self::assertEquals(2, self::getCount());
    }

    public function testEditableComboWithoutCallbackAndSetOnChange(): void
    {
        $editableCombo = new EditableCombo();

        // This should just do nothing, successfully
        ActionSimulator::act($editableCombo);

        $actual = 0;
        $editableCombo->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($editableCombo);

        self::assertEquals(1, $actual);

        $editableCombo->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($editableCombo);

        self::assertEquals(2, $actual);
    }
}
