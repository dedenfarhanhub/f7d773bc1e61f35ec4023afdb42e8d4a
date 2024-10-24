#!/bin/bash

echo "Waiting for database to be ready..."
until php -r "new PDO('mysql:host=db;dbname=$DB_NAME', '$DB_USER', '$DB_PASSWORD');" > /dev/null 2>&1; do
    sleep 1
    echo -n "."
done

echo "Database is ready."
# Run migrations
echo "Running migration ..."
php /var/www/html/database/migration.php

# Start the PHP built-in server
echo "Start App"
php -S 0.0.0.0:9000 -t public
