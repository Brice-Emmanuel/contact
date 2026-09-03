FROM php:8.2-fpm

# Installation des dépendances système et des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Installation des dépendances Composer
RUN composer install --no-dev --optimize-autoloader

# Permissions pour le stockage Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configuration Nginx
RUN echo 'server {\n\
    listen 80;\n\
    index index.php index.html;\n\
    root /var/www/public;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}' > /etc/nginx/sites-available/default

EXPOSE 80

CMD php-fpm -D && nginx -g 'daemon off;'