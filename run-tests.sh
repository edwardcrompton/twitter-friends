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
docker-compose exec php php artisan env -vvv --env=testing

echo "*** Running tests ***"
docker-compose exec php php artisan dusk -vvv --env=testing

echo "*** Showing the php logs ***"
docker-compose exec php cat storage/logs/laravel.log

echo "*** Shutting down ***"
exec docker-compose down
