<?php

namespace DynamicComponents\AdvancedControls;

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
     * @param string[]      $options    Keys are ignored!
     * @param callable|null $onSelected Gets $this as first param
     * @param int|string    $selected   Index or text of option to be selected
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
        return $this->options[$this->getSelected()] ?? '';
    }

    public function setSelectedText(string $text): void
    {
        /*
         * \UI\Controls\Radio::setSelected() allows any index to be set,
         * however non-existing indices (except 0) will become -1
         */
        $this->setSelected($this->flipped[$text] ?? -1);
    }
}
