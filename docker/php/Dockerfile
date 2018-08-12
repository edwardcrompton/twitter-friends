FROM php:7.2-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN pecl install xdebug-2.6.0
RUN docker-php-ext-install pdo mbstring
RUN docker-php-ext-enable xdebug
WORKDIR /app
RUN chown -R www-data:www-data /app