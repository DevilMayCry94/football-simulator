<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', 'LoginController@showLoginForm')->name('auth.login');
Route::post('/login', 'LoginController@login');
Route::group(['middleware' => 'auth:web'], function() {
    Route::resource('leagues', 'LeagueController');
    Route::get('leagues/{league}/week/{weekNumber}', 'StandingController@getByWeek')->name('leagues.get-by-week');
    Route::get('leagues/{league}/next-week', 'LeagueController@nextWeek')->name('leagues.next-week');
    Route::get('leagues/{league}/play-all', 'StandingController@playAll')->name('leagues.play-all');
    Route::put('matches/{match}', 'MatchController@update')->name('matches.update');
});
