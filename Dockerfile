# Stage 1: Obtener Composer de la imagen oficial
FROM composer:2 AS composer-stage

# Stage 2: Imagen final basada en PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libcurl4-openssl-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    libwebp-dev \
    libxpm-dev \
    libicu-dev \
    libpq-dev \
    libbz2-dev \
    libgmp-dev \
    libldap2-dev \
    libsodium-dev \
    libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip mbstring exif pcntl bcmath gd phar intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Asegurar que el directorio /tmp tiene permisos adecuados
RUN chmod 1777 /tmp

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar el código de la aplicación
COPY . /var/www/html

# Establecer permisos para directorios de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar Composer desde el stage anterior
COPY --from=composer-stage /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# Agregar el directorio como seguro para Git
RUN git config --global --add safe.directory /var/www/html

# Instalar dependencias de Composer
RUN composer install --optimize-autoloader --no-dev

# Configurar Apache para Laravel
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar configuración de Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]