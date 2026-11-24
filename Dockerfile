FROM php:8.2-fpm

#Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip 

#Dependencias de PHP necesarias para Laravel

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Limites de subida de archivos (Laravel valida hasta 20MB en DocumentoController)
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

#Instalacion de composer php
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

#Instalar dependencias del proyecto 
RUN composer install --optimize-autoloader --no-dev

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

