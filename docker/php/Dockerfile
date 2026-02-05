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

RUN echo "daemon off;" > /etc/nginx/nginx.conf && \
    echo "events { worker_connections 1024; }" >> /etc/nginx/nginx.conf && \
    echo "http {" >> /etc/nginx/nginx.conf && \
    echo "    include /etc/nginx/mime.types;" >> /etc/nginx/nginx.conf && \
    echo "    default_type application/octet-stream;" >> /etc/nginx/nginx.conf && \
    echo "    server {" >> /etc/nginx/nginx.conf && \
    echo "        listen 80;" >> /etc/nginx/nginx.conf && \
    echo "        server_name _;" >> /etc/nginx/nginx.conf && \
    echo "        root /var/www/html;" >> /etc/nginx/nginx.conf && \
    echo "        index index.php;" >> /etc/nginx/nginx.conf && \
    echo "        location / {" >> /etc/nginx/nginx.conf && \
    echo "            try_files \$uri \$uri/ /index.php?\$query_string;" >> /etc/nginx/nginx.conf && \
    echo "        }" >> /etc/nginx/nginx.conf && \
    echo "        location ~ \.php\$ {" >> /etc/nginx/nginx.conf && \
    echo "            try_files \$uri =404;" >> /etc/nginx/nginx.conf && \
    echo "            fastcgi_pass 127.0.0.1:9000;" >> /etc/nginx/nginx.conf && \
    echo "            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;" >> /etc/nginx/nginx.conf && \
    echo "            include fastcgi_params;" >> /etc/nginx/nginx.conf && \
    echo "        }" >> /etc/nginx/nginx.conf && \
    echo "    }" >> /etc/nginx/nginx.conf && \
    echo "}" >> /etc/nginx/nginx.conf

WORKDIR /var/www/html

CMD ["sh", "-c", "\
composer install ;\
chmod -R 777 /var/www/html ;\
php-fpm -D && nginx"]