#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z db 3306; do
    sleep 1
done

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm
