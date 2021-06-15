#!/usr/bin/env bash

if [ ! -d build ]; then
  mkdir build;
fi

echo "-- Install phpunit"
curl -sSLo build/phpunit https://phar.phpunit.de/phpunit-9.phar
chmod +x build/phpunit

echo "-- Install php-cs-fixer"
curl -sSLo build/php-cs-fixer https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v2.19.0/php-cs-fixer.phar
chmod +x build/php-cs-fixer
