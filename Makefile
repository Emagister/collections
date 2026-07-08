COMPOSER := docker run --rm -v "$(CURDIR):/app" -w /app --user $(shell id -u):$(shell id -g) composer:2

.PHONY: install lint test phpcs cs-fixer

install:
	$(COMPOSER) install --no-interaction --prefer-dist

lint:
	$(COMPOSER) run lint

test:
	$(COMPOSER) run tests

phpcs:
	$(COMPOSER) run phpcs

cs-fixer:
	$(COMPOSER) run cs-fixer
