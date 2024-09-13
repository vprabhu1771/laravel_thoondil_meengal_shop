# laravel_thoondil_meengal_shop
 
cp .env.example .env

# generating key
php artisan key:generate

# composer install
composer install

# filament install
composer require filament/filament:"^3.2" -W

## filament panel
php artisan filament:install --panels