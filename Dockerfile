FROM php:7-alpine

RUN apk info && apk add --no-cache unzip

RUN mkdir /daux && mkdir /build

WORKDIR /daux


# Copy files
COPY bin/ /daux/bin/
COPY libs/ /daux/libs/
COPY templates/ /daux/templates/
COPY themes/ /daux/themes/
COPY tipuesearch/ /daux/tipuesearch/
COPY global.json /daux/global.json
COPY composer.json /daux/composer.json
COPY composer.lock /daux/composer.lock

# Composer install
RUN cd /daux && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
 && php composer-setup.php \
 && rm composer-setup.php \
 && php composer.phar install --prefer-dist --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader \
 && rm composer.phar

RUN ln -s /daux/bin/daux /usr/local/bin/daux

WORKDIR /build

EXPOSE 8085

CMD ["daux"]
