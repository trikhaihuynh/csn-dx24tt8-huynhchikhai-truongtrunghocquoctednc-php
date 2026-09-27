# Image cho web server PHP 8.2 + Apache của website Trường Trung học Quốc tế DNC.
# Build context là thư mục gốc repo (xem docker-compose.yml) để COPY được cả docker/ và src/.
FROM php:8.2-apache

# Extension cần cho dự án: PDO MySQL (truy vấn CSDL), GD (xử lý ảnh upload), zip.
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev unzip \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli gd zip \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

# Cấu hình Apache (DocumentRoot = src/public) và PHP.
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

# Copy mã nguồn vào image để image tự chạy được khi không mount volume.
# Khi dev, docker-compose.yml mount ../src đè lên thư mục này.
COPY src/ /var/www/html/
RUN mkdir -p /var/www/html/public/uploads \
 && chown -R www-data:www-data /var/www/html

EXPOSE 80
