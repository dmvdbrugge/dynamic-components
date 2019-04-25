<?php declare(strict_types=1);

namespace Tests\Unit;

use DynamicComponents\Menu;
use DynamicComponents\MenuItem;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use UI\MenuItem as UiMenuItem;

class MenuTest extends TestCase
{
    public function testMenuWorks(): void
    {
        $fileMenu = new Menu('File');
        self::assertEquals('File', $fileMenu->getName());

        /** @var MenuItem $newMenuItem */
        $newMenuItem = $fileMenu->append('New');

        self::assertInstanceOf(MenuItem::class, $newMenuItem);
        self::assertTrue($fileMenu->hasMenuItem($newMenuItem));
        self::assertEquals('New', $fileMenu->getMenuItemName($newMenuItem));
        self::assertEquals('New', $newMenuItem->getName());
        self::assertSame($fileMenu, $newMenuItem->getParent());
    }

    public function testMenuWorksWithUiMenuItem(): void
    {
        $editMenu     = new Menu('Edit');
        $copyMenuItem = $editMenu->append('Copy', UiMenuItem::class);

        self::assertTrue($editMenu->hasMenuItem($copyMenuItem));
        self::assertEquals('Copy', $editMenu->getMenuItemName($copyMenuItem));
    }

    public function testMenuItemRequiresName(): void
    {
        $viewMenu = new Menu('View');
        $message  = "Provide a \$name to append to Menu 'View'.";

        $this->expectExceptionObject(new InvalidArgumentException($message));

        $viewMenu->append();
    }

    public function testGetMenuItemNameFromNonChild(): void
    {
        $windowMenu = new Menu('Window');
        $helpMenu   = new Menu('Help');

        $reportMenuItem = $helpMenu->append('Report bug');
        self::assertFalse($windowMenu->hasMenuItem($reportMenuItem));

        $message = "MenuItem is not a child of Menu 'Window'.";
        $this->expectExceptionObject(new InvalidArgumentException($message));

        $windowMenu->getMenuItemName($reportMenuItem);
    }

    public function testAddWorks(): void
    {
        $runMenu     = new Menu('Run');
        $runMenuItem = $runMenu->add('Run...');

        self::assertTrue($runMenu->hasMenuItem($runMenuItem));
        self::assertEquals('Run...', $runMenu->getMenuItemName($runMenuItem));
        self::assertEquals('Run...', $runMenuItem->getName());
        self::assertSame($runMenu, $runMenuItem->getParent());
    }

    public function testAddAsWorks(): void
    {
        $toolsMenu     = new Menu('Tools');
        $stubsMenuItem = $toolsMenu->addAs(MenuItemStub::class, 'Stubs');

        self::assertInstanceOf(MenuItemStub::class, $stubsMenuItem);
        self::assertEquals('Stubs', $stubsMenuItem->getName());
    }
}

class MenuItemStub extends MenuItem
{
}
