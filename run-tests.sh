#!/bin/bash

docker-compose up -d --build

echo "Waiting for composer to install..."
docker wait twitterfriends_composer_1 > /dev/null

# Setting file permissions.
docker-compose exec php chmod -R 777 storage
docker-compose exec php chmod -R 777 bootstrap/cache

docker-compose exec php php artisan dusk
