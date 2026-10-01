PHP_IMAGE := emagister-collections-php:8.1
PHP := docker run --rm -v "$(CURDIR):/app" -w /app --user $(shell id -u):$(shell id -g) -e COMPOSER_HOME=/tmp $(PHP_IMAGE)
COMPOSER := docker run --rm -v "$(CURDIR):/app" -w /app --user $(shell id -u):$(shell id -g) composer:2

.PHONY: install lint test phpcs cs-fixer php-image infection

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

php-image:
	docker build -t $(PHP_IMAGE) docker

infection: php-image
	$(PHP) composer run infection
