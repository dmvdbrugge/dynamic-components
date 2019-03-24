# Dynamic Components
Dynamic Components for the PHP UI extension: use callbacks instead of hardcoded actions. Features advanced versions of
the basic controls provided by the extension.

[![Build Status](https://travis-ci.org/dmvdbrugge/dynamic-components.svg?branch=master)](https://travis-ci.org/dmvdbrugge/dynamic-components)

## Usage / Examples

The most basic example is a button that changes its text when clicked:

```php
<?php

use DynamicComponents\Controls\Button;

$button = new Button('Click me!', function (Button $button) {
    $button->setText('You clicked :D');
});
```

This in contrast to having to create a hardcoded or anonymous class:

```php
<?php

use UI\Controls\Button;

$button = new class('Click me!') extends Button {
    protected function onClicked(): void
    {
        $this->setText('You clicked :D');
    }
};
```

When we take the first example a bit further, say we now want 3 buttons:

```php
<?php

use DynamicComponents\Controls\Button;

$onClick = function (Button $button) {
    $button->setText('You clicked :D');
};

$button1 = new Button('Click me!', $onClick);
$button2 = new Button('Click me!', $onClick);
$button3 = new Button('Click me!', $onClick);
```

Whereas in the `extends` form you either need to duplicate the anonymous class multiple times (duplicating logic), or
create an actual class (which gives you some additional options, but also more hassle).

Each callback will receive the component that the action triggers for as last parameter. Last mostly means only (and
thus also first), but is relevant for `Area`'s actions, as they receive multiple params already.

See also the [examples](examples), which feature several Controls used in different ways and coding styles. Every
example is a fully working program and can be run directly with php, given of course the extension is enabled. See also
[csv2qif](https://github.com/dmvdbrugge/csv2qif)'s
[FileSelect](https://github.com/dmvdbrugge/csv2qif/blob/master/src/UiComponents/FileSelect.php) for a more advanced
version of the filepicker. (Or any of its other `UiComponents`. It's kicked off in `src/Command/Ui.php` but that's the
boring part.)

## API

All components extend a class, usually the one provided by the extension. Inherited methods are unlisted but obviously
still available. All component descriptions in this section are in the same format:

[`Clickable FQN (is link to source)`](src)` extends `[`Clickable FQN (is link to its API docs)`](#api)
```php
// Constructor

// Relevant overridden methods, if any

// Additional methods
```

Extra information, if any.

### Controls

#### Button
[`DynamicComponents\Controls\Button`](src/Controls/Button.php)` extends `[`UI\Controls\Button`](https://secure.php.net/manual/en/class.ui-controls-button.php)
```php
// Constructor
public function __construct(string $text, ?callable $onClick = null)

// Additional methods
public function setOnClick(callable $onClick): void
```

#### Check
[`DynamicComponents\Controls\Check`](src/Controls/Check.php)` extends `[`UI\Controls\Check`](https://secure.php.net/manual/en/class.ui-controls-check.php)
```php
// Constructor
public function __construct(string $text, ?callable $onToggle = null, bool $checked = false)

// Additional methods
public function setOnToggle(callable $onToggle): void
```

#### ColorButton
[`DynamicComponents\Controls\ColorButton`](src/Controls/ColorButton.php)` extends `[`UI\Controls\ColorButton`](https://secure.php.net/manual/en/class.ui-controls-colorbutton.php)
```php
// Constructor
public function __construct(?callable $onChange = null, ?Color $color = null)

// Additional methods
public function setOnChange(callable $onChange): void
```

#### Combo
[`DynamicComponents\Controls\Combo`](src/Controls/Combo.php)` extends `[`UI\Controls\Combo`](https://secure.php.net/manual/en/class.ui-controls-combo.php)
```php
// Constructor
public function __construct(?callable $onSelected = null)

// Additional methods
public function setOnSelected(callable $onSelected): void
```

#### EditableCombo
[`DynamicComponents\Controls\EditableCombo`](src/Controls/EditableCombo.php)` extends `[`UI\Controls\EditableCombo`](https://secure.php.net/manual/en/class.ui-controls-editablecombo.php)
```php
// Constructor
public function __construct(?callable $onChange = null, string $text = '')

// Additional methods
public function setOnChange(callable $onChange): void
```

#### Entry
[`DynamicComponents\Controls\Entry`](src/Controls/Entry.php)` extends `[`UI\Controls\Entry`](https://secure.php.net/manual/en/class.ui-controls-entry.php)
```php
// Constructor
public function __construct(int $type = Entry::Normal, ?callable $onChange = null, string $text = '', bool $readOnly = false)

// Additional methods
public function getType(): int
public function setOnChange(callable $onChange): void
```

#### MultilineEntry
[`DynamicComponents\Controls\MultilineEntry`](src/Controls/MultilineEntry.php)` extends `[`UI\Controls\MultilineEntry`](https://secure.php.net/manual/en/class.ui-controls-multilineentry.php)
```php
// Constructor
public function __construct(int $type = MultilineEntry::Wrap, ?callable $onChange = null, string $text = '', bool $readOnly = false)

// Additional methods
public function getType(): int
public function setOnChange(callable $onChange): void
```

#### Radio
[`DynamicComponents\Controls\Radio`](src/Controls/Radio.php)` extends `[`UI\Controls\Radio`](https://secure.php.net/manual/en/class.ui-controls-radio.php)
```php
// Constructor
public function __construct(?callable $onSelected = null)

// Additional methods
public function setOnSelected(callable $onSelected): void
```

#### Slider
[`DynamicComponents\Controls\Slider`](src/Controls/Slider.php)` extends `[`UI\Controls\Slider`](https://secure.php.net/manual/en/class.ui-controls-slider.php)
```php
// Constructor
public function __construct(int $min, int $max, ?callable $onChange = null, ?int $value = null)

// Additional methods
public function getMax(): int
public function getMin(): int
public function setOnChange(callable $onChange): void
```

Because `UI\Controls\Slider` accepts `$min > $max` and then flips them, so does `DynamicComponents\Controls\Slider`.
However for clarity's sake you should pass them in the correct way, as the getters return the actual min and max.

#### Spin
[`DynamicComponents\Controls\Spin`](src/Controls/Spin.php)` extends `[`UI\Controls\Spin`](https://secure.php.net/manual/en/class.ui-controls-spin.php)
```php
// Constructor
public function __construct(int $min, int $max, ?callable $onChange = null, ?int $value = null)

// Additional methods
public function getMax(): int
public function getMin(): int
public function setOnChange(callable $onChange): void
```

Because `UI\Controls\Spin` accepts `$min > $max` and then flips them, so does `DynamicComponents\Controls\Spin`.
However for clarity's sake you should pass them in the correct way, as the getters return the actual min and max.

### Advanced Controls

#### Combo
[`DynamicComponents\AdvancedControls\Combo`](src/AdvancedControls/Combo.php)` extends `[`DynamicComponents\Controls\Combo`](#combo)
```php
// Constructor
public function __construct(string[] $options, ?callable $onSelected = null, int|string $selected = 0)

// Additional methods
public function appendAll(string[] $options): void
public function getOptions(): string[]
public function getSelectedText(): string
public function setSelectedText(string $text): void
```

#### Radio
[`DynamicComponents\AdvancedControls\Radio`](src/AdvancedControls/Radio.php)` extends `[`DynamicComponents\Controls\Radio`](#radio)
```php
// Constructor
public function __construct(string[] $options, ?callable $onSelected = null, int|string $selected = -1)

// Additional methods
public function appendAll(string[] $options): void
public function getOptions(): string[]
public function getSelectedText(): string
public function setSelectedText(string $text): void
```

### Other

#### Area
[`DynamicComponents\Area`](src/Area.php)` extends `[`UI\Area`](https://secure.php.net/manual/en/class.ui-area.php)
```php
// Constructor
public function __construct(?callable $onDraw = null, ?callable $onKey = null, ?callable $onMouse = null)

// Additional methods
public function setOnDraw(callable $onDraw): void
public function setOnKey(callable $onKey): void
public function setOnMouse(callable $onMouse): void
```

#### Executor
[`DynamicComponents\Executor`](src/Executor.php)` extends `[`UI\Executor`](https://secure.php.net/manual/en/class.ui-executor.php)
```php
// Constructor
public function __construct(int $seconds = 0, ?int $microseconds = null, ?callable $onExecute = null)

// Additional methods
public function getInterval(): int[]
public function setOnExecute(callable $onExecute): void
```

#### Menu
[`DynamicComponents\Menu`](src/Menu.php)` extends `[`UI\Menu`](https://secure.php.net/manual/en/class.ui-menu.php)
```php
// Constructor
public function __construct(string $name)

// Relevant override (default param changes)
public function append(string $name = '', string $type = \DynamicComponents\MenuItem::class): \UI\MenuItem

// Additional methods
public function getName(): string
public function getMenuItemName(\UI\MenuItem $menuItem): string
public function hasMenuItem(\UI\MenuItem $menuItem): bool
```

`Menu` has no actions itself, but is used for creating `MenuItem`s. While `DynamicComponents\MenuItem`s can be used
with a `UI\Menu`, using a `DynamicComponents\Menu` has the following advantages: it adds itself as parent, it sets the
`MenuItem`'s name, and it has `DynamicComponents\MenuItem` as default in `append()`. It's fully backwards compatible
though, so you can still create `UI\MenuItem`s with it.

#### MenuItem
[`DynamicComponents\MenuItem`](src/MenuItem.php)` extends `[`UI\MenuItem`](https://secure.php.net/manual/en/class.ui-menuitem.php)
```php
// No constructor, MenuItems are created by a Menu via append()

// Additional methods
public function getName(): ?string
public function getParent(): \DynamicComponents\Menu
public function isEnabled(): bool
public function setOnClick(callable $onClick): void
```

Name and Parent are only available when created by a `DynamicComponents\Menu`. Theoretically, name could be set
manually, but there is no guarantee that it's actually correct.

#### Window
[`DynamicComponents\Window`](src/Window.php)` extends `[`UI\Window`](https://secure.php.net/manual/en/class.ui-window.php)
```php
// Constructor
public function __construct(string $title, \UI\Size $size, bool $menu = false, ?callable $onClosing = null)

// Additional methods
public function hasMenu(): bool
public function setOnClosing(callable $onClosing): void
```

The default action on a `UI\Window` without `onClosing` is to `destroy()` itself and call `UI\quit()`. To provide the
same functionality in a flexible way `DynamicComponents\Window` does this as well, but you can prevent it by returning
`false` from the callback.

## Installation
Dynamic Components is just a single composer call away:
```
composer require dmvdbrugge/dynamic-components
```

## Background
[csv2qif](https://github.com/dmvdbrugge/csv2qif) was a happy little commandline tool, until I stumbled upon the
[PHP UI extension](https://secure.php.net/manual/en/book.ui.php) ([source](https://github.com/krakjoe/ui)). Coming from
a web-background, where callbacks are flowing freely, the idea of hardcoding every button, dropdown, and radio was not a
pleasant one. Thus the dynamic button was born, quickly followed by the advanced controls combo and radio.

Realizing this had nothing to do with the tool itself, the idea sparked to move them to a library of their own,
providing a reason to let their actionable control-friends (check, entry, etc.) join the party. So here we are.

## License
MIT License

Copyright (c) 2018-2019 Dave van der Brugge
