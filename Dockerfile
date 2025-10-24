# Gunakan PHP 8.2 + Apache
FROM php:8.2-apache

# Install ekstensi dan tools tambahan
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer secara global
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file ke container
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# (Opsional) Build frontend Laravel (jika kamu pakai Vite / Mix)
RUN npm install && npm run build

# Ubah root Apache ke folder public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Buka port untuk Railway
EXPOSE 8080

# Jalankan Apache
CMD ["apache2-foreground"]
