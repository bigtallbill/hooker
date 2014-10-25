#!/bin/bash

php ../vendor/phpunit/phpunit/phpunit -c unit-nocover.xml "$@"
