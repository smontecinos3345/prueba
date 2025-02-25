FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

WORKDIR /app

COPY composer.json composer.lock /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --prefer-dist --optimize-autoloader --no-interaction --no-progress

COPY src /app/src
COPY public /app/public
COPY .htaccess /app/.htaccess

# Regenerate the optimized autoload files
RUN composer dumpautoload -oa

# Base Image with common dependencies
FROM php:8.2-apache AS base

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# production image. stage for running the app.
FROM base AS production
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY --from=builder /app /var/www/html
COPY .htaccess /var/www/html
RUN mkdir -p /var/www/html/config
COPY config/ /var/www/html/config

RUN a2enmod rewrite
CMD ["apache2-foreground"]
