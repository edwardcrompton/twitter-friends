<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class UpdateFollowersTest
 *
 * Tests the update followers route.
 */
class UpdateFollowersTest extends TestCase
{
    /**
     * @test
     */
    public function UpdateFollowersRouteLoadsWithText()
    {
        $this->visit($this->twitterHandle . '/updatefollowers')
          ->see('Followers updated');
    }
}
