# Use the official PHP image from Docker Hub
FROM php:8.1-apache

# Set environment variables for non-interactive apt
ENV DEBIAN_FRONTEND=noninteractive

# Update and install dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libssl-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql curl dom openssl mbstring exif gd json fileinfo zip

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
