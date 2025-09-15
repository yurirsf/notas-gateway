FROM php:8.2-fpm-buster as base

# Declares all arguments
ARG APP_ENV=dev
ARG APP_DEBUG=true
ARG DEPLOY_CMD=php-fpm

# Database
ARG DB_USER
ARG DB_PASSWORD
ARG DB_HOST
ARG DB_PORT
ARG DB_NAME

# Datadog
ARG DD_SERVICE
ARG DD_ENV
ARG DD_VERSION
ARG DD_APM_NON_LOCAL_TRAFFIC
ARG DD_AGENT_HOST
ARG DD_TRACE_AGENT_PORT

# Bind all arguments to environment variables
ENV APP_ENV    $APP_ENV
ENV APP_DEBUG  $APP_DEBUG
ENV DEPLOY_CMD $DEPLOY_CMD

# Database configs
ENV DB_USER              $DB_USER
ENV DB_PASSWORD          $DB_PASSWORD
ENV DB_HOST              $DB_HOST
ENV DB_PORT              $DB_PORT
ENV DB_NAME              $DB_NAME

# Datadog configs
ENV DD_SERVICE               $DD_SERVICE
ENV DD_ENV                   $DD_ENV
ENV DD_VERSION               $DD_VERSION
ENV DD_AGENT_HOST            $DD_AGENT_HOST
ENV DD_TRACE_AGENT_PORT      $DD_TRACE_AGENT_PORT

ENV DD_APM_NON_LOCAL_TRAFFIC=1
ENV DD_LOGS_ENABLED=true

# Defines composer superuser
ENV COMPOSER_ALLOW_SUPERUSER="1"

# Includes console command into PATH
ENV PATH="/app/bin:/app/vendor/bin:${PATH}"

# Creates a workdir
WORKDIR /app

# Exposes NGiNX port
EXPOSE 8080

# Instala dependencias do sistema operacional
RUN apt update -y && \ 
    apt install -y git libzip-dev libicu-dev nginx $PHPIZE_DEPS

# Configure NGiNX
ADD ./provision/docker/nginx/default.conf /etc/nginx/sites-enabled/default.conf

# Configure PHP-FPM
ADD ./provision/docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# Overwrites entrypoint
ADD ./provision/docker/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Configurações do PHP
ADD provision/docker/php/php.${APP_ENV}.ini /usr/local/etc/php/php.ini

# NGiNX
RUN rm /usr/local/etc/php-fpm.d/zz-docker.conf && \
    rm /etc/nginx/sites-enabled/default && \
    mkdir -p /run/php

# Permission of execution entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Sets entrypoint
ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint.sh"]

# Command
CMD ${DEPLOY_CMD}

# Copy composer.phar
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala as extensões comuns a todos ambientes
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install opcache && \
    docker-php-ext-install zip && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-enable zip && \
    docker-php-ext-enable opcache

FROM base as ci

RUN pecl install xdebug-3.2.2 && \
    docker-php-ext-enable xdebug

FROM ci as dev

ARG XDEBUG_REMOTE_HOST="172.23.0.1"
RUN sed -i "s|xdebug.client_host=localhost|xdebug.client_host=$XDEBUG_REMOTE_HOST|g" /usr/local/etc/php/php.ini

FROM base as prod

# Datadog
RUN cd /tmp/ && \
    curl -sSL https://github.com/DataDog/dd-trace-php/releases/download/0.75.0/datadog-php-tracer_0.75.0_amd64.deb --output datadog-php-tracer.deb && \ 
    dpkg -i datadog-php-tracer.deb

# Creates  cache folders and changes your permissions
RUN mkdir -m 777 -p /app/var/cache /app/var/log  \
    && chown -R www-data:www-data /app/var

COPY ./composer.json  /app/composer.json
COPY ./composer.lock  /app/composer.lock

RUN composer install \
    --no-dev --optimize-autoloader \
    --no-ansi --no-interaction \ 
    --quiet --no-scripts

COPY ./bin          /app/bin
COPY ./config       /app/config
COPY ./migrations   /app/migrations
COPY ./public       /app/public
COPY ./src          /app/src
