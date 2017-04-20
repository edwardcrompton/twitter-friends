<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * The twitter handle to use to run tests.
     */
    protected $twitterHandle = 'ed_crompton';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->baseUrl = env('APP_URL');

        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        // Run all the migrations to set up the application.
        Artisan::call('migrate');
    }

    public function tearDown()
    {
        // Rest all the migrations ready for the next test.
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
