@echo off
cd C:/xampp/htdocs/nova
pip install pyserial
php artisan migrate --seed
php artisan storage:link

