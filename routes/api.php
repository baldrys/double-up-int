<?php

use Illuminate\Http\Request;

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

Route::get('v0/users/profile/{id}', 'V0\UserProfilesController@show_profile');
Route::get('v0/user/{userId}/profiles', 'V0\UserProfilesController@show_profiles');
Route::get('v0/users/profiles', 'V0\UserProfilesController@show_profiles_5_per_page');
Route::patch('v0/users/profile/{id}', 'V0\UserProfilesController@update_profile');
Route::delete('v0/users/profile/{id}','V0\UserProfilesController@delete_profile');