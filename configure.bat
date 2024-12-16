@echo off
cd C:/xampp/htdocs/nova
php artisan migrate --seed
php artisan storage:link
pause
