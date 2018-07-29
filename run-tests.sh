#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "false" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q composer | xargs docker inspect -f '{{ .State.Running }}')
  >&2 echo "Composer is installing"
  sleep 1
done

echo "*** Testing artisan ***"
docker-compose exec php php artisan env -vvv

echo "*** Running tests ***"
docker-compose exec php php artisan dusk -vvv

# This needs to be changed to use a dynamic date.
cat storage/logs/laravel-2018-07-29.log

echo "*** Shutting down ***"
exec docker-compose down
