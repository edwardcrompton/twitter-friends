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
        $this->visit($this->twitterHandle . '/followers/celebs')
             ->see('Followers of ' . $this->twitterHandle . ': Celebrity status');
    }

    /**
     * A basic functional test for the followers page.
     *
     * @return void
     */
    public function testBasicFriendsPage()
    {
        $this->visit($this->twitterHandle . '/friends/celebs')
          ->see('Friends of ' . $this->twitterHandle . ': Celebrity status');

        $this->visit($this->twitterHandle . '/friends/lastupdated')
          ->see('Friends of ' . $this->twitterHandle . ': Low activity');
    }
}
