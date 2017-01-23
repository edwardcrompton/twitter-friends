<?php
/**
 * @file
 *  Contains FollowersController class.
 */

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;

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
}
