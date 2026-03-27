FROM php:8-apache

# Activer les modules Apache nécessaires
RUN a2enmod rewrite && \
    a2enmod headers && \
    a2enmod deflate

# Installer les extensions PHP nécessaires (si MySQL n'est pas disponible par défaut)
RUN docker-php-ext-install pdo pdo_mysql

# Exposer le port
EXPOSE 80
