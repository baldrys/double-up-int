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

Route::get('v0/users/profile/{userProfile}', 'V0\UserProfilesController@showProfile');
Route::get('v0/user/{user}/profiles', 'V0\UserProfilesController@showProfiles');
Route::get('v0/users/profiles', 'V0\UserProfilesController@showProfilesPerPage');
Route::patch('v0/users/profile/{userProfile}', 'V0\UserProfilesController@updateProfile');
Route::delete('v0/users/profile/{userProfile}','V0\UserProfilesController@deleteProfile');


Route::get('v0/db/users/profile/{id}', 'V0\UserProfilesController@showProfileDB');
Route::get('v0/db/user/{userId}/profiles', 'V0\UserProfilesController@showProfilesDB');
Route::get('v0/db/users/profiles', 'V0\UserProfilesController@showProfilesPerPageDB');
Route::patch('v0/db/users/profile/{id}', 'V0\UserProfilesController@updateProfileDB');
Route::delete('v0/db/users/profile/{id}','V0\UserProfilesController@deleteProfileDB');