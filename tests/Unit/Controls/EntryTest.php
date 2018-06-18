<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\Entry;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;

class EntryTest extends TestCase
{
    public function testEntryWorks(): void
    {
        $entry = new Entry(Entry::Normal, function (/** @var Entry $control */ $control = null) {
            // The entry should be given as first param to the callback
            self::assertInstanceOf(Entry::class, $control);

            // The text should've been passed through
            self::assertEquals('Read Me!', $control->getText());

            // As should the readOnly flag
            self::assertTrue($control->isReadOnly());
        }, 'Read Me!', true);

        ActionSimulator::act($entry);

        self::assertEquals(3, self::getCount());
    }

    public function testEntryWithoutCallbackAndSetOnChange(): void
    {
        $entry = new Entry();

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
