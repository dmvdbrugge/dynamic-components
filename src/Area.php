<?php declare(strict_types=1);

namespace DynamicComponents;

use UI\Draw\Pen;
use UI\Point;
use UI\Size;

class Area extends \UI\Area
{
    /** @var callable|null */
    private $onDraw;

    /** @var callable|null */
    private $onKey;

    /** @var callable|null */
    private $onMouse;

    public function __construct(?callable $onDraw = null, ?callable $onKey = null, ?callable $onMouse = null)
    {
        $this->onDraw  = $onDraw;
        $this->onKey   = $onKey;
        $this->onMouse = $onMouse;
    }

    public function setOnDraw(callable $onDraw): void
    {
        $this->onDraw = $onDraw;
    }

    public function setOnKey(callable $onKey): void
    {
        $this->onKey = $onKey;
    }

    public function setOnMouse(callable $onMouse): void
    {
        $this->onMouse = $onMouse;
    }

    protected function onDraw(Pen $pen, Size $areaSize, Point $clipPoint, Size $clipSize): void
    {
        if ($onDraw = $this->onDraw) {
            $onDraw($pen, $areaSize, $clipPoint, $clipSize, $this);
        }
    }

    protected function onKey(string $key, int $ext, int $flags): void
    {
        if ($onKey = $this->onKey) {
            $onKey($key, $ext, $flags, $this);
        }
    }

    protected function onMouse(Point $areaPoint, Size $areaSize, int $flags): void
    {
        if ($onMouse = $this->onMouse) {
            $onMouse($areaPoint, $areaSize, $flags, $this);
        }
    }
}
