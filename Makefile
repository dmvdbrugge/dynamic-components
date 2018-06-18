GLOBAL_COMPOSER := $(shell command -v composer 2> /dev/null || command -v composer.phar 2> /dev/null)

ifdef GLOBAL_COMPOSER
COMPOSER=$(GLOBAL_COMPOSER)
else ifneq (, $(wildcard composer.phar))
COMPOSER=php composer.phar
else
ERR := $(error No composer or composer.phar found; please https://getcomposer.org  )
endif

.PHONY: install
install: vendor/autoload.php

vendor/autoload.php: composer.json
	$(COMPOSER) install

.PHONY: fix-cs
fix-cs: vendor/autoload.php
	vendor/bin/php-cs-fixer fix --verbose

.PNONY: fix-cs-all
fix-cs-all: vendor/autoload.php
	vendor/bin/php-cs-fixer fix --using-cache=no --verbose

.PHONY: unittests
unittests: vendor/autoload.php
	vendor/bin/phpunit --testsuite=unittests

.PHONY: tests
tests: unittests
