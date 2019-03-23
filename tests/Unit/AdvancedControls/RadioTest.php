<?php declare(strict_types=1);

namespace Tests\Unit\AdvancedControls;

use DynamicComponents\AdvancedControls\Radio;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
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

    /**
     * @dataProvider dpRadioSelectedValue
     */
    public function testRadioSelectedValue($value, ?string $message): void
    {
        if ($message !== null) {
            $this->expectExceptionObject(new InvalidArgumentException($message));
        }

        new Radio(['This', 'is-a', 'test'], null, $value);

        if ($message === null) {
            $this->addToAssertionCount(1);
        }
    }

    public function dpRadioSelectedValue(): array
    {
        return [
            'int'    => [1, null],
            'float'  => [1.0, null],
            'string' => ['is-a', null],
            'bool'   => [true, 'Selected should be either a string or an integer, got boolean.'],
            'object' => [new stdClass(), 'Selected should be either a string or an integer, got stdClass.'],
            'array'  => [[], 'Selected should be either a string or an integer, got array.'],
        ];
    }
}
