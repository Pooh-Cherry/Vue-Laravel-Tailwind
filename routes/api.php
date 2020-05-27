<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/play_next_week_matches', 'MatchController@create')->name('play_next_week_matches');
Route::post('/play_all_matches', 'MatchController@batchCreate')->name('play_all_matches');
Route::post('/reset_matches', 'MatchController@batchDelete')->name('reset_matches');
