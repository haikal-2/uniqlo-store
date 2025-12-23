FROM php:8.2-fpm

# ===============================
# SYSTEM DEPENDENCIES
# ===============================
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# PHP EXTENSIONS
# ===============================
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# ===============================
# NGINX CONFIG
# ===============================
COPY nginx.conf /etc/nginx/nginx.conf

# ===============================
# APP SETUP
# ===============================
WORKDIR /var/www/html
COPY . .

# ===============================
# COMPOSER
# ===============================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ===============================
# PERMISSIONS
# ===============================
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD service nginx start && php-fpm
