FROM php:8.2-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip zip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libonig-dev libxml2-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --no-interaction \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080

CMD sh -c "php artisan storage:link || true; php database/restore_db.php; php artisan serve --host=0.0.0.0 --port=\$PORT"