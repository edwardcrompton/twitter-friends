<?php
/**
 * @file
 *  Contains class FriendsController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    // The number of minutes to cache friends fetched from the API.
    const CACHE_EXPIRE = 360;
    // The prefix for the cache keys used to store friends.
    const FRIENDS_CACHE_KEY = 'friends';
    // The prefix for the cache keys used to store followers.
    const FOLLOWERS_CACHE_KEY = 'followers';
    // The maximum number of friend ids that can be used in a request for friends
    // from the API.
    const API_PAGE_MAXIMUM = 100;
    // The number of items to display on each paginated page.
    const ITEMS_PER_PAGE = 10;
    // The base URL of the twitter site.
    const EXTERNAL_LINK_TO_TWITTER = 'https://www.twitter.com';

    // The API client object.
    private $client;

    /**
     * FollowersController constructor.
     *
     * @param TwitterOAuth $client
     */
    public function __construct(TwitterOAuth $client)
    {
        $this->client = $client;
    }

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
        $followers = $this->getFriends($screenName);
        
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
     * Get the array of friends from a handle, either from the API or from cache.
     *
     * @param $screenName
     *  Twitter handle of the person who's friends we want.
     *
     * @return array
     *  An array of friends objects.
     */
    public function getFriends($screenName)
    {
        // See if we've cached the friends, if not, load them from the API.
        $cacheKey = self::FRIENDS_CACHE_KEY . '_' . $screenName;
        if (!Cache::has($cacheKey)) {
            $friendObjects = $this->loadFriends($screenName);
            Cache::add($cacheKey, $friendObjects, self::CACHE_EXPIRE);
            return $friendObjects;
        }

        return Cache::get($cacheKey);
    }

    /**
     * Get the followers of a twitter account.
     *
     * @param $screenName string
     *  The screen name of the user whose friends to fetch.
     *
     * @return array
     *  An array of friend objects from the API.
     */
    private function loadFriends($screenName)
    {
        $friends = $this->client->get('friends/ids', ['screen_name' => $screenName]);

        $paged_ids = array_chunk($friends->ids, self::API_PAGE_MAXIMUM);
        $friend_objects = array();

        // Fetch the friend objects in pages, appending them to a single array.
        foreach ($paged_ids as $page) {
            $imploded_ids = implode(',', $page);
            // It's recommended we post the user ids since there are a lot of them.
            $paged_friends = $this->client->post('users/lookup', ['user_id' => $imploded_ids]);
            $friend_objects = array_merge($friend_objects, $paged_friends);
        }

        return $friend_objects;
    }
    
    /**
     * Get the array of followers from a handle, either from the API or from cache.
     *
     * @param $screenName
     *  Twitter handle of the person who's followers we want.
     *
     * @return array
     *  An array of follower objects.
     */
    public function getFollowers($screenName)
    {
        // See if we've cached the friends, if not, load them from the API.
        $cacheKey = FOLLOWERS_CACHE_KEY . '_' . $screenName;
        if (!Cache::has($cacheKey)) {
            $followerObjects = $this->loadFollowers($screenName);
            Cache::add($cacheKey, $followerObjects, self::CACHE_EXPIRE);
            return $followerObjects;
        }

        return Cache::get($cacheKey);
    }
    
    /**
     * Returns paginated profiles from an array of profiles.
     * 
     * @param array $friends
     *  A list of friends.
     * @param Request $request
     *  The page request.
     * 
     * @return LengthAwarePaginator
     */
    private function paginateProfiles($friends, $request) 
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $collection = Collection::make($friends);

        // Slice the collection to get the items to display in current page.
        $currentPageFriends = $collection->slice(($currentPage - 1) * self::ITEMS_PER_PAGE, self::ITEMS_PER_PAGE)->all();

        //Create our paginator and pass it to the view.
        $paginatedFriends = app()->make('LengthAwarePaginator', [$currentPageFriends, count($collection), self::ITEMS_PER_PAGE]);
        $paginatedFriends->setPath('/' . $request->path());
        
        return $paginatedFriends;
    }
}
