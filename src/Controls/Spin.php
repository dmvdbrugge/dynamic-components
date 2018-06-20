<?php

namespace DynamicComponents\Controls;

use function max;
use function min;

class Spin extends \UI\Controls\Spin
{
    /** @var int */
    private $max;

    /** @var int */
    private $min;

    /** @var callable|null */
    private $onChange;

    public function __construct(int $min, int $max, ?callable $onChange = null, ?int $value = null)
    {
        parent::__construct($min, $max);

        // Yes \UI\Controls\Spin excepts them the other way around...
        $this->max = max($min, $max);
        $this->min = min($min, $max);

        $this->onChange = $onChange;

        if ($value !== null) {
            $this->setValue($value);
        }
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function getMin(): int
    {
        return $this->min;
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
