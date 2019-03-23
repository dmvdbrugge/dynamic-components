<?php declare(strict_types=1);

namespace Tests\Unit;

use DynamicComponents\Area;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\ActionSimulator;
use UI\Draw\Pen;
use UI\Key;
use UI\Point;
use UI\Size;

use function func_get_args;
use function func_num_args;

class AreaTest extends TestCase
{
    public function testAreaWorks(): void
    {
        $callFlag = 0;

        $onDraw = function () use (&$callFlag) {
            self::assertEquals(5, func_num_args());

            [$pen, $areaSize, $clipPoint, $clipSize, $control] = func_get_args();

            self::assertInstanceOf(Pen::class, $pen);
            self::assertInstanceOf(Size::class, $areaSize);
            self::assertInstanceOf(Point::class, $clipPoint);
            self::assertInstanceOf(Size::class, $clipSize);
            self::assertInstanceOf(Area::class, $control);

            $callFlag++;
        };

        $onKey = function () use (&$callFlag) {
            self::assertEquals(4, func_num_args());

            [$key, $ext, $flags, $control] = func_get_args();

            self::assertIsString($key);
            self::assertIsInt($ext);
            self::assertIsInt($flags);
            self::assertInstanceOf(Area::class, $control);

            $callFlag += 4;
        };

        $onMouse = function () use (&$callFlag) {
            self::assertEquals(4, func_num_args());

            [$areaPoint, $areaSize, $flags, $control] = func_get_args();

            self::assertInstanceOf(Point::class, $areaPoint);
            self::assertInstanceOf(Size::class, $areaSize);
            self::assertIsInt($flags);
            self::assertInstanceOf(Area::class, $control);

            $callFlag += 16;
        };

        $area  = new Area($onDraw, $onKey, $onMouse);
        $pen   = new Pen();
        $size  = new Size(640, 480);
        $point = new Point(5, 5);

        ActionSimulator::drawArea($area, $pen, $size, $point, $size);
        ActionSimulator::keyArea($area, '', Key::Right, Area::Down);
        ActionSimulator::mouseArea($area, $point, $size, Area::Up);

        self::assertEquals(16, self::getCount());
        self::assertEquals(21, $callFlag);
    }

    public function testAreaWithoutCallbacksButSetters(): void
    {
        $area  = new Area();
        $pen   = new Pen();
        $size  = new Size(640, 480);
        $point = new Point(5, 5);

        // Should all do nothing, successfully
        ActionSimulator::drawArea($area, $pen, $size, $point, $size);
        ActionSimulator::keyArea($area, '', Key::Right, Area::Down);
        ActionSimulator::mouseArea($area, $point, $size, Area::Up);

        $action = 0;

        // Set and re-set onDraw
        $area->setOnDraw(function () use (&$action) {
            $action = 1;
        });

        ActionSimulator::drawArea($area, $pen, $size, $point, $size);
        self::assertEquals(1, $action);

        $area->setOnDraw(function () use (&$action) {
            $action = 2;
        });

        ActionSimulator::drawArea($area, $pen, $size, $point, $size);
        self::assertEquals(2, $action);

        // Set and re-set onKey
        $area->setOnKey(function () use (&$action) {
            $action = 3;
        });

        ActionSimulator::keyArea($area, '', Key::Right, Area::Down);
        self::assertEquals(3, $action);

        $area->setOnKey(function () use (&$action) {
            $action = 4;
        });

        ActionSimulator::keyArea($area, '', Key::Right, Area::Down);
        self::assertEquals(4, $action);

        // Set and re-set onMouse
        $area->setOnMouse(function () use (&$action) {
            $action = 5;
        });

        ActionSimulator::mouseArea($area, $point, $size, Area::Up);
        self::assertEquals(5, $action);

        $area->setOnMouse(function () use (&$action) {
            $action = 6;
        });

        ActionSimulator::mouseArea($area, $point, $size, Area::Up);
        self::assertEquals(6, $action);
    }
}
