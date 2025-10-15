FROM php:8.3-cli-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp
RUN docker-php-ext-install zip pdo pdo_mysql mbstring gd bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

#Creates symlink
RUN  php artisan storage:link --force || true

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", $PORT]
