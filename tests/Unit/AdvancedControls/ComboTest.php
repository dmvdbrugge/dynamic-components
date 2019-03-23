<?php declare(strict_types=1);

namespace Tests\Unit\AdvancedControls;

use DynamicComponents\AdvancedControls\Combo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Helpers\ActionSimulator;

use function array_merge;

class ComboTest extends TestCase
{
    public function testComboWorks(): void
    {
        $options = ['One', 'Three', 'Three', 'Seven'];

        $combo = new Combo($options, function (/** @var Combo $control */ $control = null) {
            // The combo should be given as first param to the callback
            self::assertInstanceOf(Combo::class, $control);

            // The first 'Three' should have been selected
            self::assertEquals(1, $control->getSelected());
            self::assertEquals('Three', $control->getSelectedText());
        }, 'Three');

        ActionSimulator::act($combo);
        self::assertEquals(3, self::getCount());

        $appended = ['Four', 'Two'];
        $combo->appendAll($appended);
        self::assertEquals(array_merge($options, $appended), $combo->getOptions());

        $combo->setSelectedText('Four');
        self::assertEquals(4, $combo->getSelected());
        self::assertEquals('Four', $combo->getSelectedText());

        $combo->setSelectedText('Unexisting');
        self::assertEquals(-1, $combo->getSelected());
        self::assertEquals('', $combo->getSelectedText());
    }

    public function testEmptyCombo(): void
    {
        $combo = new Combo([]);
        self::assertEquals(0, $combo->getSelected());
        self::assertEquals('', $combo->getSelectedText());

        // Adding stuff doesn't mean it's selected...
        $combo->append('Not empty anymore!');
        self::assertEquals(-1, $combo->getSelected());
        self::assertEquals('', $combo->getSelectedText());
    }

    /**
     * @dataProvider dpComboSelectedValue
     */
    public function testComboSelectedValue($value, ?string $message): void
    {
        if ($message !== null) {
            $this->expectExceptionObject(new InvalidArgumentException($message));
        }

        new Combo(['This', 'is-a', 'test'], null, $value);

        if ($message === null) {
            $this->addToAssertionCount(1);
        }
    }

    public function dpComboSelectedValue(): array
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
