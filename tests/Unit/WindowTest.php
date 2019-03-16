<?php

namespace Tests\Unit;

use DynamicComponents\Window;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Controls\Box;
use UI\Size;

class WindowTest extends TestCase
{
    public function testWindowWorks(): void
    {
        $size   = new Size(640, 480);
        $window = new Window('Close me!', $size, true, function (/** @var Window $control */ $control = null) use ($size) {
            // The Window should be given as first param to the callback
            self::assertInstanceOf(Window::class, $control);

            // The title should've been passed through
            self::assertEquals('Close me!', $control->getTitle());

            // As should the size, not the same object though :(
            self::assertEquals($size, $control->getSize());

            // And the menu flag
            self::assertTrue($control->hasMenu());
        });

        ActionSimulator::act($window);

        self::assertEquals(4, self::getCount());
    }

    public function testWindowWithoutCallbackAndSetOnClosing(): void
    {
        $window = new Window('Please work...', new Size(640, 480));

        $actual = 0;
        $window->setOnClosing(function () use (&$actual) {
            $actual = 1;

            // This also tests the return false mechanic, because otherwise this test crashes.
            return false;
        });

        ActionSimulator::act($window);

        self::assertEquals(1, $actual);

        $window->setOnClosing(function () use (&$actual) {
            $actual = 2;
        });

        ActionSimulator::act($window);

        self::assertEquals(2, $actual);

        // Because we didn't return false the second time, this line would crash:
        // ActionSimulator::act($window);
    }

    public function testCannotAddWindowToWindow(): void
    {
        $parent = new Window('Parent', new Size(640, 480));
        $child  = new Window('Child', new Size(320, 240));
        $box    = new Box(Box::Horizontal);

        // This should still work
        $parent->add($box);

        $message = 'Cannot add a Window (DynamicComponents\Window: Child) to a Window (DynamicComponents\Window: Parent)!';
        $this->expectExceptionObject(new InvalidArgumentException($message));

        $parent->add($child);
    }
}
