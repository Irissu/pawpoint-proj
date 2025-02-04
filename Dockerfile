# Imagen base con PHP 8.2.4 y FPM
FROM php:8.2.4-fpm

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev libpng-dev libicu-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring zip gd intl


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

CMD ["php-fpm"]
