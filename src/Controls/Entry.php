<?php

namespace DynamicComponents\Controls;

class Entry extends \UI\Controls\Entry
{
    /** @var callable|null */
    private $onChange;

    public function __construct(
        int $type = Entry::Normal,
        ?callable $onChange = null,
        string $text = '',
        bool $readOnly = false
    ) {
        parent::__construct($type);

        $this->onChange = $onChange;

        $this->setText($text);
        $this->setReadOnly($readOnly);
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
