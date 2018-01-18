Twitter Friends
===============

[![Build Status](https://travis-ci.org/edwardcrompton/twitter-friends.svg?branch=develop)](https://travis-ci.org/edwardcrompton/twitter-friends)

A Laravel based application to provide visibility on the people you follow on 
Twitter and their recent activity.

The application shows:
- People who follow you ordered by their friends to followers ratio ('Celeb' status).
- People you follow ordered by their Celeb status
- People you follow based on how long ago they last tweeted so that you can 
consider unfollowing them.
- A list of people who have unfollowed you, periodically updated with a cron job.

Installation
------------

### Docker

Soon it should be possible to run the whole application using a few docker commands.

To build the docker container, run:
`docker build twitter-friends .`

To run a server, run
`docker run -d -p 8181:8181 twitter-friends`

However, I need to work out whether a second database container is required and where the artisan migrate command should be called.

### Database set up

Install sqlite on the homestead machine:

`sudo apt-get update`

`sudo apt-get install sqlite`

Create a database file for sqlite:

`touch storage/databases/twitterfriends.sqlite`

From the project root on the homestead machine:

`composer install`

`php artisan key:generate`

`cp .env.example .env`

Create a Twitter app at https://apps.twitter.com

Add the following lines to .env, filling in the details of your Twitter app in 
place of the xxx

`# Twitter security credentials`

`TWITTER_CONSUMER_KEY=xxx`

`TWITTER_CONSUMER_KEY_SECRET=xxx`

`TWITTER_ACCESS_TOKEN=xxx`

`TWITTER_ACCESS_TOKEN_SECRET=xxx`

Connect to the sqlite database with the following lines:

`DB_CONNECTION=sqlite`

`DB_FILE=twitterfriends.sqlite`

Run the migration to create the database tables:

`php artisan migrate`

Permissions problems with the filesystem in homestead, I solved with this

http://laravel-recipes.com/recipes/26/creating-a-nginx-virtualhost

www-conf needed to have the user changed from www-data to vagrant.

To do next
----------

Tests now run on Travis CI inside the docker container. However, they fail with 
RuntimeException: The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.

The APP_KEY environment variable seems to be correctly set inside the docker container. It's possible 
Laravel tries to do something clever when tests are run and looks for a different environment var.

Database storage and variable storage is all file based at the moment. Making it 
db based would be better.

Dockerise the build so that we can get rid of the long installation instructions and use Docker instead.

Periodically check who is following you so we can tell who has unfollowed you.
This should be done with a cron job that hits http://twitter-friends.app/[handle]/updatefollowers

Make the code more unit testable and write some extra tests.

Using the api or local storage, add stats showing who you've followed vs who has
followed you back vs who is unlikely to ever follow you back.

OR

Create a React based front end that integrates with this as an API.
