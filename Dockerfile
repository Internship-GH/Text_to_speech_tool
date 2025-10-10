# =============================
# Stage 1: Build frontend assets with Node (Vite)
# =============================
FROM node:20-alpine AS frontend
WORKDIR /var/www/html

# Install frontend deps and build
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# =============================
# Stage 2: Build PHP dependencies with Composer
# =============================
FROM php:8.4-cli-alpine AS build

WORKDIR /var/www/html

#Install system dependencies and extensions for composer
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install zip pdo pdo_mysql mbstring gd bcmath

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress


# Copy compiled Vite assets from frontend stage
COPY --from=frontend /var/www/html/public /var/www/html/public

# =============================
# Stage 3: PHP + Apache
# =============================
FROM php:8.4-apache-alpine

WORKDIR /var/www/html

# Enable mod_rewrite 
RUN sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/apache2/httpd.conf


# Install required PHP extensions
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd bcmath zip

# Copy Laravel app from build stage
COPY --from=build /var/www/html /var/www/html


# Set Apache DocumentRoot to /public
RUN sed -i 's|DocumentRoot "/var/www/localhost/htdocs"|DocumentRoot "/var/www/html/public"|' /etc/apache2/httpd.conf

# Allow .htaccess overrides for Laravel
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/httpd.conf

# Allow Apache to follow symlinks in /public/storage
RUN echo '<Directory /var/www/html/public/storage>\n\
    Options FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/httpd.conf

# Fix permissions
RUN chown -R apache:apache /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Recreate storage symlink
RUN rm -rf /var/www/html/public/storage \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage


ARG PORT=8080
ENV PORT=${PORT}

# Using the dynamically allocated port
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/httpd.conf

# Expose to the port automatically set by railway
EXPOSE ${PORT}

# Start Apache
CMD ["httpd", "-D", "FOREGROUND"]
