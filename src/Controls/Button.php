<?php declare(strict_types=1);

namespace DynamicComponents\Controls;

class Button extends \UI\Controls\Button
{
    /** @var callable|null */
    private $onClick;

    public function __construct(string $text, ?callable $onClick = null)
    {
        parent::__construct($text);

        $this->onClick = $onClick;
    }

    public function setOnClick(callable $onClick): void
    {
        $this->onClick = $onClick;
    }

    protected function onClick(): void
    {
        if ($onClick = $this->onClick) {
            $onClick($this);
        }
    }
}
