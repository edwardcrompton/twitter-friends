<?php
/**
 * @file
 *  Contains class FollowersController
 */

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;


class FollowersController extends Controller {
  /**
   * Show the followers of the handle.
   */
  public function showFollowers($handle) {
    return view('reports.followers', ['handle' => $handle]);
  }
}