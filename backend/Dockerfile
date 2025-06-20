FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory
COPY . /var/www/html

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Create storage directory and set permissions
RUN mkdir -p /var/www/html/storage/logs \
    && chmod -R 775 /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/storage

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"] 