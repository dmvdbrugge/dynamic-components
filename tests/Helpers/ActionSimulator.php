<?php declare(strict_types=1);

namespace Tests\Helpers;

use InvalidArgumentException;
use ReflectionClass;
use UI\Area;
use UI\Control;
use UI\Controls;
use UI\Draw\Pen;
use UI\Executor;
use UI\MenuItem;
use UI\Point;
use UI\Size;
use UI\Window;

use function get_class;

class ActionSimulator
{
    private const CONTROL_METHOD_MAP = [
        Controls\Button::class         => 'onClick',
        Controls\Check::class          => 'onToggle',
        Controls\ColorButton::class    => 'onChange',
        Controls\Combo::class          => 'onSelected',
        Controls\EditableCombo::class  => 'onChange',
        Controls\Entry::class          => 'onChange',
        Controls\MultilineEntry::class => 'onChange',
        Controls\Radio::class          => 'onSelected',
        Controls\Slider::class         => 'onChange',
        Controls\Spin::class           => 'onChange',
        Window::class                  => 'onClosing',
    ];

    public static function act(Control $control): void
    {
        if ($control instanceof Area) {
            throw new InvalidArgumentException('Area has multiple possible actions, use the specific methods.');
        }

        self::invokeMethod($control, self::getActionMethod($control));
    }

    public static function clickMenuItem(MenuItem $menuItem): void
    {
        self::invokeMethod($menuItem, 'onClick');
    }

    public static function drawArea(Area $area, Pen $pen, Size $areaSize, Point $clipPoint, Size $clipSize): void
    {
        self::invokeMethod($area, 'onDraw', [$pen, $areaSize, $clipPoint, $clipSize]);
    }

    public static function executeExecutor(Executor $executor): void
    {
        self::invokeMethod($executor, 'onExecute');
    }

    public static function keyArea(Area $area, string $key, int $ext, int $flags): void
    {
        self::invokeMethod($area, 'onKey', [$key, $ext, $flags]);
    }

    public static function mouseArea(Area $area, Point $areaPoint, Size $areaSize, int $flags): void
    {
        self::invokeMethod($area, 'onMouse', [$areaPoint, $areaSize, $flags]);
    }

    private static function getActionMethod(Control $control): string
    {
        // Is it a "base" control?
        if ($method = self::CONTROL_METHOD_MAP[get_class($control)] ?? false) {
            return $method;
        }

        // Not found, might be a child class
        foreach (self::CONTROL_METHOD_MAP as $class => $method) {
            if ($control instanceof $class) {
                return $method;
            }
        }

        throw new InvalidArgumentException(get_class($control) . ' is not an actionable UI\Control.');
    }

    private static function invokeMethod($object, string $methodName, array $args = []): void
    {
        $method = (new ReflectionClass($object))->getMethod($methodName);
        $method->setAccessible(true);
        $method->invokeArgs($object, $args);
    }
}
