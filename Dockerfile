FROM php:8.3-cli-alpine

WORKDIR /app

RUN apk add --no-cache git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .

RUN composer dump-autoload --optimize \
    && cp .env.example .env \
    && php artisan key:generate \
    && mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV CACHE_STORE=array
ENV SESSION_DRIVER=array
ENV LOG_CHANNEL=stderr

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}