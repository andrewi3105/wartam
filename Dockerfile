FROM php:8.2-apache

# Install dependency PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Copy semua file ke container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Ganti root Apache ke /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Tambahkan ServerName supaya tidak ada warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Izinkan Apache dengar di port Railway ($PORT)
ENV PORT=8080
EXPOSE 8080

# Jalankan Apache di foreground
CMD ["apache2-foreground"]
