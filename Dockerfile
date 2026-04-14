FROM php:8.3-apache

# STEP 1: Install system libs
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# STEP 2: Install PHP extensions (WAJIB sebelum composer)
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql mbstring opcache

# STEP 3: Fix Apache MPM conflict
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# STEP 4: Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# STEP 5: Set workdir
WORKDIR /var/www/html

# STEP 6: Copy composer files saja dulu
COPY composer.json composer.lock ./

# STEP 7: Composer install (SETELAH ext terinstall)
RUN composer install \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction \
    --no-dev

# STEP 8: Copy semua source code
COPY . .

# STEP 9: Apache virtual host config
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# STEP 10: Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]