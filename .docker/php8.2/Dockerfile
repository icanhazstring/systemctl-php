FROM php:8.2.0RC5-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /docker
# Workaround to keep container running
CMD ["tail", "-f", "/dev/null"]
