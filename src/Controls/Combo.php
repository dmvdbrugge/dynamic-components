<?php

namespace DynamicComponents\Controls;

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
        if ($index < -1 || ($index > $this->maxIndex && $index !== 0)) {
            $index = -1;
        }

        parent::setSelected($index);
    }

    protected function onSelected(): void
    {
        if ($onSelected = $this->onSelected) {
            $onSelected($this);
        }
    }
}
