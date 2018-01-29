FROM php:7
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN docker-php-ext-install pdo mbstring
WORKDIR /app
EXPOSE 8181
ENTRYPOINT ["php"]
