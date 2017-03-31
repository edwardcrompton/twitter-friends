Twitter Friends
===============

A Laravel based application to provide visibility on the people you follow on 
Twitter and their recent activity.

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

To do next
----------

It's useful to store follower profiles in the db for the 'master user' but when 
drilling down to followers, of followers we can cache these more simply.

Build a screen to show followers.

Allow drill down by reinstating caching for followers of followers.

Database storage and variable storage is all file based a the moment. Making it 
db based would be better.

Concentrate on a self hosted system designed to gather information for a single
user.

Periodically check who is following you so we can tell who has unfollowed you.

Using the api or local storage, add stats showing who you've followed vs who has
followed you back vs who is unlikely to ever follow you back.

Add menu system and the ability to enter a twitter handle in a form

OR

Create a React based front end that integrates with this as an API.