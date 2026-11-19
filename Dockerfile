# ១. ប្រើប្រាស់ PHP 8.3 Apache
FROM php:8.3-apache

# ២. ដំឡើង System dependencies
RUN apt-get update -y && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# ៣. ដំឡើង PHP Extensions ដែល Laravel ត្រូវការ
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# ៤. បើក Apache mod_rewrite សម្រាប់ Laravel Route
RUN a2enmod rewrite

# ៥. កែ DocumentRoot របស់ Apache ឲ្យចង្អុលទៅ public folder របស់ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ៦. ដំឡើង Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ៧. កំណត់ទីតាំង Folder
WORKDIR /var/www/html

# ៨. Copy កូដទាំងអស់ចូល Docker
COPY . .

# ៩. ដំឡើង Composer packages ដោយមិនយក Dev packages
RUN composer install --no-interaction --optimize-autoloader --no-dev

# ១០. ផ្ដល់សិទ្ធិ (Permissions) ទៅលើ Storage និង Bootstrap Cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ១១. កំណត់ Port សម្រាប់ Apache (Render ប្រើ Port 10000 ជាទូទៅ ឬតាម $PORT)
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf
EXPOSE 10000

# ១២. Command ដំណើរការពេល Server ចាប់ផ្ដើម
CMD php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && apache2-foreground
