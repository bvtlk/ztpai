# Obraz bazowy z PHP 8.1 i Apache
FROM php:8.3-apache

# Instalujemy wymagane rozszerzenia
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Włączamy mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Kopiujemy pliki aplikacji do kontenera
COPY . /var/www/html

# 1) Zmień domyślny DocumentRoot z /var/www/html -> /var/www/html/public
# 2) Zezwól na .htaccess (AllowOverride All)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Na koniec daj uprawnienia www-data
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
