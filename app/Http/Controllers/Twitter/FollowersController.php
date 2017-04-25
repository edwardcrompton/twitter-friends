<?php
/**
 * @file
 *  Contains FollowersController class.
 */

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use Setting;
use App\Profile;

/**
 * FollowersController class for handling actions to do with followers.
 */
class FollowersController extends ProfileBaseController {

    // A string to denote sorting by 'celebrity status'.
    const SORTING_CELEB_STATUS = 'celebs';
    
    /**
     * Display a list of your followers by celeb status.
     *
     * @param string $screenName
     *  The twitter handle of the user whos followers we want to show.
     * @param string $sorting
     *  A string to denote the type of sorting used.
     * @param Request $request
     *  The page request object.
     *
     * @return string
     *  A view to render.
     */
    public function showFollowers($screenName, $sorting, Request $request) {
        $this->screenName = $screenName;
        
        switch ($sorting) {
            case self::SORTING_CELEB_STATUS:
                // If we're looking at the followers of the main handle, then
                // we can cache the results, otherwise fetch them fresh.
                $cacheable = $this->screenName == config('services.twitter.user');
                if ($cacheable) {
                    $followers = $this->sortByFollowersFriendsRatio($this->getSavedFollowers());
                }
                else {
                    $followers = $this->sortByFollowersFriendsRatio($this->loadFollowersFromRemote());
                }

                $profileType = 'celeb';
                $title = 'Followers of ' . $this->screenName . ': Celebrity status';
                break;
            default:
                $profileType = 'friend';
                // No sorting.
        }        
        $paginatedFollowers = $this->paginateProfiles($followers, $request);
           
        return view('reports.profiles', [
          'title' => $title,
          'profiles' => $paginatedFollowers,
          'profiletype' => $profileType,  
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER,     
        ]);
    }

    /**
     * Display a list of people who've unfollowed you.
     *
     * @param $screenName
     *  The twitter handle of the main user.
     * @param Request $request
     *  The page request object.
     *
     * @return string
     *  A view to render.
     */
    public function showUnfollowers($screenName, Request $request) {
        $this->screenName = $screenName;

        $title = 'People who have unfollowed ' . $this->screenName;

        $profileType = 'unfollower';

        $paginatedUnfollowers = $this->paginateProfiles($this->getUnfollowers(), $request);
        return view('reports.profiles', [
          'title' => $title,
          'profiles' => $paginatedUnfollowers,
          'profiletype' => $profileType,
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER,
        ]);
    }

    /**
     * Updates the followers in the database using the results of a request to
     * the remote API.
     *
     * @param string $screenName
     *  The screenname of the twitter user whose followers we want.
     */
    public function updateFollowers($screenName) {
        $this->screenName = $screenName;

        $previousFollowers = $this->getSavedFollowers();
        $currentFollowers = $this->loadFollowersFromRemote();

        // Save the unfollowers.
        $this->saveUnfollowers($previousFollowers, $currentFollowers);

        // Save the current followers.
        $this->saveFollowers($currentFollowers);

        return 'Followers updated.';
    }
    
    /**
     * Update the profiles in the database that have unfollowed.
     * 
     * @param array $previousFollowers
     *  The array of previous followers.
     * @param array $currentFollowers
     *  The array of current followers.
     * 
     * @return array
     *  The profiles that are no longer following.
     */
    protected function saveUnfollowers($previousFollowers, $currentFollowers) {
        $unfollowers = array_diff_key($previousFollowers, $currentFollowers);
        
        $this->saveProfiles($unfollowers, static::PROFILE_TYPE_UNFOLLOWER);
    }

    /**
     * Fetches the saved unfollower profiles from the database.
     *
     * @return array
     *  An array of profile objects of unfollowers.
     */
    public function getUnfollowers() {
        $unFollowers = Profile::where('follower', 0)
          ->where('friend', 0)
          ->orderBy('updated_at', 'desc')
          ->get();

        $unFollowerObjects = array();
        foreach ($unFollowers as $unFollower) {
            // By unserializing the saved profile field we'll get the whole
            // profile with the same object structure as it was when returned
            // by the API.
            $unFollowerObjects[$unFollower->id] = unserialize($unFollower->profile);
            $unFollowerObjects[$unFollower->id]->unfollowed_date = $unFollower->updated_at;
        }
        return $unFollowerObjects;
    }

    /**
     * Save an array of followers to the database with an updated timestamp.
     *
     * @param array $latestFollowers.
     *  The latest followers to save.
     */
    protected function saveFollowers($latestFollowers) {
        $this->saveProfiles($latestFollowers, static::PROFILE_TYPE_FOLLOWER);
        // Setting is a vendor package for storing variables.
        Setting::set('followers_updated', time());
        Setting::save();
    }
    
    /**
     * Fetch the saved followers from the database.
     * 
     * @return array
     *  Profile objects of followers from the database.
     */
    protected function getSavedFollowers() {
        $savedFollowers = Profile::where('follower', 1)->get();
        $followerObjects = array();
        foreach ($savedFollowers as $follower) {
            // By unserializing the saved profile field we'll get the whole
            // profile with the same object structure as it was when returned
            // by the API.
            $followerObjects[$follower->id] = unserialize($follower->profile);
        }
        return $followerObjects;
    }
}
