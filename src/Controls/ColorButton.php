<?php

namespace DynamicComponents\Controls;

use UI\Draw\Color;

class ColorButton extends \UI\Controls\ColorButton
{
    /** @var callable|null */
    private $onChange;

    public function __construct(?callable $onChange = null, ?Color $color = null)
    {
        $this->onChange = $onChange;

        if ($color) {
            $this->setColor($color);
        }
    }

    public function setOnChange(callable $onChange): void
    {
        $this->onChange = $onChange;
    }

    protected function onChange(): void
    {
        if ($onChange = $this->onChange) {
            $onChange($this);
        }
    }
}
