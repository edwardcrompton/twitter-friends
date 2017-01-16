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
    
    // These should perhaps be global so we can use them in the route too?
    const SORTING_LAST_UPDATE = 'friends';
    const SORTING_CELEB_STATUS = 'celebfriends';
    
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
                break;
            case self::SORTING_CELEB_STATUS:
                $friends = $this->sortByFollowersFriendsRatio($friends);
                break;
            default:
                // No sorting.
        }
        
        $paginatedFriends = $this->paginateProfiles($friends, $request);
        
        return view('reports.friends', [
          'handle' => $screenName,
          'friends' => $paginatedFriends,
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER
        ]);
    }
    
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
    public function showFollowersByCelebStatus($screenName, Request $request) {
        $followers = $this->getFollowers($screenName);
        
        // Sort the friends objects by the ratio of followers to followed.
        usort($followers, function ($a, $b) {
            if ($a->friends_count == 0) {
                return -1;
            }
            if ($b->friends_count == 0) {
                return 1;
            }
            return ($a->followers_count / $a->friends_count > $b->followers_count / $b->friends_count) ? -1 : 1;
        });
        
        $paginatedFollowers = $this->paginateProfiles($followers, $request);
           
        return view('reports.celebs', [
          'handle' => $screenName,
          'friends' => $paginatedFollowers,
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER,     
        ]);
    }
}
