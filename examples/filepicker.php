<?php

use DynamicComponents\Controls\Button;
use DynamicComponents\Controls\Entry;
use UI\Controls\Box;
use UI\Size;
use UI\Window;

require_once __DIR__ . '/bootstrap.php';

$window = new Window('Filepicker Example', new Size(500, 50));
$file   = new Entry(Entry::Normal, null, '', true);

$onClick = function () use ($window, $file): void {
    if ($selected = $window->open()) {
        $file->setText($selected);
    }
};

$button = new Button('Select a file', $onClick);

$box = new Box(Box::Horizontal);
$box->setPadded(true);
$box->append($button);
$box->append($file, true);

$wrapper = new Box(Box::Vertical);
$wrapper->append($box);

$window->setMargin(true);
$window->add($wrapper);
$window->show();

UI\run();
