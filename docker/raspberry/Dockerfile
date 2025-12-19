# Stage 1: Frontend Build
FROM node:22-slim AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

# Stage 2: Application
FROM ubuntu:24.04

WORKDIR /var/www/html

# Fix timezone
RUN ln -snf /usr/share/zoneinfo/UTC /etc/localtime && echo UTC > /etc/timezone

# Install base dependencies first
RUN apt-get update && apt-get install -y --no-install-recommends \
    gnupg \
    gosu \
    curl \
    ca-certificates \
    zip \
    unzip \
    git \
    supervisor \
    sqlite3 \
    nginx \
    libcap2-bin \
    libpng-dev \
    dnsutils \
    software-properties-common \
    && mkdir -p /etc/apt/keyrings \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Add ondrej/php repository for PHP 8.4
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y \
    && apt-get update

# Install PHP 8.4 with all required extensions
RUN apt-get install -y --no-install-recommends \
    php8.4-cli \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-sqlite3 \
    php8.4-curl \
    php8.4-gd \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-soap \
    php8.4-intl \
    php8.4-readline \
    php8.4-ldap \
    php8.4-msgpack \
    php8.4-igbinary \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/apt/archives/*

# Copy Frontend Build
COPY --from=frontend-builder /app/public/build public/build

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP Dependencies (cache this layer)
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts \
    && rm -rf /root/.composer/cache

# Copy App Files
COPY . .

# Run post-install scripts
RUN composer run-script post-autoload-dump

# Configure Nginx
RUN rm -f /etc/nginx/sites-enabled/default
COPY docker/raspberry/nginx.conf /etc/nginx/nginx.conf
COPY docker/raspberry/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copy supervisor config
RUN mkdir -p /var/log/supervisor
COPY docker/raspberry/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy PHP config
COPY docker/raspberry/php.ini /etc/php/8.4/cli/conf.d/99-custom.ini
COPY docker/raspberry/php.ini /etc/php/8.4/fpm/conf.d/99-custom.ini

# Configure PHP-FPM to use port 9000 and catch worker output
RUN sed -i 's/listen = \/run\/php\/php8.4-fpm.sock/listen = 127.0.0.1:9000/' /etc/php/8.4/fpm/pool.d/www.conf \
    && echo "catch_workers_output = yes" >> /etc/php/8.4/fpm/pool.d/www.conf \
    && echo "decorate_workers_output = no" >> /etc/php/8.4/fpm/pool.d/www.conf

# Create PHP-FPM log file with proper permissions
RUN install -o www-data -g www-data -m 664 /dev/null /var/log/php8.4-fpm.log

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s \
    CMD curl -f http://localhost/ || exit 1

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
