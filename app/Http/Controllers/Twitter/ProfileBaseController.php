<?php
/**
 * @file
 *  Contains class ProfileBaseController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use App\Profile;
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
    // The maximum number of friend ids that can be used in a request for friends
    // from the API.
    const API_PAGE_MAXIMUM = 100;
    // The number of items to display on each paginated page.
    const ITEMS_PER_PAGE = 10;
    // The base URL of the twitter site.
    const EXTERNAL_LINK_TO_TWITTER = 'https://www.twitter.com';
    
    // The follower profile type.
    const PROFILE_TYPE_FOLLOWER = 1;
    // The friend profile type.
    const PROFILE_TYPE_FRIEND = 2;

    // The API client object.
    private $client;
            
    // The screen name of the user we are getting associated profiles for.
    public $screenName = '';        

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
     * @return array
     *  An array of friends objects.
     */
    public function getFriends()
    {
        // See if we've cached the friends, if not, load them from the API.
        $cacheKey = self::FRIENDS_CACHE_KEY . '_' . $this->screenName;
        if (!Cache::has($cacheKey)) {
            $friendObjects = $this->loadFriendsFromRemote();
            Cache::add($cacheKey, $friendObjects, self::CACHE_EXPIRE);
            return $friendObjects;
        }

        return Cache::get($cacheKey);
    }

    /**
     * Get the friends of a twitter account.
     *
     * @return array
     *  An array of friend objects from the API.
     */
    protected function loadFriendsFromRemote()
    {
        $friends = $this->client->get('friends/ids', ['screen_name' => $this->screenName]);
        return $this->profileIdsToObjects($friends);
    }
    
    /**
     * Load the followers of a twitter account.
     *
     * @return array
     *  An array of follower objects from the API.
     */
    protected function loadFollowersFromRemote()
    {
        try {
            $followers = $this->client->get('followers/ids', ['screen_name' => $this->screenName]);
            return $this->profileIdsToObjects($followers);
        } catch (Exception $ex) {
            var_dump($ex);
        }
        
    }
    
    /**
     * Save a set of profile objects to the database.
     * 
     * @param type $profileObjects
     */
    protected function saveProfiles($profileObjects, $type) 
    {
        // We have to allow all fields to be fillable whilst we're creating
        // profiles en-masse.
        Profile::unguard();
        foreach ($profileObjects as $profileObject) {
            $profile = Profile::firstOrNew(['id' => $profileObject->id]);
            $profile->handle = $profileObject->screen_name;
            $profile->id = $profileObject->id;
            $profile->friend = $type == static::PROFILE_TYPE_FOLLOWER;
            $profile->follower = $type == static::PROFILE_TYPE_FRIEND;
            $profile->profile = serialize($profileObject);
            $profile->save();
        }
        Profile::reguard();
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

        // Loop through the profile array and create a new array that is keyed
        // by the profile ids.
        foreach ($profile_objects as $profile_object) {
            $keyedProfileObjects[$profile_object->id] = $profile_object;
        }
        
        return $keyedProfileObjects;
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
    
    /**
     * Sort an array of profiles by the last time they were updated.
     * 
     * @param array $profiles
     * 
     * @return array
     *  The array of sorted profiles.
     */
    protected function sortByLastUpdate($profiles) {
        // Sort the friend objects by the date of the last post.
        usort($profiles, function ($a, $b) {
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
        return $profiles;
    }
    
    /**
     * Sort an array of profiles by the ratio of followers to friends.
     * 
     * @param array $profiles
     * 
     * @return array
     *  The array of sorted profiles.
     */
    protected function sortByFollowersFriendsRatio($profiles) {
        // Sort the friends objects by the ratio of followers to followed.
        usort($profiles, function ($a, $b) {
            if ($a->friends_count == 0) {
                return -1;
            }
            if ($b->friends_count == 0) {
                return 1;
            }
            return ($a->followers_count / $a->friends_count > $b->followers_count / $b->friends_count) ? -1 : 1;
        });
        return $profiles;
    }
    
    /**
     * Fetches id properties from an array of profile objects.
     */
    private function getProfileIds($profiles) {
        $ids = array();
        foreach ($profiles as $profile) {
            $ids[] = $profile->id;
        }
        return $ids;
    }
}
