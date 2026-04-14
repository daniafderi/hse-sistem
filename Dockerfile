FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip libpq-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        gd \
        zip \
        pdo \
        pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN rm -f .env

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 storage bootstrap/cache

# ✅ semua artisan pindah ke runtime
CMD export DB_CONNECTION=pgsql && \
    export DB_HOST=$PGHOST && \
    export DB_PORT=$PGPORT && \
    export DB_DATABASE=$PGDATABASE && \
    export DB_USERNAME=$PGUSER && \
    export DB_PASSWORD=$PGPASSWORD && \
    php artisan config:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=$PORT