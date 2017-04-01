<?php

class ListingPageTest extends TestCase
{
    /**
     * A basic functional test for the followers page.
     *
     * @return void
     */
    public function testBasicFollowersPage()
    {
        $this->visit('laravelphp/followers/celebs')
             ->see('Followers of laravelphp: Celebrity status');
    }

    /**
     * A basic functional test for the followers page.
     *
     * @return void
     */
    public function testBasicFriendsPage()
    {
        $this->visit('laravelphp/friends/celebs')
          ->see('Friends of laravelphp: Celebrity status');

        $this->visit('laravelphp/friends/lastupdated')
          ->see('Friends of laravelphp: Low activity');
    }
}
