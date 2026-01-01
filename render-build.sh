#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --working-dir=/opt/render/project/src

# Create SQLite database
touch /opt/render/project/src/database/database.sqlite

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force