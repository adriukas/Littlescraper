# PHP su Apache serveris
FROM php:8.2-apache

# MySQL 
RUN docker-php-ext-install pdo pdo_mysql

# Python 
RUN apt-get update && apt-get install -y python3 python3-pip

#Apache mod_rewrite (for laravel routing)
RUN a2enmod rewrite

# Working directory
WORKDIR /var/www/html

# Copying code to container
COPY . .

# Giving writting permissions to storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apache configuration to use public folder as document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf