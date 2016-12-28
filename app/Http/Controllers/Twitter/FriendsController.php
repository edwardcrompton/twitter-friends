<?php
/**
 * @file
 *  Contains class FriendsController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Cache;

class FriendsController extends Controller {

  const CACHE_EXPIRE = 360;
  const FRIENDS_CACHE_KEY = 'friends';

  private $client;

  /**
   * FollowersController constructor.
   * @param TwitterOAuth $client
   *
   * This doesn't feel 'decoupled' enough to me. Should we be having to use the
   * TwitterOAuth client here and pass it directly to the constructor? It feels
   * as though there should be another layer of abstraction.
   */
  public function __construct(TwitterOAuth $client) {
    $this->client = $client;
  }

  /**
   * Get the followers of the handle.
   */
  private function loadFriends($handle) {
    // Make this a class constant.
    $page_max = 100;

    $friends = $this->client->get('friends/ids', ['user_id' => $handle]);

    $paged_ids = array_chunk($friends->ids, $page_max);
    $friend_objects = array();

    // Fetch the friend objects in pages of 100, appending them to a single array.
    foreach ($paged_ids as $page) {
      $imploded_ids = implode(',', $page);
      // It's recommended we post the user ids since there are a lot of them.
      $paged_friends = $this->client->get('users/lookup', ['user_id' => $imploded_ids]);
      $friend_objects = array_merge($friend_objects, $paged_friends);
    }

    return $friend_objects;
  }

  /**
   * Display a list of friends ordered by the last time they were updated.
   */
  public function showFriendsByLastUpdate($handle) {
    $friends = $this->getFriends($handle);

    // Sort the friend objects by the date of the last post.
    usort($friends, function ($a, $b) {
      return strtotime($a->status->created_at) > strtotime($b->status->created_at) ? 1 : -1;
    });

    $paginated_friends = new LengthAwarePaginator($friends, count($friends), 10);

    return view('reports.friends', ['handle' => $handle, 'friends' => $paginated_friends]);
  }

  /**
   * Get the array of friends from a handle, either from the API or from cache.
   *
   * @param $handle
   *  Twitter handle of the person who's friends we want.
   *
   * @return array
   *  An array of friends objects.
   */
  public function getFriends($handle) {
    // See if we've cached the friends, if not, load them from the API.
    $friend_objects = Cache::has('friends_' . $handle);

    if (!$friend_objects) {
      $friend_objects = $this->loadFriends($handle);
      Cache::add('friends_' . $handle, $friend_objects, self::CACHE_EXPIRE);
      return $friend_objects;
    }
    return Cache::get('friends_' . $handle);
  }
}
