<?php declare(strict_types=1);

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Check;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class CheckTest extends TestCase
{
    public function testCheckWorks(): void
    {
        $check = new Check('Toggle Me!', function (/** @var Check $control */ $control = null) {
            // The check should be given as first param to the callback
            self::assertInstanceOf(Check::class, $control);

            // The text should've been passed through
            self::assertEquals('Toggle Me!', $control->getText());

            // Should be checked
            self::assertTrue($control->isChecked());
        }, true);

        ActionSimulator::act($check);

        self::assertEquals(3, self::getCount());
    }

    public function testCheckWithoutCallbackAndSetOnToggle(): void
    {
        $check = new Check('Please work...');

        // This should just do nothing, successfully
        ActionSimulator::act($check);

        $actual = 0;
        $check->setOnToggle(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($check);

        self::assertEquals(1, $actual);

        $check->setOnToggle(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($check);

        self::assertEquals(2, $actual);
    }
}
