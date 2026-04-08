# PHP su Apache serveris
FROM php:8.4-apache

# Diegiame visus sisteminius paketus vienu kartu (Sujungta dėl efektyvumo)
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-requests \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# MySQL plėtiniai
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Apache mod_rewrite (Laravel maršrutizacijai būtina)
RUN a2enmod rewrite

# Darbinis aplankas
WORKDIR /var/www/html

# Nukopijuojame kodą
COPY . .

# Teisių suteikimas (SVARBU: Laravel storage ir cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Apache konfigūracija nukreipti į /public aplanką
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Eksponuojame 80 prievadą
EXPOSE 80