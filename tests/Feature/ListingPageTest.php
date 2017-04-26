<?php

class ListingPageTest extends TestCase
{
    /**
     * @test
     */
    public function CelebsFollowersPageLoadsWithTitle()
    {
        $this->visit('/')
          ->click('Followers: Celebs')
          ->see('Followers of ' . $this->twitterHandle . ': Celebrity status');
    }

    /**
     * @test
     */
    public function CelebsFriendsPageLoadsWithTitle()
    {
        $this->visit('/')
          ->click('Friends: Celebs')
          ->see('Friends of ' . $this->twitterHandle . ': Celebrity status');

    }

    /**
     * @test
     */
    public function OldFriendsPageLoadsWithTitle() {
        $this->visit('/')
          ->click('Friends: Old profiles')
          ->see('Friends of ' . $this->twitterHandle . ': Low activity');

    }

    /**
     * @test
     */
    public function UnfollowersPageLoadsWithTitle()
    {
        $this->visit('/')
          ->click('Followers: Unfollowed')
          ->see('People who have unfollowed ' . $this->twitterHandle);
    }
}
