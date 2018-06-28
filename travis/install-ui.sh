#!/bin/bash

cp ui.* `php-config --extension-dir`
echo "extension=ui.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
