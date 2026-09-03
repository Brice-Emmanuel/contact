FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default
COPY entrypoint.sh /var/www/entrypoint.sh

# Conversion des fin de lignes (CRLF -> LF) et attribution des droits d'exécution
RUN apt-get update && apt-get install -y dos2unix \
    && dos2unix /var/www/entrypoint.sh \
    && chmod +x /var/www/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/var/www/entrypoint.sh"]