# use a multistaged docker file
# so composer is not in the final image
# minimizing attack surface
FROM php:8.2-cli AS builder

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --prefer-dist --optimize-autoloader --no-interaction --no-progress

COPY src /app/src
COPY public /app/public
COPY tests /app/tests

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

#test image including development dependencies
FROM builder AS test

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=builder /app /var/www/html
COPY tests phpunit.xml /var/www/html

WORKDIR /var/www/html
RUN composer dumpautoload -oa

RUN curl -o /usr/local/bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh \
    && chmod +x /usr/local/bin/wait-for-it.sh

COPY docker/tests/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["unit"]
