FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libzip-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring gd zip bcmath exif \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["bash", "docker/start.sh"]