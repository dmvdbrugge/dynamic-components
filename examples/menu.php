<?php declare(strict_types=1);

use DynamicComponents\Menu;
use DynamicComponents\MenuItem;
use DynamicComponents\Window;
use UI\Controls\MultilineEntry;
use UI\Size;

use function UI\quit;
use function UI\run;

require_once __DIR__ . '/bootstrap.php';

$file = new Menu('File');

/** @var MenuItem $new */
$new = $file->append('New');

/** @var MenuItem $open */
$open = $file->append('Open');
$file->appendSeparator();

/** @var MenuItem $save */
$save = $file->append('Save');

/** @var MenuItem $saveAs */
$saveAs = $file->append('Save As');
$file->appendSeparator();

// These items receive no click events, so we cannot do anything with them, and they don't do anything by themselves.
// I think that's a bug. Same holds for appendCheck. Concerning appendCheck, why does it even exist? We can "check" a
// "normal" menu item as well, so what's the use?
// OSX: These items will not appear on the File menu, but will be enabled on the 'php' menu. However there are
// separators getting appended each call (even though at max 1 shows).
$file->appendAbout();
$file->appendPreferences();
$file->appendQuit();

/** @var MenuItem $quit */
$quit = $file->append('Quit for real');

$log = new Menu('Log');

/** @var MenuItem $check */
$check = $log->append('Log menu clicks');
$check->setChecked(true);
$log->appendSeparator();

/** @var MenuItem $clear */
$clear = $log->append('Clear Log');

// OSX: No items, yet these menu names magically have items appended, with which we cannot interact.
$edit = new Menu('Edit');
$view = new Menu('View');
$help = new Menu('Help');

// OSX: Menu is showing anyway, even if $menu would be false.
$window = new Window('Menu Example', new Size(320, 180), true, function (Window $window) {
    $window->msg('Closing', 'This message comes from the onClosing callback.');
});

$multi = new MultilineEntry();
$multi->setReadOnly(true);
$window->add($multi);

$logClick = function (MenuItem $item) use ($check, $multi) {
    if ($check->isChecked()) {
        $multi->append("MenuItem: '{$item->getName()}' from Menu: '{$item->getParent()->getName()}'" . PHP_EOL);
    }
};

$withLog = function (callable $func) use ($logClick) {
    return function (MenuItem $menuItem) use ($func, $logClick) {
        $logClick($menuItem);
        $func($menuItem);
    };
};

$new->setOnClick($logClick);
$open->setOnClick($withLog(function () use ($window) {
    if ($opened = $window->open()) {
        $window->msg('Open file', $opened);
    }
}));

$save->setOnClick($logClick);
$saveAs->setOnClick($withLog(function () use ($window) {
    if ($saved = $window->save()) {
        $window->msg('Save as', $saved);
    }
}));

$quit->setOnClick($withLog(function () use ($window) {
    $window->msg('Quitting!', 'This message comes from the onClick callback.');

    $window->destroy();
    quit();
}));

$check->setOnClick(function (MenuItem $check) use ($logClick) {
    // If we go from checked to unchecked, this one triggers.
    $logClick($check);

    $check->setChecked(!$check->isChecked());

    // If we go from unchecked to checked, this one triggers.
    $logClick($check);
});

$clear->setOnClick(function () use ($multi) {
    $multi->setText('');
});

$window->show();

run();
