<?php

namespace DynamicComponents\Controls;

class Entry extends \UI\Controls\Entry
{
    /** @var callable|null */
    private $onChange;

    public function __construct(?int $type = null, ?callable $onChange = null)
    {
        parent::__construct($type);

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
