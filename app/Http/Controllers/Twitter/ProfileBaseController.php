<?php
/**
 * @file
 *  Contains class ProfileBaseController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

/**
 * Base controller for Twitter user profiles.
 */
abstract class ProfileBaseController extends Controller
{
    // The number of minutes to cache profiles fetched from the API.
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
     * Get the friends of a twitter account.
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
        return $this->profileIdsToObjects($friends);
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
        // See if we've cached the friends, if not, load them from the API.
        $cacheKey = self::FOLLOWERS_CACHE_KEY . '_' . $screenName;
        if (!Cache::has($cacheKey)) {
            $followerObjects = $this->loadFollowers($screenName);
            Cache::add($cacheKey, $followerObjects, self::CACHE_EXPIRE);
            return $followerObjects;
        }

        return Cache::get($cacheKey);
    }
    
    /**
     * Get the followers of a twitter account.
     *
     * @param $screenName string
     *  The screen name of the user whose followers to fetch.
     *
     * @return array
     *  An array of follower objects from the API.
     */
    private function loadFollowers($screenName)
    {
        $followers = $this->client->get('followers/ids', ['screen_name' => $screenName]);
        return $this->profileIdsToObjects($followers);
    }
    
    /**
     * For an array of profile ids fetch the full profile objects.
     * 
     * @param type $profiles
     * 
     * @return type
     */
    private function profileIdsToObjects($profiles) 
    {
        $paged_ids = array_chunk($profiles->ids, self::API_PAGE_MAXIMUM);
        $profile_objects = array();

        // Fetch the follower objects in pages, appending them to a single array.
        foreach ($paged_ids as $page) {
            $imploded_ids = implode(',', $page);
            // It's recommended we post the user ids since there are a lot of them.
            $paged_followers = $this->client->post('users/lookup', ['user_id' => $imploded_ids]);
            $profile_objects = array_merge($profile_objects, $paged_followers);
        }

        return $profile_objects;
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
    protected function paginateProfiles($friends, $request) 
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
