#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "false" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q composer | xargs docker inspect -f '{{ .State.Running }}')
  >&2 echo "Composer is installing"
  sleep 1
done

echo "*** Showing permissions ***"
docker-compose exec php ls -lha /app/storage/
docker-compose exec php ls -lha /app/bootstrap/

echo "*** Testing artisan ***"
docker-compose exec php php artisan inspire -vvv

echo "*** Running tests ***"
docker-compose exec php php artisan dusk -vvv

echo "*** Showing the log ***"
docker-compose exec php cat /usr/local/var/log/php-fpm.log

echo "*** Shutting down ***"
exec docker-compose down
