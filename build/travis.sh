#!/bin/bash

travis_retry composer self-update

if [[ "$TRAVIS_PHP_VERSION" == *5.6* ]]; then
    travis_retry composer require "satooshi/php-coveralls:~0.6.1" "symfony/config=~2.0" --prefer-source --no-interaction --dev;
else
    travis_retry composer install --prefer-source --no-interaction
fi
