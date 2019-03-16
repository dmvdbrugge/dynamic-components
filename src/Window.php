<?php

namespace DynamicComponents;

use InvalidArgumentException;
use UI\Control;
use UI\Size;

use function get_class;
use function UI\quit;

class Window extends \UI\Window
{
    /** @var bool */
    private $menu;

    /** @var callable|null */
    private $onClosing;

    public function __construct(string $title, Size $size, bool $menu = false, ?callable $onClosing = null)
    {
        parent::__construct($title, $size, $menu);

        $this->menu      = $menu;
        $this->onClosing = $onClosing;
    }

    public function add(Control $control): void
    {
        // While ext-ui is ok with this, libui is not. If we don't prevent it, php crashes.
        if ($control instanceof \UI\Window) {
            $child  = get_class($control);
            $parent = static::class;

            throw new InvalidArgumentException(
                "Cannot add a Window ({$child}: {$control->getTitle()}) to a Window ({$parent}: {$this->getTitle()})!"
            );
        }

        parent::add($control);
    }

    public function hasMenu(): bool
    {
        return $this->menu;
    }

    public function setOnClosing(callable $onClosing): void
    {
        $this->onClosing = $onClosing;
    }

    protected function onClosing()
    {
        if ($this->onClosing && ($this->onClosing)($this) === false) {
            return;
        }

        // Default behaviour when not having implemented onClosing.
        // Explicitly return false in the callback to prevent.
        $this->destroy();
        quit();
    }
}
