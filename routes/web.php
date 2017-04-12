<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Pagination\LengthAwarePaginator;

Route::get('/', function () {
    return view('index');
});

Route::get('/{screenName}/friends/{sorting}', 'Twitter\FriendsController@showFriends');

// Does it matter what's inside the {} in the place holders?
Route::get('/{screenName}/followers/{sorting}', 'Twitter\FollowersController@showFollowers');

//
Route::get('/{screenName}/unfollowers', 'Twitter\FollowersController@showUnfollowers');

// @todo: I think this should be moved somewhere special.
App::bind('LengthAwarePaginator', function ($app, $params) {
  return new LengthAwarePaginator($params[0], $params[1], $params[2]);
});