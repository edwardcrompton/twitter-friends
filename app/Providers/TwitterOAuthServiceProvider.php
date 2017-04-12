<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
            config('services.twitter.key'),
            config('services.twitter.key_secret'),
            config('services.twitter.token'),
            config('services.twitter.token_secret')
        );
      });
    }
}
