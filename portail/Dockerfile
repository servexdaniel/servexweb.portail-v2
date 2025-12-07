# Dockerfile
FROM php:8.2-fpm AS php

# Métadonnées
LABEL maintainer="daniel.ngenzi@servex.ca"

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip exif pcntl bcmath ftp \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier configuration PHP personnalisée
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code source
COPY . .

# Ajuster les permissions pour Laravel (exemple)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# Installer les dépendances PHP (production)
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Exposer le port FPM (9000 par défaut)
EXPOSE 9000

# Installer Supervisor
RUN apt-get update && apt-get install -y supervisor \
    && mkdir -p /var/log/supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copier la configuration Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/supervisor/conf.d/ /etc/supervisor/conf.d/


# Démarrer PHP-FPM
#CMD ["php-fpm"]
# Démarrer Supervisor (au lieu de php-fpm seul)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
