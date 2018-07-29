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

Environment variables that are set in the OS of where PHP is running get
imported into the $_ENV array in PHP. Laravel uses these for its own
environment variables and they can be fetched with the Laravel env() function.

PHP is running inside a Docker container inside Travis, but environment
variables set in the Travis environment do not seem to be automatically sent
to the Docker container running in Travis.

Variables that are not sensitive or secret can be put in a .env.travis file
which is copied to .env before starting the Docker containers in .travis.yml.

Variables that ARE sensitive can be added to the Travis environment via the UI.
This means they don't have to be committed to the repository in an .env file.

In the docker-compose.yml file these variables are explicitly listed in the
environment section of the configuration for the containers that they need to go
into.

One drawback of this at the moment is that the variables in the Travis UI have
to be displayed in the build log, which is not secure. I think there is a way
that the variables can be encrypted and then committed to the repository.

https://docs.travis-ci.com/user/environment-variables/#defining-encrypted-variables-in-travisyml

### Development set up

The .env.travis file contains most of the configuration required for the docker
set up. Copy this file to '.env'.

You will then need to add some secret authentication keys for your Twitter app.

Create a Twitter app at https://apps.twitter.com

Add the following lines to .env, filling in the details of your Twitter app in 
place of the xxx

`# Twitter security credentials`

`TWITTER_CONSUMER_KEY=xxx`

`TWITTER_CONSUMER_KEY_SECRET=xxx`

`TWITTER_ACCESS_TOKEN=xxx`

`TWITTER_ACCESS_TOKEN_SECRET=xxx`

To do next
----------

Examine the logs in the Travis build and try to work out why the test is failing
when locally it's passing.

The build it not marked as failed when the tests fail inside docker. Does this
help? https://stackoverflow.com/questions/29568352/using-docker-compose-with-ci-how-to-deal-with-exit-codes-and-daemonized-linked

Database storage and variable storage is all file based at the moment. Making it 
db based would be better.

Periodically check who is following you so we can tell who has unfollowed you.
This should be done with a cron job that hits http://twitter-friends.app/[handle]/updatefollowers

Make the code more unit testable and write some extra tests.

Using the api or local storage, add stats showing who you've followed vs who has
followed you back vs who is unlikely to ever follow you back.

OR

Create a React based front end that integrates with this as an API.
