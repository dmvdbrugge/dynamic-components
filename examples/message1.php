<?php

use DynamicComponents\Controls\Button;
use DynamicComponents\Controls\Entry;
use DynamicComponents\Controls\MultilineEntry;
use UI\Controls\Box;
use UI\Size;
use UI\Window;

require_once __DIR__ . '/bootstrap.php';

$window  = new Window('Message 1 Example', new Size(400, 200));
$title   = new Entry(Entry::Normal, null, 'Title - Change me!');
$message = new MultilineEntry(MultilineEntry::Wrap, null, 'Message can be changed too!
It even supports newlines :)');

$button = new Button(
    'Click me',
    function (Button $btn) use ($window, $title, $message) {
        $btn->setText('Clicked!');
        $window->msg($title->getText(), $message->getText());
    }
);

$box = new Box(Box::Vertical);
$box->setPadded(true);

$box->append($title);
$box->append($message);
$box->append($button);

$window->setMargin(true);
$window->add($box);
$window->show();

UI\run();
