FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY . .

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000