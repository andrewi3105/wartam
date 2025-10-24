# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install ekstensi dan tools
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy semua file
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Build frontend (kalau pakai Vite atau Mix)
RUN npm install && npm run build

# Pastikan Apache menuju ke folder public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Buka port 8080 (Railway pakai ini)
EXPOSE 8080

# Jalankan Laravel dengan Apache
CMD ["apache2-foreground"]
