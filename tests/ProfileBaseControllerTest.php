<?php

use App\Http\Controllers\Twitter\MainFollowersController;
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
                'created_at' => "31/10/2016 00:00:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "26/11/2016 00:00:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:21:00",
            )),
            (object) array('status' => (object) array(
                'created_at' => "01/01/2017 06:19:00",
            )),
        );

        $followersController = App::make('\App\Http\Controllers\Twitter\FollowersController');
        $followersController->sortByLastUpdate($profiles);

        // @todo: Make sure that $profiles is ordered correctly.
        var_dump($profiles);
    }
}