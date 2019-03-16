<?php

namespace DynamicComponents;

use InvalidArgumentException;
use SplObjectStorage;
use UI\Menu as UiMenu;
use UI\MenuItem as UiMenuItem;
use Webmozart\Assert\Assert;

class Menu extends UiMenu
{
    /** @var string */
    private $name;

    /** @var SplObjectStorage */
    private $items;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->name  = $name;
        $this->items = new SplObjectStorage();
    }

    public function append(string $name = '', string $type = MenuItem::class): UiMenuItem
    {
        Assert::notEmpty($name, "Provide a \$name to append to Menu '{$this->name}'.");

        $menuItem = parent::append($name, $type);

        $this->items->attach($menuItem, $name);

        if ($menuItem instanceof MenuItem) {
            $menuItem->setName($name);
            $menuItem->setParent($this);
        }

        return $menuItem;
    }

    public function hasMenuItem(UiMenuItem $menuItem): bool
    {
        return $this->items->contains($menuItem);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMenuItemName(UiMenuItem $menuItem): string
    {
        if (!$this->hasMenuItem($menuItem)) {
            throw new InvalidArgumentException("MenuItem is not a child of Menu '{$this->name}'.");
        }

        return $this->items->offsetGet($menuItem);
    }
}
