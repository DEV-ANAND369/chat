# Use the official PHP image from Docker Hub
FROM php:8.1-apache

# Set environment variables for non-interactive apt
ENV DEBIAN_FRONTEND=noninteractive

# Update and install dependencies separately for troubleshooting
RUN apt-get update && \
    apt-get install -y libcurl4-openssl-dev && \
    apt-get install -y libssl-dev && \
    apt-get install -y libxml2-dev && \
    apt-get install -y libjpeg62-turbo-dev && \
    apt-get install -y libpng-dev && \
    apt-get install -y libfreetype6-dev && \
    apt-get install -y libzip-dev && \
    apt-get install -y libonig-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql curl dom openssl mbstring exif json fileinfo zip

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
