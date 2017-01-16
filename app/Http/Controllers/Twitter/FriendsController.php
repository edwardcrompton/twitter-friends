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
        switch ($sorting) {
            case self::SORTING_LAST_UPDATE:
                $friends = $this->sortByLastUpdate($this->getFriends($screenName));
                break;
            case self::SORTING_CELEB_STATUS:
                $friends = $this->sortByFollowersFriendsRatio($this->getFriends($screenName));
                break;
            default:
                // No sorting.
        }
        
        $paginatedFriends = $this->paginateProfiles($friends, $request);
        
        return view('reports.friends', [
          'handle' => $screenName,
          'friends' => $paginatedFriends,
          'profiletype' => 'friend',  
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER
        ]);
    }
}
