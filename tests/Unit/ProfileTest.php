<?php
/**
 * @file
 *  Contains test class for the Profile object.
 */

use App\Profile;

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
}
