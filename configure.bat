@echo off

php artisan migrate --seed
php artisan storage:link