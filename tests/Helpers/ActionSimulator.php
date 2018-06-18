<?php

namespace Tests\Helpers;

use DynamicComponents\Controls;
use InvalidArgumentException;
use ReflectionClass;
use UI\Control;

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
    ];

    public static function act(Control $control): void
    {
        $class  = new ReflectionClass($control);
        $name   = self::getActionMethod($control);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        $method->invokeArgs($control, []);
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

        throw new InvalidArgumentException(get_class($control) . ' is not one of DynamicComponents\Controls');
    }
}
