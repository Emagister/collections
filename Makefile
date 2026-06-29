PHP      := docker run --rm -v "$(PWD):/app" -w /app php:8.1-cli
COMPOSER := docker run --rm -v "$(PWD):/app" -w /app composer:2

.PHONY: install lint test phpcs cs-fixer

install:
	$(COMPOSER) install --no-interaction --prefer-dist

lint:
	$(PHP) find src tests -name "*.php" -exec php -l {} \;

test:
	$(PHP) vendor/bin/phpunit

phpcs:
	$(PHP) vendor/bin/phpcs src tests

cs-fixer:
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --diff
