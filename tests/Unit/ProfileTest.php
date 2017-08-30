<?php
/**
 * @file
 *  Contains test class for the Profile object.
 */

use App\Profile;
use App\Http\Controllers\Twitter\FollowersController;

/**
 * Class ProfileTest
 *  Contains tests for the Profile object.
 */
class ProfileTest extends TestCase
{
    /**
     * @test
     */
    public function ProfileHasHandle()
    {
        $profile = factory(Profile::class)->create(['handle' => 'fakehandle']);
        $this->assertEquals('fakehandle', $profile->handle);
    }

    /**
     * @test
     */
    public function ProfileHasSerialisedProfileData()
    {
        $profile = factory(Profile::class)->create(['profile' => serialize('fakeprofile')]);
        $this->assertEquals(serialize('fakeprofile'), $profile->profile);
    }

    /**
     * @test
     */
    public function ProfileHasFriendFlag()
    {
        $profile = factory(Profile::class)->create(['friend' => 1]);
        $this->assertEquals(1, $profile->friend);
    }

    /**
     * @test
     */
    public function ProfileHasFollowerFlag()
    {
        $profile = factory(Profile::class)->create(['follower' => 1]);
        $this->assertEquals(1, $profile->follower);
    }

    /**
     * @test
     */
    public function MultipleProfilesCanBeSaved()
    {
        $profiles = [
            factory(Profile::class)->make(['id' => 987, 'handle' => 'anna']),
            factory(Profile::class)->make(['id' => 988, 'handle' => 'benny']),
            factory(Profile::class)->make(['id' => 989, 'handle' => 'cilla']),
        ];

        $followersController = App::make(FollowersController::class);
        $followersController->saveProfiles($profiles, 1);

        // Load the profiles by their ids to check they exist.
        // Also put a temp line in here to check we're using the temp db.
        $firstProfile = Profile::all();
        print_r($firstProfile);
    }
}
