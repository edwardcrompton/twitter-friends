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
  public function getFriends($handle) {
    // Make this a class constant.
    $page_max = 100;

    $friends = $this->client->get('friends/ids', ['user_id' => $handle]);

    $paged_ids = array_chunk($friends->ids, $page_max);
    $friend_objects = array();

    // Fetch the friend objects in pages of 100, appending them to a single array.
    foreach ($paged_ids as $page) {
      $imploded_ids = implode(',', $page);
      $paged_friends = $this->client->get('users/lookup', ['user_id' => $imploded_ids]);
      $friend_objects = array_merge($friend_objects, $paged_friends);
    }

    return $friend_objects;
  }

  /**
   *
   */
  public function showFriendsByLastUpdate($handle, $order) {
    // See if we've cached the followers, if not, get them.
    $friend_objects = Cache::has('friend_objects');
    if (!$friend_objects) {
      $friend_objects = $this->getFriends($handle);
      Cache::add('friend_objects', $friend_objects, self::CACHE_EXPIRE);
    }
    else {
      $friend_objects = Cache::get('friend_objects');
    }

    // Order the friends objects by the date of the last tweet.
    $updates = array();
    foreach ($friend_objects as $index => $friend) {
      $updates[$friend->screen_name] = strtotime($friend->status->created_at);
    }

    // Sort by date.
    switch ($order) {
      case 'asc':
        asort($updates);
        break;
      case 'desc':
        arsort($updates);
        break;
    }

    $friends = new LengthAwarePaginator($updates, count($updates), 10);

    return view('reports.friends', ['handle' => $handle, 'friends' => $friends]);
  }

}