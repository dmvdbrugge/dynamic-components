<?php

namespace DynamicComponents\Controls;

class EditableCombo extends \UI\Controls\EditableCombo
{
    /** @var callable|null */
    private $onChange;

    public function __construct(?callable $onChange = null)
    {
        $this->onChange = $onChange;
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
