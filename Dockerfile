 FROM php:8.2-fpm

# Installation des dépendances système
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

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Installation des dépendances Composer
RUN composer install --no-dev --optimize-autoloader

# Permissions pour le stockage et le cache Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copie de la configuration Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Lancement : remplacement du port dynamique, démarrage de PHP-FPM puis Nginx
CMD sed -i "s/PORT_PLACEHOLDER/${PORT:-8080}/g" /etc/nginx/sites-available/default && php-fpm -D && nginx -g "daemon off;"