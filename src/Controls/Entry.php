<?php

namespace DynamicComponents\Controls;

use UI\Exception\InvalidArgumentException;

use function in_array;

class Entry extends \UI\Controls\Entry
{
    public const TYPES = [
        Entry::Normal,
        Entry::Password,
        Entry::Search,
    ];

    /** @var callable|null */
    private $onChange;

    public function __construct(
        int $type = Entry::Normal,
        ?callable $onChange = null,
        string $text = '',
        bool $readOnly = false
    ) {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException("Type {$type} is not a valid Entry type.");
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
