#!/bin/sh

# Generate App Key
php artisan key:generate

# Wait till database container is up
php artisan wait:db

# Migrate Database
php artisan migrate

exec "$@"