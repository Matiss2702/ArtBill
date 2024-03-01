FROM php:8.1-fpm

# Mettre à jour les paquets et installer les dépendances requises
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libpq-dev \
        && docker-php-ext-install zip \
        && docker-php-ext-install pdo pdo_pgsql \
        && rm -rf /var/lib/apt/lists/*

# Installer Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
        && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
        && rm composer-setup.php

# Installer Node.js et npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
        apt-get install -y nodejs

# Assurez-vous que le répertoire des logs peut être écrit par l'application
RUN mkdir -p /var/www/symfony/var/log && chmod -R 777 /var/www/symfony/var/log

WORKDIR /var/www/symfony

# Optional: Expose port 9000 for fpm
EXPOSE 9000
