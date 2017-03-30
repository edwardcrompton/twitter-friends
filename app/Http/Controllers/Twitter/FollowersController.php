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
    
    const SORTING_CELEB_STATUS = 'celebs';
    
    /**
     * Display a list of your followers by celeb status.
     *
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
                $followers = $this->sortByFollowersFriendsRatio($this->getFollowers());
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
        return '';
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
     * Get the array of followers of a handle, either from the API or from cache.
     *
     * @return array
     *  An array of follower objects.
     */
    public function getFollowers()
    {
        $savedFollowers = $this->getSavedFollowers();
        
        if ($latestFollowers = $this->getUpdatedFollowers()) {
            $this->saveUnfollowers($savedFollowers, $latestFollowers);
            return $latestFollowers;
        }
        
        return $savedFollowers;
    }
    
    /**
     * If the maximum cache time has elapsed since followers were last saved
     * to the database, load them again and save them to the database, updating 
     * the timestamp as we do so.
     * 
     * @return array
     *  The latest followers, or an empty array if there is no update.
     */
    protected function getUpdatedFollowers() {
        $followersSavedTimestamp = Setting::get('followers_updated', 0);
        
        if ($followersSavedTimestamp && time() - $followersSavedTimestamp > $this->cacheExpire * 60) {
            $latestFollowers = $this->loadFollowersFromRemote();
            $this->saveProfiles($latestFollowers, static::PROFILE_TYPE_FOLLOWER);
            // Setting is a vendor package for storing variables.
            Setting::set('followers_updated', time());
            Setting::save();
            return $latestFollowers;
        }
        
        return array();
    }
    
    /**
     * Fetch the saved followers from the database.
     * 
     * @return type
     */
    protected function getSavedFollowers() {
        $savedFollowers = Profile::where('follower', 1)->get();
        $followerObjects = array();
        foreach ($savedFollowers as $follower) {
            $profile = $follower->profile;
            // By unserializing the saved profile field we'll get the whole
            // profile with the same object structure as it was when returned
            // by the API.
            $followerObjects[$follower->id] = unserialize($follower->profile);
        }
        return $followerObjects;
    }
}
