<?php
/**
 * @file
 *  Contains class FollowersController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class FollowersController extends Controller {


  /**
   * Show the followers of the handle.
   */
  public function showFollowers($handle) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
    return view('reports.followers', ['handle' => $handle]);
  }
}