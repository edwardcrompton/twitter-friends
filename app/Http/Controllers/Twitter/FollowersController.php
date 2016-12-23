<?php
/**
 * @file
 *  Contains class FollowersController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class FollowersController extends Controller {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }

  /**
   * Show the followers of the handle.
   */
  public function showFollowers($handle) {
    $this->client->request('GET', 'test');
    print_r($this->client);
    //return view('reports.followers', ['handle' => $handle]);
  }
}