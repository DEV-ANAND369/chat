# Use the official PHP image from Docker Hub
FROM php:8.0-apache

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libjpeg-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libfreetype6-dev \
    libimap-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install and enable mysqli, pdo, pdo_mysql, and intl extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql intl

# Install and enable Exif extension
RUN docker-php-ext-install exif

# Install and enable GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install and enable Zip extension
RUN docker-php-ext-install zip

# Install and enable IMAP extension
RUN docker-php-ext-install imap

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
