<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MenuTest extends DuskTestCase
{
    /**
     * Check the dropdown menu opens.
     *
     * @return void
     */
    public function testDropdownOpens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertDontSee('Friends: Celebs')
                    ->click('.dropdown-toggle')
                    ->assertSee('Friends: Celebs');
        });
    }

    /**
     * Check that a link in the dropdown goes to the expected path.
     */
    public function testFriendsCelebsLink() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('.dropdown-toggle')
                    ->clickLink('Friends: Celebs')
                    ->assertPathIs('/ed_crompton/friends/celebs');
        });
    }
}
