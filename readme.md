Twitter Friends
===============

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
