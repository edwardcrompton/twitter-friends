<?php
/**
 * @file
 *  Contains FriendsController class.
 */

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;

/**
 * Description of FriendsController
 *
 * @author edward
 */
class FriendsController extends ProfileBaseController {
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
    public function showFriendsByLastUpdate($screenName, Request $request)
    {
        $friends = $this->getFriends($screenName);

        // Sort the friend objects by the date of the last post.
        usort($friends, function ($a, $b) {
            // If $a->status isn't set, assume $b->status is bigger.
            if (!isset($a->status)) {
                return -1;
            }
            // If $b->status isn't set, assume $a->status is bigger.
            if (!isset($b->status)) {
                return 1;
            }
            return strtotime($a->status->created_at) > strtotime($b->status->created_at) ? 1 : -1;
        });

        $paginatedFriends = $this->paginateProfiles($friends, $request);
        
        return view('reports.friends', [
          'handle' => $screenName,
          'friends' => $paginatedFriends,
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER
        ]);
    }
    
    /**
     * Display a list of friends ordered by their ratio of friends to followers.
     *
     * @param $screenName
     *  The screen name of the twitter account we're showing friends for.
     * @param Request $request
     *  The page request object.
     *
     * @return string
     *  A view to render.
     */
    public function showFriendsByCelebStatus($screenName, Request $request) {
        $friends = $this->getFriends($screenName);
        
        // Sort the friends objects by the ratio of followers to followed.
        usort($friends, function ($a, $b) {
            if ($a->friends_count == 0) {
                return -1;
            }
            if ($b->friends_count == 0) {
                return 1;
            }
            return ($a->followers_count / $a->friends_count > $b->followers_count / $b->friends_count) ? -1 : 1;
        });
        
        $paginatedFriends = $this->paginateProfiles($friends, $request);
           
        return view('reports.celebs', [
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
          'linkToTwitter' => self::EXTERNAL_LINK_TO_TWITTER
        ]);
    }
    
    /**
     * Display a list of celebs who follow celebs that follow you.
     * 
     * Currently times out.
     */
    public function showCelebsFollowingCelebs($screenName) {
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
        
        // For now, limit to the first ten celebs that follow you.
        $followers = array_slice($followers, 0, 2);
        
        foreach ($followers as $follower) {
            $their_followers = $this->getFollowers($follower->screen_name);
        } 
        
        // Sort the friends objects by the ratio of followers to followed.
        usort($their_followers, function ($a, $b) {
            if ($a->friends_count == 0) {
                return -1;
            }
            if ($b->friends_count == 0) {
                return 1;
            }
            return ($a->followers_count / $a->friends_count > $b->followers_count / $b->friends_count) ? -1 : 1;
        });
        
        // For each of the top ten celebs who follow you, we'll only look at 
        // the top ten of their followers.
        $their_followers = array_slice($their_followers, 0, 2);
        
        $paginatedCelebs = $this->paginateProfiles($their_followers, $request);
        
    }

}
