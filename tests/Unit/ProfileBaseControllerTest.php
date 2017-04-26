<?php

use App\Http\Controllers\Twitter\FollowersController;
use App\Http\Controllers\Twitter;

/**
 * @file
 *  Contains class ProfileBaseControllerTest
 */
class ProfileBaseControllerTest extends TestCase
{
    /**
     * Unit test for the sortByLastUpdate method.
     */
    public function testSortByLastUpdate() {
        $profiles = array(
            (object) array('status' => (object) array(
                'created_at' => "11/26/2016 00:00:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "10/31/2016 00:00:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:21:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:19:00",
            )),
        );

        $followersController = App::make(FollowersController::class);
        $sortedProfiles = $followersController->sortByLastUpdate($profiles);
        
        $this->assertEquals(array(
            (object) array('status' => (object) array(
                'created_at' => "10/31/2016 00:00:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "11/26/2016 00:00:00",
            )),

            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:19:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:21:00",
            ))
        ), $sortedProfiles);
    }
}