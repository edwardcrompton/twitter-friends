<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FrontPageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPageExists()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSeep('Twitter Friends');
        });
    }
}
