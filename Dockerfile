FROM composer:1.7.2 AS composer

FROM php:7-stretch

RUN apt-get update && apt-get install -y libicu-dev git unzip
RUN docker-php-ext-configure intl \
&& docker-php-ext-install intl

RUN mkdir /daux && mkdir /build

WORKDIR /daux

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy files
COPY composer.json /daux/composer.json
COPY composer.lock /daux/composer.lock

RUN composer install --prefer-dist --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

COPY bin/ /daux/bin/
COPY libs/ /daux/libs/
COPY templates/ /daux/templates/
COPY themes/ /daux/themes/
COPY tipuesearch/ /daux/tipuesearch/
COPY global.json /daux/global.json
COPY index.php /daux/index.php

RUN ln -s /daux/bin/daux /usr/local/bin/daux

WORKDIR /build

EXPOSE 8085

CMD ["daux"]
