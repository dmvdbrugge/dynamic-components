# Dynamic Components
Dynamic Components for the PHP UI extension: use callbacks instead of hardcoded actions.
Features advanced versions of the basic controls provided by the extension.

## Disclaimer
This is still a work in progress.

**TODO**
* Update this README with documentation and examples
* Implement Travis for automated testing. Does it even support php ui?
* php-cs-fixer in Travis? (fabbot.io not public :sob: )
* Tag either v0.1.0 or v1.0.0 :wink:

## Usage
*TODO: Describe usage*

## API

### Basic Controls
*TODO: Describe basic Controls*

### Advanced Controls
*TODO: Describe AdvancedControls*

## Installation
Dynamic Components is just a single composer call away:
```
composer require dmvdbrugge/dynamic-components dev-master
```
As there are no releases yet, don't leave out the last argument.

## Background
[csv2qif](https://github.com/dmvdbrugge/csv2qif) was a happy little commandline tool, until I stumbled upon the PHP UI
extension. Coming from a web-background, where callbacks are flowing freely, the idea of hardcoding every button,
dropdown, and radio was not a pleasant one. Thus the dynamic button was born, quickly followed by the advanced controls
combo and radio.

Realizing this had nothing to do with the tool itself, the idea sparked to move them to a library of their own,
providing a reason to let their actionable control-friends (check, entry, etc.) join the party. So here we are.

## License
MIT License

Copyright (c) 2018 Dave van der Brugge
