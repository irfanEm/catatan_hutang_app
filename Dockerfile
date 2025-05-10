FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan (mysqli, pdo, dll)
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan mod_rewrite (jika pakai .htaccess)
RUN a2enmod rewrite

# Set working directory
WORKDIR /app

# Ubah kepemilikan folder /app
RUN chown -R www-data:www-data /app

# Expose port 80
EXPOSE 80

COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf
