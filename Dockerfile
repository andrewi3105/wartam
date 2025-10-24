# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install ekstensi yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer (supaya perintah composer install bisa jalan)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy semua file proyek ke container
COPY . .

# Ubah konfigurasi Apache agar menggunakan folder /public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Jalankan Laravel
CMD php artisan key:generate && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
