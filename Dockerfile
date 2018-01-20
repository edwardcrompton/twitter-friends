FROM php:7
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock
WORKDIR /app
RUN composer install --no-scripts --no-autoloader
COPY . /app
RUN composer dump-autoload --optimize && composer run-script post-install-cmd
EXPOSE 8181
CMD php artisan serve --host=0.0.0.0 --port=8181
