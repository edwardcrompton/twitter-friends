<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class HttpClientServiceProvider extends ServiceProvider
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
    public function register()
    {
      $this->app->bind('\GuzzleHttp\Client', function() {
        return new \GuzzleHttp\Client([
          'base_uri' => 'https://www.twitter.com',
          'timeout' => 2.0,
        ]);
      });

    }
}
