# Multi-stage build for Laravel application
FROM php:8.4-fpm-alpine AS base

# Install system dependencies and build tools
RUN apk add --no-cache \
    nginx \
    supervisor \
    mariadb-dev \
    mariadb-client \
    libzip-dev \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype \
    freetype-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    git \
    curl \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        zip \
        bcmath \
        opcache \
        gd

# Clean up build dependencies but keep runtime ones
RUN apk del autoconf g++ make

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies and build assets
RUN npm ci && npm run build && rm -rf node_modules

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure PHP-FPM
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Configure Nginx
COPY docker/raspberry/nginx.conf /etc/nginx/nginx.conf
COPY docker/raspberry/default.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/raspberry/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/raspberry/php.ini "$PHP_INI_DIR/conf.d/custom.ini"

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s \
    CMD curl -f http://localhost/ || exit 1

# Expose port
EXPOSE 80

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
