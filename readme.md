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

A dockerfile now allows the app to be run in a docker container. This gets
built by Travis CS when the develop branch gets pushed.

Work out the best practice for setting up the database

Travis currently runs no meaningful tests. It would run phpunit by default but
this doesn't happen because Travis is essentially just running a docker
container. Work out how the global variables should be imported into that
container. Work out how to run the tests that I've written in Laravel and get
a proper error code output. This might help:
http://bencane.com/2016/01/11/using-travis-ci-to-test-docker-builds/

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
