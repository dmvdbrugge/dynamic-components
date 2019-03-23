<?php declare(strict_types=1);

namespace DynamicComponents\Controls;

class Check extends \UI\Controls\Check
{
    /** @var callable|null */
    private $onToggle;

    public function __construct(string $text, ?callable $onToggle = null, bool $checked = false)
    {
        parent::__construct($text);

        $this->onToggle = $onToggle;

        if ($checked) {
            $this->setChecked(true);
        }
    }

    public function setOnToggle(callable $onToggle): void
    {
        $this->onToggle = $onToggle;
    }

    protected function onToggle(): void
    {
        if ($onToggle = $this->onToggle) {
            $onToggle($this);
        }
    }
}
