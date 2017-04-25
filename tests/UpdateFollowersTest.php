<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class UpdateFollowersTest
 *
 * Tests the update followers page.
 */
class UpdateFollowersTest extends TestCase
{
    /**
     * Testing the update followers page.
     *
     * @return void
     */
    public function testUpdateFollowersRoute()
    {
        $this->visit($this->twitterHandle . '/updatefollowers')
          ->see('Followers updated');
    }
}
