FROM php:8.3-cli-alpine
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

ENV TZ=UTC
ENV PATH="$PATH:/var/www/vendor/bin:/var/www/bin"
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk update && apk add --no-cache git gmp-dev libzip-dev unzip bash tzdata htop
RUN docker-php-ext-install gmp zip

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/symfony.ini $PHP_INI_DIR/conf.d/symfony.ini
WORKDIR /var/www/

COPY ./src/ ./src/
COPY ./docker/ ./docker/
COPY ./composer.json ./composer.json

RUN chmod +x /var/www/docker/entrypoint.sh
ENTRYPOINT ["/bin/bash", "/var/www/docker/entrypoint.sh"]
CMD ["php", "-a"]

RUN composer install --prefer-dist --no-scripts --optimize-autoloader
