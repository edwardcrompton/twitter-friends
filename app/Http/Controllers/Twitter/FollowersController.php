<?php
/**
 * @file
 *  Contains FollowersController class.
 */

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use Setting;

/**
 * FollowersController class for handling actions to do with followers.
 */
class FollowersController extends ProfileBaseController {
    
    const SORTING_CELEB_STATUS = 'celebs';
    
    /**
     * Display a list of your followers by celeb status.
     *
     * @param $screenName
     *  The screen name of the twitter account we're showing friends for.
     * @param Request $request
     *  The page request object.
     *
     * @return string
     *  A view to render.
     */
    public function showFollowers($screenName, $sorting, Request $request) {
        switch ($sorting) {
            case self::SORTING_CELEB_STATUS:
                $followers = $this->sortByFollowersFriendsRatio($this->getFollowers($screenName));
                $profileType = 'celeb';
                $title = 'Followers of ' . $screenName . ': Celebrity status';
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
     * Get the difference between two arrays of profiles.
     */
    protected function getUnfollowers($previousFollowers, $currentFollowers) {
        $unfollowers = array_diff_key($previousFollowers, $currentFollowers);
        // @todo: Save the unfollowers to a separate database table or set a flag.
        // May need to add a new db column.
    }
    
    /**
     * Get the array of followers of a handle, either from the API or from cache.
     *
     * @param $screenName
     *  Twitter handle of the person whose followers we want.
     *
     * @return array
     *  An array of follower objects.
     */
    public function getFollowers($screenName)
    {
        $followersSavedTimestamp = Setting::get('followers_updated', 0);
        
        $savedFollowers = $this->getSavedFollowers();
        
        // If the maximum cache time has elapsed since followers were last saved
        // to the database, load them again and save them to the database, 
        // updating the timestamp as we do so.
        if ($followersSavedTimestamp && time() - $followersSavedTimestamp > self::CACHE_EXPIRE * 60) {
            $latestFollowers = $this->loadFollowersFromRemote($screenName);
            
            $unfollowers = $this->getUnfollowers($savedFollowers, $latestFollowers);
            
            $this->saveProfiles($latestFollowers, static::PROFILE_TYPE_FOLLOWER);
            // Setting is a vendor package for storing variables.
            Setting::set('followers_updated', time());
            Setting::save();
            return $latestFollowers;
        }
        
        return $savedFollowers;
    }
    
    /**
     * Fetch the saved followers from the database.
     * 
     * @return type
     */
    protected function getSavedFollowers() {
        $savedFollowers = \App\Profile::all();
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
