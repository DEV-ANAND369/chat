FROM php:8.1-apache

# Set environment variable for non-interactive apt
ENV DEBIAN_FRONTEND=noninteractive

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . .

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev || { echo "Failed to install dependencies"; exit 1; } && \
    docker-php-ext-configure gd --with-freetype --with-jpeg || { echo "Failed to configure GD"; exit 1; } && \
    docker-php-ext-install -j$(nproc) \
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
        zip || { echo "Failed to install PHP extensions"; exit 1; } && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Expose port 80 to the outside world
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
