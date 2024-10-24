# Use the official PHP image with Apache
FROM php:8.3-cli

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the application code to the container
COPY . .

# Copy the entrypoint script into the container
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set permissions
RUN chmod +x /usr/local/bin/entrypoint.sh
RUN chmod +x /var/www/html/database/migration.php

# Expose port 9000
EXPOSE 9000

# Set the entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
