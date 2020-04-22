#!/usr/bin/env bash

if [ ! -d build ]; then
  mkdir build;
fi

echo "-- Install phpunit"
curl -sSLo build/phpunit https://phar.phpunit.de/phpunit-8.phar
chmod +x build/phpunit

echo "-- Install php-cs-fixer"
curl -sSLo build/php-cs-fixer https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v2.16.3/php-cs-fixer.phar
chmod +x build/php-cs-fixer

echo "-- Install phan"
curl -sSLo build/phan https://github.com/phan/phan/releases/download/2.7.1/phan.phar
chmod +x build/phan

echo "-- Install psalm"
curl -sSLo build/psalm https://github.com/vimeo/psalm/releases/download/3.11.2/psalm.phar
chmod +x build/psalm

echo "-- Install phpstan"
curl -sSLo build/phpstan https://github.com/phpstan/phpstan/releases/download/0.12.19/phpstan.phar
chmod +x build/phpstan
