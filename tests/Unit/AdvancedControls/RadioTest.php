<?php

namespace Tests\Unit\AdvancedControls;

use DynamicComponents\AdvancedControls\Radio;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

use function array_merge;

class RadioTest extends TestCase
{
    public function testRadioWorks(): void
    {
        $options = ['One', 'Three', 'Three', 'Seven'];

        $radio = new Radio($options, function (/** @var Radio $control */ $control = null) {
            // The radio should be given as first param to the callback
            self::assertInstanceOf(Radio::class, $control);

            // The first 'Three' should have been selected
            self::assertEquals(1, $control->getSelected());
            self::assertEquals('Three', $control->getSelectedText());
        }, 'Three');

        ActionSimulator::act($radio);
        self::assertEquals(3, self::getCount());

        $appended = ['Four', 'Two'];
        $radio->appendAll($appended);
        self::assertEquals(array_merge($options, $appended), $radio->getOptions());

        $radio->setSelectedText('Four');
        self::assertEquals(4, $radio->getSelected());
        self::assertEquals('Four', $radio->getSelectedText());

        $radio->setSelectedText('Unexisting');
        self::assertEquals(-1, $radio->getSelected());
        self::assertEquals('', $radio->getSelectedText());
    }

    public function testEmptyRadio(): void
    {
        $radio = new Radio([]);
        self::assertEquals(-1, $radio->getSelected());
        self::assertEquals('', $radio->getSelectedText());

        // Adding stuff doesn't mean it's selected...
        $radio->append('Not empty anymore!');
        self::assertEquals(-1, $radio->getSelected());
        self::assertEquals('', $radio->getSelectedText());
    }
}
