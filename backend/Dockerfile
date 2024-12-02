# Utilise l'image PHP avec Apache
FROM php:8.1-apache

# Installe l'extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Configure la racine d'Apache pour le backend
ARG APACHE_DOCUMENT_ROOT=/var/www/html/backend
ENV APACHE_DOCUMENT_ROOT=${APACHE_DOCUMENT_ROOT}
RUN sed -ri -e 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/backend|g' /etc/apache2/sites-available/000-default.conf

# Configuration globale des permissions pour Apache
RUN echo "<Directory /var/www/html>" >> /etc/apache2/apache2.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Ajout d'un alias pour le frontend
RUN echo "Alias /frontend /var/www/html/frontend" >> /etc/apache2/apache2.conf && \
    echo "<Directory /var/www/html/frontend>" >> /etc/apache2/apache2.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Ajout d'un alias pour le backend/auth
RUN echo "Alias /backend/auth /var/www/html/backend/auth" >> /etc/apache2/apache2.conf && \
    echo "<Directory /var/www/html/backend/auth>" >> /etc/apache2/apache2.conf && \
    echo "    Options FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Active l'affichage des erreurs PHP pour le débogage
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini && \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-errors.ini

# Définir le niveau de journalisation d'Apache
RUN echo "LogLevel debug" >> /etc/apache2/apache2.conf

# Définir "index.php" comme fichier d'index par défaut
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Active mod_rewrite pour Apache
RUN a2enmod rewrite

# Ajoute ServerName pour éviter les avertissements
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copie les fichiers backend et frontend dans le conteneur
COPY backend/auth /var/www/html/backend/auth
COPY backend/config /var/www/html/backend/config
COPY backend/index.php /var/www/html/backend/
COPY frontend /var/www/html/frontend

# Définit le répertoire de travail
WORKDIR /var/www/html

# Applique des permissions sécurisées
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose le port 80
EXPOSE 80

# Commande pour démarrer Apache
CMD ["apache2-foreground"]
