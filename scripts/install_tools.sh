#!/usr/bin/env bash

if [ ! -d build ]; then
  mkdir build;
fi

echo "-- Install phpunit"
curl -sSLo build/phpunit https://phar.phpunit.de/phpunit-8.phar
chmod +x build/phpunit
