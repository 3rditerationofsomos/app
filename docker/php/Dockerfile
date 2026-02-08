FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

CMD ["sh", "-c", "\
    composer install ;\
    chown -R www-data:www-data /var/www/html ;\
    php-fpm -D && nginx"]