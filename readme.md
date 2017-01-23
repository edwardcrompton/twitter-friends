Twitter Friends
===============

A Laravel based application to provide visibility on the people you follow on 
Twitter and their recent activity.

Installation
------------

From the project root:

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

To do next
----------

Build a simple menu system for a single user.

Concentrate on a self hosted system designed to gather information for a single
user.

Periodically check who is following you so we can tell who has unfollowed you.

Using the api or local storage, add stats showing who you've followed vs who has
followed you back vs who is unlikely to ever follow you back.

Add menu system and the ability to enter a twitter handle in a form

OR

Create a React based front end that integrates with this as an API.