FROM php:8.1-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
