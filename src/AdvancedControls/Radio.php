<?php

namespace DynamicComponents\AdvancedControls;

use UI\Exception\InvalidArgumentException;

use function count;
use function is_int;
use function is_string;

class Radio extends \DynamicComponents\Controls\Radio
{
    /** @var string[] */
    private $options = [];

    /** @var array String indexed int[], as if array_flip($this->options) */
    private $flipped = [];

    /**
     * @param string[]        $options
     * @param callable|null   $onSelected
     * @param int|string|null $selected   Text or index of option to be selected (null for default)
     */
    public function __construct(array $options, ?callable $onSelected = null, $selected = 0)
    {
        parent::__construct($onSelected);

        foreach ($options as $value) {
            $this->append($value);
        }

        if (is_int($selected)) {
            $this->setSelected($selected);
        } elseif (is_string($selected)) {
            $this->setSelectedText($selected);
        }
    }

    public function append(string $text): void
    {
        parent::append($text);

        $this->flipped[$text] = count($this->options);
        $this->options[]      = $text;
    }

    public function getSelectedText(): string
    {
        return $this->options[$this->getSelected()];
    }

    public function setSelectedText(string $text): void
    {
        if (!isset($this->flipped[$text])) {
            throw new InvalidArgumentException("Cannot select {$text}: it's not in options!");
        }

        $this->setSelected($this->flipped[$text]);
    }
}
