<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ListingPageTest extends DuskTestCase
{
    /**
     * Checks celebrity followers list loads.
     *
     * @return void
     */
    public function testCelebrityFollowersList()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/ed_crompton/followers/celebs')
                    ->assertSee('Followers of ed_crompton: Celebrity status');
        });
    }
}
