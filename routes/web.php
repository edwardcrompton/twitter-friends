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

Route::get('/{screenName}/{friends}', 'Twitter\FriendsController@showFriends');

Route::get('/{screenName}/{celebfriends}', 'Twitter\FriendsController@showFriends');

Route::get('{screenName}/celebfollowers', 'Twitter\FriendsController@showFollowersByCelebStatus');

Route::get('/{screenName}/celebsuggestions', 'Twitter\FriendsController@showCelebsFollowingCelebs');

// I think this should be moved somewhere special.        
App::bind('LengthAwarePaginator', function ($app, $params) {
  return new LengthAwarePaginator($params[0], $params[1], $params[2]);
});