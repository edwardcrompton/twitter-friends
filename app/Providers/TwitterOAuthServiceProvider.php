<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterOAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
      $this->app->bind('\Abraham\TwitterOAuth\TwitterOAuth', function () {
        return new \Abraham\TwitterOAuth\TwitterOAuth(
          env('TWITTER_CONSUMER_KEY'),
          env('TWITTER_CONSUMER_KEY_SECRET'),
          env('TWITTER_ACCESS_TOKEN'),
          env('TWITTER_ACCESS_TOKEN_SECRET')
        );
      });
    }
}
