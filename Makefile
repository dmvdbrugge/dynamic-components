GLOBAL_COMPOSER := $(shell command -v composer 2> /dev/null)

ifdef GLOBAL_COMPOSER
COMPOSER=composer
else
COMPOSER=php composer.phar
endif

.PHONY: install
install: vendor/autoload.php

composer.lock:
	$(COMPOSER) install

vendor/autoload.php: composer.json composer.lock
	$(COMPOSER) install

.PHONY: fix-cs
fix-cs: vendor/autoload.php
	vendor/bin/php-cs-fixer fix --verbose

.PNONY: fix-cs-all
fix-cs-all: vendor/autoload.php
	vendor/bin/php-cs-fixer fix --using-cache=no --verbose

.PHONY: tests
tests: vendor/autoload.php
	# TODO ;)
