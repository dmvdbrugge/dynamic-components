<?php

namespace DynamicComponents;

use BadMethodCallException;
use Webmozart\Assert\Assert;

use function get_class;

class MenuItem extends \UI\MenuItem
{
    private $enabled = true;

    /** @var string|null */
    private $name;

    /** @var callable|null */
    private $onClick;

    /** @var Menu|null */
    private $parent;

    public function disable(): void
    {
        $this->enabled = false;

        parent::disable();
    }

    public function enable(): void
    {
        $this->enabled = true;

        parent::enable();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getParent(): Menu
    {
        if (!$this->parent) {
            $class = static::class;

            throw new BadMethodCallException(
                "MenuItem ({$class}: {$this->name}) has no parent," .
                " it's probably created by a \\UI\\Menu instead of a \\DynamicComponents\\Menu."
            );
        }

        return $this->parent;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @internal
     */
    public function setName(string $name): void
    {
        $class = static::class;
        Assert::notEmpty($name, "Cannot set an empty name on a {$class}.");

        if ($this->name) {
            throw new BadMethodCallException(
                "Cannot set a name ({$name}) on an already named MenuItem ({$class}: {$this->name})."
            );
        }

        $this->name = $name;
    }

    public function setOnClick(callable $onClick): void
    {
        $this->onClick = $onClick;
    }

    /**
     * @internal
     */
    public function setParent(Menu $menu): void
    {
        if (!$menu->hasMenuItem($this)) {
            $parent = get_class($menu);
            $child  = static::class;

            throw new BadMethodCallException(
                "Trying to set Menu ({$parent}: {$menu->getName()}) as parent of MenuItem ({$child}: {$this->name})." .
                ' Can only set actual parent as parent.'
            );
        }

        $this->parent = $menu;
    }

    protected function onClick(): void
    {
        if ($onClick = $this->onClick) {
            $onClick($this);
        }
    }
}
