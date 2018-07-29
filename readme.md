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

To build and run the application:

`docker-compose up -d`

To run the tests:

`./run-tests.sh`

Travis CI
---------

Tests currently fail in Travis.

Environment variables that are set in the OS of where PHP is running get
imported into the $_ENV array in PHP. Laravel uses these for its own
environment variables and they can be fetched with the Laravel env() function.

PHP is running inside a Docker container inside Travis, but environment
variables set in the Travis environment do not seem to be automatically sent
to the Docker container running in Travis.

Variables the are not sensitive or secret can be put in a .env.travis file which
is copied to .env before starting the Docker containers in .travis.yml.

Variables that ARE sensitive can be added to the Travis environment via the UI.
This means they don't have to be committed to the repository in an .env file.

In the docker-compose.yml file these variables are explicitly listed in the
environment section of the configuration for the containers that they need to go
into.

However, when checking out the repository locally and running ./run-tests the
tests also fail. There may be a build step missing from the docker config.

### Database set up [Deprecated]

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

Get Travis to run tests successfully.
We have one test. It runs locally when running ./run-tests.sh and passes.
In Travis it fails.

There is some extra output in Travis now which should help debug this.

Try installing artisan tinker. Then we could view the PHP configuration and see
why PHP errors aren't getting sent to stdout. This would help as they'd be
easier to display in travis then.

Remember that env vars have to be added to the Travis UI. Not sure why they
don't load from .travis.yml. Could there still be an env var that's present
locally but not on Travis. Compare .env which Travis doesn't have.

Tests run locally inside the docker container.

Database storage and variable storage is all file based at the moment. Making it 
db based would be better.

Periodically check who is following you so we can tell who has unfollowed you.
This should be done with a cron job that hits http://twitter-friends.app/[handle]/updatefollowers

Make the code more unit testable and write some extra tests.

Using the api or local storage, add stats showing who you've followed vs who has
followed you back vs who is unlikely to ever follow you back.

OR

Create a React based front end that integrates with this as an API.
