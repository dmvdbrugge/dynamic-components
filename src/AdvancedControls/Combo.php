<?php declare(strict_types=1);

namespace DynamicComponents\AdvancedControls;

use Webmozart\Assert\Assert;

use function array_search;
use function is_string;

class Combo extends \DynamicComponents\Controls\Combo
{
    /** @var string[] */
    private $options = [];

    /**
     * @param string[]      $options    Keys are ignored!
     * @param callable|null $onSelected Gets $this as first param
     * @param int|string    $selected   Index or text of option to be selected
     */
    public function __construct(array $options, ?callable $onSelected = null, $selected = 0)
    {
        if (!is_string($selected)) {
            Assert::integerish($selected, 'Selected should be either a string or an integer, got %s.');
        }

        parent::__construct($onSelected);

        $this->appendAll($options);

        if (!is_string($selected)) {
            $this->setSelected((int) $selected);
        } else {
            $this->setSelectedText($selected);
        }
    }

    public function append(string $text): void
    {
        parent::append($text);

        $this->options[] = $text;
    }

    /**
     * @param string[] $options
     */
    public function appendAll(array $options): void
    {
        foreach ($options as $option) {
            $this->append($option);
        }
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSelectedText(): string
    {
        return $this->options[$this->getSelected()] ?? '';
    }

    public function setSelectedText(string $text): void
    {
        /*
         * \UI\Controls\Combo::setSelected() allows any index to be set,
         * however non-existing indices will become -1.
         */
        $index = array_search($text, $this->options);
        $index = $index === false ? -1 : $index;

        $this->setSelected($index);
    }
}
