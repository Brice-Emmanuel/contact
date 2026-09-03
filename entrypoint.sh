#!/bin/sh

# Remplacement du port dans la configuration Nginx
PORT="${PORT:-8080}"
sed -i "s/PORT_PLACEHOLDER/$PORT/g" /etc/nginx/sites-available/default

# Démarrage de PHP-FPM en arrière-plan
php-fpm -D

# Démarrage de Nginx au premier plan pour maintenir le conteneur actif
nginx -g "daemon off;"