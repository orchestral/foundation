#!/bin/bash

if [[ "$TRAVIS_PHP_VERSION" == *5.6* ]]; then
    composer require "satooshi/php-coveralls:~0.6.1" "symfony/config=~2.0" "symfony/yaml=~2.0" --prefer-source --no-interaction --dev;
else
    composer install --prefer-source --no-interaction
fi
