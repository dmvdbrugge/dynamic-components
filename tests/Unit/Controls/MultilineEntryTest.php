<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\MultilineEntry;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class MultilineEntryTest extends TestCase
{
    public function testMultilineEntryWorks(): void
    {
        $multilineEntry = new MultilineEntry(
            MultilineEntry::Wrap,
            function (/** @var MultilineEntry $control */ $control = null) {
                // The multilineEntry should be given as first param to the callback
                self::assertInstanceOf(MultilineEntry::class, $control);

                // The text should've been passed through
                self::assertEquals('Read Me!', $control->getText());

                // As should the readOnly flag
                self::assertTrue($control->isReadOnly());
            },
            'Read Me!',
            true
        );

        ActionSimulator::act($multilineEntry);

        self::assertEquals(3, self::getCount());
    }

    public function testMultilineEntryWithoutCallbackAndSetOnChange(): void
    {
        $entry = new MultilineEntry();

        // This should just do nothing, successfully
        ActionSimulator::act($entry);

        $actual = 0;
        $entry->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($entry);

        self::assertEquals(1, $actual);

        $entry->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($entry);

        self::assertEquals(2, $actual);
    }
}
