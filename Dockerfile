FROM php:8.1-fpm

# Install necessary packages
RUN apt-get update && apt-get install -y \
    git \
    nginx \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/cache/apk/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy source files
COPY . /var/www/html

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx configuration file
COPY nginx/conf.d /etc/nginx/conf.d

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose ports
EXPOSE 9000

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]
