<?php declare(strict_types=1);

namespace Tests\Unit;

use BadMethodCallException;
use DynamicComponents\Menu;
use DynamicComponents\MenuItem;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Menu as UiMenu;

class MenuItemTest extends TestCase
{
    public function testMenuItemWorks(): void
    {
        $menu = new Menu('File');

        /** @var MenuItem $item */
        $item = $menu->append('New');
        $item->setOnClick(function (/** @var MenuItem $control */ $control = null) {
            // The MenuItem should be given as first param to the callback
            self::assertInstanceOf(MenuItem::class, $control);

            // It should be enabled
            self::assertTrue($control->isEnabled());
        });

        ActionSimulator::clickMenuItem($item);

        self::assertEquals(2, self::getCount());

        // Set by Menu::append
        self::assertEquals('New', $item->getName());
        self::assertSame($menu, $item->getParent());

        $item->disable();
        self::assertFalse($item->isEnabled());

        $item->enable();
        self::assertTrue($item->isEnabled());
    }

    public function testMenuItemWorksWithUiMenu(): void
    {
        $uiMenu = new UiMenu('Edit');

        /** @var MenuItem $item */
        $item = $uiMenu->append('Copy', MenuItem::class);
        $item->setOnClick(function (/** @var MenuItem $control */ $control = null) {
            // The MenuItem should be given as first param to the callback
            self::assertInstanceOf(MenuItem::class, $control);

            // It should be enabled
            self::assertTrue($control->isEnabled());

            // And name not set
            self::assertNull($control->getName());
        });

        ActionSimulator::clickMenuItem($item);

        self::assertEquals(3, self::getCount());

        // While marked as internal, it's not prohibited. Just no guarantee that it actually has that label.
        $item->setName('Copy');
        self::assertEquals('Copy', $item->getName());

        $message = 'MenuItem (DynamicComponents\\MenuItem: Copy) has no parent,' .
            " it's probably created by a \\UI\\Menu instead of a \\DynamicComponents\\Menu.";
        $this->expectExceptionObject(new BadMethodCallException($message));

        $item->getParent();
    }

    public function testCannotSetDifferentMenuAsParent(): void
    {
        $viewMenu = new Menu('View');
        $helpMenu = new Menu('Help');

        /** @var MenuItem $item */
        $item = $helpMenu->append('Report bug');

        self::assertTrue($helpMenu->hasMenuItem($item));
        self::assertFalse($viewMenu->hasMenuItem($item));
        self::assertSame($helpMenu, $item->getParent());

        $message = 'Trying to set Menu (DynamicComponents\Menu: View) as parent of MenuItem' .
            ' (DynamicComponents\MenuItem: Report bug). Can only set actual parent as parent.';
        $this->expectExceptionObject(new BadMethodCallException($message));

        $item->setParent($viewMenu);
    }

    public function testSetEmptyNameFails(): void
    {
        $runMenu = new UiMenu('Run');

        /** @var MenuItem $runItem */
        $runItem = $runMenu->append('Run...', MenuItem::class);

        $message = 'Cannot set an empty name on a DynamicComponents\MenuItem.';
        $this->expectExceptionObject(new InvalidArgumentException($message));

        $runItem->setName('');
    }

    public function testSetNameAgainFails(): void
    {
        $windowMenu = new Menu('Window');

        /** @var MenuItem $item */
        $item = $windowMenu->append('dynamic-components');

        $message = 'Cannot set a name (csv2qif) on an already named MenuItem' .
            ' (DynamicComponents\MenuItem: dynamic-components).';
        $this->expectExceptionObject(new BadMethodCallException($message));

        $item->setName('csv2qif');
    }
}
