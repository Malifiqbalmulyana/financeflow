FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

# Create SQLite database
RUN touch database/database.sqlite \
    && chown www-www-data database/database.sqlite \
    && chmod 664 database/database.sqlite

EXPOSE 80
CMD ["apache2-foreground"]