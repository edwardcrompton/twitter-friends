#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "false" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q composer | xargs docker inspect -f '{{ .State.Running }}')
  >&2 echo "Composer is installing"
  sleep 1
done

echo "*** Showing permissions ***"
exec docker-compose exec php ls -lha /app/storage/
exec docker-compose exec php ls -lha /app/bootstrap/

echo "*** Showing logs ***"
exec docker-compose exec nginx cat /var/log/nginx/error.log

echo "*** Running tests ***"
exec docker-compose exec php php artisan dusk

echo "*** Shutting down ***"
exec docker-compose down
