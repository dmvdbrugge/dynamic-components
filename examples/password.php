<?php declare(strict_types=1);

use DynamicComponents\Controls\Check;
use DynamicComponents\Controls\Entry;
use UI\Controls\Box;
use UI\Size;
use UI\Window;

require_once __DIR__ . '/bootstrap.php';

$window   = new Window('Password Toggle Example', new Size(500, 50));
$password = new Entry(Entry::Password);
$revealed = new Entry(Entry::Normal);
$revealed->hide();

$onToggle = function (Check $reveal) use ($password, $revealed): void {
    if ($reveal->isChecked()) {
        $password->hide();
        $revealed->show();
    } else {
        $revealed->hide();
        $password->show();
    }
};

$reveal = new Check('Reveal', $onToggle);
$update = function () use ($reveal, $password, $revealed): void {
    if ($reveal->isChecked()) {
        $password->setText($revealed->getText());
    } else {
        $revealed->setText($password->getText());
    }
};

$password->setOnChange($update);
$revealed->setOnChange($update);

$box = new Box(Box::Horizontal);
$box->setPadded(true);
$box->append($password, true);
$box->append($revealed, true);
$box->append($reveal);

$wrapper = new Box(Box::Vertical);
$wrapper->append($box);

$window->setMargin(true);
$window->add($wrapper);
$window->show();

UI\run();
