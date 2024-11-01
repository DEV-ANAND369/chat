# Use the official PHP 8.1 image from the Docker Hub
FROM php:8.2-apache

# Set environment variable for non-interactive apt
ENV DEBIAN_FRONTEND=noninteractive

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . .

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    curl \
    dom \
    openssl \
    mbstring \
    exif \
    gd \
    pcre \
    json \
    fileinfo \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Expose port 80 to the outside world
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
