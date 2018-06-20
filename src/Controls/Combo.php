<?php

namespace DynamicComponents\Controls;

use function max;
use function min;

class Combo extends \UI\Controls\Combo
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
        // 0 <= $index <= $this->maxIndex (unless $this->maxIndex == -1, then $index = 0)
        $index = max(0, min($index, $this->maxIndex));

        parent::setSelected($index);
    }

    protected function onSelected(): void
    {
        if ($onSelected = $this->onSelected) {
            $onSelected($this);
        }
    }
}
