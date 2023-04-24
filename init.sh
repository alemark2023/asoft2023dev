#!/bin/bash

echo "------------------ Checking for Composer ------------------"
composer -n install
composer -n update
composer dump-autoload

php artisan key:generate
php artisan migrate:reset
php artisan db:seed
php artisan storage:link

php artisan config:clear
php artisan config:cache
php artisan cache:clear
php artisan view:clear

php artisan migrate
php artisan tenancy:migrate

composer dumpautoload

echo "------------------ Npm ------------------"
npm install
npm run dev

echo "------------------ Updating permitions ------------------"
chmod 777 -R storage
chmod 777 -R bootstrap/cache
chmod 777 -R resources/tmpcsv
chmod 777 -R resources/leadpendientes
chmod 777 -R vendor/mpdf/mpdf/tmp
