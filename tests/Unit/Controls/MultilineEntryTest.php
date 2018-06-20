<?php

namespace Tests\Unit\Controls;

use DynamicComponents\Controls\MultilineEntry;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Exception\InvalidArgumentException;

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
        $multilineEntry = new MultilineEntry();

        // This should just do nothing, successfully
        ActionSimulator::act($multilineEntry);

        $actual = 0;
        $multilineEntry->setOnChange(function () use (&$actual) {
            $actual = 1;
        });

        ActionSimulator::act($multilineEntry);

        self::assertEquals(1, $actual);

        $multilineEntry->setOnChange(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($multilineEntry);

        self::assertEquals(2, $actual);
    }

    public function testTypesAreValid()
    {
        foreach (MultilineEntry::TYPES as $type) {
            $multilineEntry = new MultilineEntry($type);
            self::assertEquals($type, $multilineEntry->getType());
        }
    }

    public function testInvalidTypeThrows()
    {
        $invalidType = -5;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Type {$invalidType} is not a valid MultilineEntry type.");

        new MultilineEntry($invalidType);
    }
}
