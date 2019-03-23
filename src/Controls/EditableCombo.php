<?php declare(strict_types=1);

namespace DynamicComponents\Controls;

class EditableCombo extends \UI\Controls\EditableCombo
{
    /** @var callable|null */
    private $onChange;

    public function __construct(?callable $onChange = null, string $text = '')
    {
        $this->onChange = $onChange;

        $this->setText($text);
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
