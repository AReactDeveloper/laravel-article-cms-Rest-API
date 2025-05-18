# Use official PHP 8.2 FPM base image
FROM php:8.2-fpm

# Set working directory inside the container
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy Composer binary from official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . /var/www

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

# Set ownership of the project files to www-data user/group
RUN chown -R www-data:www-data /var/www

# Expose port 8080 for artisan serve
EXPOSE 8080

# Start the Laravel development server using artisan
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
