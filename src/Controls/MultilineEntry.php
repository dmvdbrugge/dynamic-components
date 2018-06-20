<?php

namespace DynamicComponents\Controls;

use UI\Exception\InvalidArgumentException;

class MultilineEntry extends \UI\Controls\MultilineEntry
{
    public const TYPES = [
        MultilineEntry::Wrap,
        MultilineEntry::NoWrap,
    ];

    /** @var callable|null */
    private $onChange;

    public function __construct(
        int $type = MultilineEntry::Wrap,
        ?callable $onChange = null,
        string $text = '',
        bool $readOnly = false
    ) {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException("Type {$type} is not a valid MultilineEntry type.");
        }

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
