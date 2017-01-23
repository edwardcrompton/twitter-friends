<?php
/**
 * @file
 *  Contains FriendsController class.
 */

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;

/**
 * FriendsController class for handling actions to do with friends.
 */
class FriendsController extends ProfileBaseController {
    
    const SORTING_LAST_UPDATE = 'lastupdated';
    const SORTING_CELEB_STATUS = 'celebs';
    
    /**
     * Display a list of friends ordered by the last time they were updated.
     *
     * @param $screenName
     *  The screen name of the twitter account we're showing friends for.
     * @param Request $request
     *  The page request object.
     *
     * @return string
     *  A view to render.
     */
    public function showFriends($screenName, $sorting, Request $request)
    {
        $friends = $this->getFriends($screenName);
        
        switch ($sorting) {
            case self::SORTING_LAST_UPDATE:
                $friends = $this->sortByLastUpdate($friends);
                $profileType = 'friend';
                $title = 'Friends of ' . $screenName . ': Low activity';
                break;
            case self::SORTING_CELEB_STATUS:
                $friends = $this->sortByFollowersFriendsRatio($friends);
                $profileType = 'celeb';
                $title = 'Friends of ' . $screenName . ': Celebrity status';
                break;
            default:
                // No sorting.
        }
        
        $paginatedFriends = $this->paginateProfiles($friends, $request);
        
        return view('reports.profiles', [
          'title' => $title,
          'profiles' => $paginatedFriends,
          'profiletype' => $profileType,  
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER
        ]);
    }
}
