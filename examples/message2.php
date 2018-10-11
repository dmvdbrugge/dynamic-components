<?php

use DynamicComponents\Controls\Button;
use DynamicComponents\Controls\Entry;
use DynamicComponents\Controls\MultilineEntry;
use UI\Controls\Box;
use UI\Size;
use UI\Window;

require_once __DIR__ . '/bootstrap.php';

class MessageExampleWindow extends Window
{
    private $message = 'Message can be changed too!' . PHP_EOL . 'It even supports newlines :)';
    private $title   = 'Title - Change me!';

    public function __construct()
    {
        parent::__construct('Message 2 Example', new Size(400, 200));

        $box = new Box(Box::Vertical);
        $box->setPadded(true);

        $box->append(new Entry(Entry::Normal, [$this, 'onTitleChange'], $this->title));
        $box->append(new MultilineEntry(MultilineEntry::Wrap, [$this, 'onMessageChange'], $this->message));
        $box->append(new Button('Click me', $this));

        $this->setMargin(true);
        $this->add($box);
    }

    public function __invoke(Button $button): void
    {
        $button->setText('Clicked!');
        $this->msg($this->title, $this->message);
    }

    public function onMessageChange(MultilineEntry $message): void
    {
        $this->message = $message->getText();
    }

    public function onTitleChange(Entry $title): void
    {
        $this->title = $title->getText();
    }
}

$window = new MessageExampleWindow();
$window->show();

UI\run();
