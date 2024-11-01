# Use the official PHP image from Docker Hub
FROM php:8.0-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql intl

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Copy your application code to the container
COPY . /var/www/html

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the Apache server
EXPOSE 80

# Start the Apache server in the foreground
CMD ["apache2-foreground"]
