FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

ServerName localhost


# Copier les fichiers du backend dans le conteneur
COPY . /var/www/html

# Donner les droits nécessaires au répertoire
RUN chown -R www-data:www-data /var/www/html

# Exposer le port pour Apache
EXPOSE 80

