<?php

namespace DynamicComponents\Controls;

use Webmozart\Assert\Assert;

class MultilineEntry extends \UI\Controls\MultilineEntry
{
    public const TYPES = [
        MultilineEntry::Wrap,
        MultilineEntry::NoWrap,
    ];

    /** @var callable|null */
    private $onChange;

    /** @var int */
    private $type;

    public function __construct(
        int $type = MultilineEntry::Wrap,
        ?callable $onChange = null,
        string $text = '',
        bool $readOnly = false
    ) {
        Assert::oneOf($type, self::TYPES, "Type {$type} is not a valid MultilineEntry type.");

        parent::__construct($type);

        $this->onChange = $onChange;
        $this->type     = $type;

        $this->setText($text);
        $this->setReadOnly($readOnly);
    }

    public function getType(): int
    {
        return $this->type;
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
