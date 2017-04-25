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
        $this->visit('/')
          ->click('Followers: Celebs')
          ->see('Followers of ' . $this->twitterHandle . ': Celebrity status');
    }

    /**
     * A basic functional test for the friends pages.
     *
     * @return void
     */
    public function testBasicFriendsPage()
    {
        $this->visit('/')
          ->click('Friends: Celebs')
          ->see('Friends of ' . $this->twitterHandle . ': Celebrity status');

        $this->visit('/')
          ->click('Friends: Old profiles')
          ->see('Friends of ' . $this->twitterHandle . ': Low activity');
    }
}
