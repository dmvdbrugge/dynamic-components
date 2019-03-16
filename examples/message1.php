<?php

use DynamicComponents\Controls\Button;
use DynamicComponents\Controls\Entry;
use DynamicComponents\Controls\MultilineEntry;
use DynamicComponents\Window;
use UI\Controls\Box;
use UI\Size;

require_once __DIR__ . '/bootstrap.php';

$window  = new Window('Message 1 Example', new Size(400, 200));
$title   = new Entry(Entry::Normal, null, 'Title - Change me!');
$message = new MultilineEntry(MultilineEntry::Wrap, null, 'Message can be changed too!
It even supports newlines :)');

$clicks = 0;
$button = new Button(
    'Click me',
    function (Button $btn) use ($window, $title, $message, &$clicks) {
        $btn->setText('Clicked!');
        $clicks++;
        $window->msg($title->getText(), $message->getText());
    }
);

$window->setOnClosing(function (Window $window) use (&$clicks) {
    $window->msg('Clicks', "You clicked {$clicks} times!");
});

$box = new Box(Box::Vertical);
$box->setPadded(true);

$box->append($title);
$box->append($message);
$box->append($button);

$window->setMargin(true);
$window->add($box);
$window->show();

UI\run();
