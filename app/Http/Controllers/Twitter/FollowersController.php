<?php
/**
 * @file
 *  Contains class FollowersController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
//use GuzzleHttp\Client;
use Abraham\TwitterOAuth\TwitterOAuth;

class FollowersController extends Controller {

  private $client;

  /**
   * FollowersController constructor.
   * @param \GuzzleHttp\Client $client
   *
   * This doesn't feel 'decoupled' enough to me. Should we be having to use the
   * GuzzleHttp client here and pass it directly to the constructor? It feels
   * as though there should be another layer of abstraction.
   */
  public function __construct(TwitterOAuth $client) {
    $this->client = $client;
  }

  /**
   * Show the followers of the handle.
   */
  public function showFollowers($handle) {
    $followers = $this->client->get('friends/ids', ['user_id' => $handle]);
    print_r($followers);
    //return view('reports.followers', ['handle' => $handle]);
  }
}