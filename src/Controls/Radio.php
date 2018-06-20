<?php

namespace DynamicComponents\Controls;

use function max;
use function min;

class Radio extends \UI\Controls\Radio
{
    /** @var int */
    private $maxIndex = -1;

    /** @var callable|null */
    private $onSelected;

    public function __construct(?callable $onSelected = null)
    {
        $this->onSelected = $onSelected;
    }

    public function append(string $text): void
    {
        parent::append($text);

        $this->maxIndex++;
    }

    public function setOnSelected(callable $onSelected): void
    {
        $this->onSelected = $onSelected;
    }

    public function setSelected(int $index): void
    {
        // -1 <= $index <= $this->maxIndex
        $index = max(-1, min($index, $this->maxIndex));

        parent::setSelected($index);
    }

    protected function onSelected(): void
    {
        if ($onSelected = $this->onSelected) {
            $onSelected($this);
        }
    }
}
