<?php

namespace DynamicComponents\Controls;

class Slider extends \UI\Controls\Slider
{
    /** @var callable|null */
    private $onChange;

    public function __construct(int $min, int $max, ?callable $onChange = null, ?int $value = null)
    {
        parent::__construct($min, $max);

        $this->onChange = $onChange;

        if ($value !== null) {
            $this->setValue($value);
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
