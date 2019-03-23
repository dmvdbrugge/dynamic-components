<?php declare(strict_types=1);

namespace DynamicComponents\Controls;

use Webmozart\Assert\Assert;

class Entry extends \UI\Controls\Entry
{
    public const TYPES = [
        Entry::Normal,
        Entry::Password,
        Entry::Search,
    ];

    /** @var callable|null */
    private $onChange;

    /** @var int */
    private $type;

    public function __construct(
        int $type = Entry::Normal,
        ?callable $onChange = null,
        string $text = '',
        bool $readOnly = false
    ) {
        Assert::oneOf($type, self::TYPES, "Type {$type} is not a valid Entry type.");

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
