#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "false" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q composer | xargs docker inspect composer -f '{{ .State.Running }}')
  >&2 echo "Composer is installing"
  sleep 1
done

echo "*** Testing artisan ***"
docker-compose exec php php artisan env -vvv

docker-compose exec php ls -lah storage
docker-compose exec php ls -lah bootstrap
docker-compose exec php ls -lah .

echo "*** Running tests ***"
docker-compose exec php php artisan dusk

echo "*** Shutting down ***"
exec docker-compose down
