#!/bin/bash
set -e

echo "Local deployment started ..."

composer install
php artisan key:generate
composer dump-autoload

php artisan migrate:fresh --seed

# Clear and cache config
php artisan config:clear
php artisan cache:clear

php artisan serve --host 0.0.0.0

echo "Deployment finished!"