FROM php:8.2-apache

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

# STEP 3: Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# STEP 4: Set workdir
WORKDIR /var/www/html

# STEP 5: Copy composer files saja dulu
COPY composer.json composer.lock ./

# STEP 6: Composer install (SETELAH ext terinstall)
RUN composer install \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction \
    --no-dev

# STEP 7: Copy semua source code
COPY . .

# STEP 8: Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# STEP 9: Apache mod rewrite
RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]