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

Route::get('/{handle}/stale', 'Twitter\FollowersController@showStale');

Route::get('/{handle}/friends', 'Twitter\FriendsController@showFriendsByLastUpdate');

App::bind('LengthAwarePaginator', function ($app, $params) {
  return new LengthAwarePaginator($params[0], $params[1], $params[2]);
});