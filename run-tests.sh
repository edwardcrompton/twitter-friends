#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "false" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q composer | xargs docker inspect  -f '{{ .State.Running }}')
  >&2 echo "Composer is installing"
  sleep 1
done

docker-compose exec php php artisan dusk
