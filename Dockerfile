# Imagen base con PHP 8.2 y FPM
FROM php:8.2.4-fpm

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev libpng-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos para storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto 9000 (usado por PHP-FPM)
EXPOSE 9000

CMD ["php-fpm"]