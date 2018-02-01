#!/bin/bash

docker-compose up -d --build

COMPOSER_INSTALLING=1

until [ "$COMPOSER_INSTALLING" == "0" ]; do
  COMPOSER_INSTALLING=$(docker-compose ps -q php | xargs docker inspect -f '{{ .State.ExitCode }}')
  >&2 echo "Composer is installing"
  sleep 5
done

exec docker-compose run php ./vendor/bin/phpunit

exec docker-compose down
