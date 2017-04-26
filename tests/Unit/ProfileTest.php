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
    public function ProfileHasId()
    {
        // This is pretty useless because we could add any property to a Profile.
        // @todo: Restructure profiles so that they use getters and setters?
        $profile = new Profile;
        $profile->id = 123;
        $profile->save();
    }
}
