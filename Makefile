SHELL := /bin/bash

serve:
	php -S localhost:8000 -t public
.PHONY: serve

tests:
	php ./vendor/bin/phpunit tests
.PHONY: tests

coverage:
	XDEBUG_MODE=coverage php ./vendor/bin/phpunit tests --coverage-text=php://stdout --coverage-filter=src
.PHONY: coverage
