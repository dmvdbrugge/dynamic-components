<?php

namespace DynamicComponents\Controls;

class Radio extends \UI\Controls\Radio
{
    /** @var callable|null */
    private $onSelected;

    public function __construct(?callable $onSelected = null)
    {
        $this->onSelected = $onSelected;
    }

    public function setOnSelected(callable $onSelected): void
    {
        $this->onSelected = $onSelected;
    }

    protected function onSelected(): void
    {
        if ($onSelected = $this->onSelected) {
            $onSelected($this);
        }
    }
}
