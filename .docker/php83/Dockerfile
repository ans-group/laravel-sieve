FROM php:8.3-alpine AS apio
RUN apk add --update git \
    linux-headers \
    $PHPIZE_DEPS \
    openssh \
    zip

RUN pecl install xdebug && docker-php-ext-enable xdebug;

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG SSH_PRIVATE_KEY

RUN mkdir -p /root/.ssh && \
    chmod 0700 /root/.ssh && \
    echo "${SSH_PRIVATE_KEY}" > /root/.ssh/id_rsa && \
    chmod 600 /root/.ssh/id_rsa && \
    ssh-keyscan gitlab.devops.ukfast.co.uk > /root/.ssh/known_hosts

WORKDIR /app
COPY ./ /app/

RUN composer install;

STOPSIGNAL SIGTERM

ENTRYPOINT ["tail", "-f", "/dev/null"]
