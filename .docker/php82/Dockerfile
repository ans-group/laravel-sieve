FROM php:8.2-alpine AS apio
RUN apk add --update git \
    linux-headers \
    $PHPIZE_DEPS \
    openssh \
    zip

RUN pecl install xdebug && docker-php-ext-enable xdebug;

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG SSH_PRIVATE_KEY

WORKDIR /app
COPY ./ /app/

RUN composer install;

STOPSIGNAL SIGTERM

ENTRYPOINT ["tail", "-f", "/dev/null"]
