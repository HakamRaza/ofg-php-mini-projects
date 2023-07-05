FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo_mysql bcmath

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Run Composer
RUN composer install

# # Configure PHP
COPY .docker/php.ini $PHP_INI_DIR/conf.d/opcache.ini

# Use default development configuration
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Create user based on the provided user ID
ARG HOST_UID
RUN adduser --disabled-password --gecos "" -u $HOST_UID backend

# Switch to created user
USER backend

