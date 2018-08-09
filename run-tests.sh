#!/bin/bash

docker-compose up -d --build

echo "Waiting for composer to install..."
docker wait twitterfriends_composer_1 > /dev/null

docker-compose exec php php artisan dusk
