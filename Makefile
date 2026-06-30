PHP      := docker run --rm -v "$(CURDIR):/app" -w /app --user $(shell id -u):$(shell id -g) php:8.1-cli
COMPOSER := docker run --rm -v "$(CURDIR):/app" -w /app --user $(shell id -u):$(shell id -g) composer:2

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
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes
