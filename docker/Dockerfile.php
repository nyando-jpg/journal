FROM php:8.4-apache

# Activer les modules Apache nécessaires
RUN a2enmod rewrite && \
    a2enmod headers && \
    a2enmod deflate

# Installer les dépendances système (unzip et git pour Composer)
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier le projet
WORKDIR /var/www
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Crédits des permissions pour Apache
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www

# Exposer le port
EXPOSE 80
