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

// Task2
Route::get('v0/users/profile/{userProfile}', 'V0\UserProfilesController@showProfile');
Route::get('v0/user/{user}/profiles', 'V0\UserProfilesController@showProfiles');
Route::get('v0/users/profiles', 'V0\UserProfilesController@showProfilesPerPage');
Route::patch('v0/users/profile/{userProfile}', 'V0\UserProfilesController@updateProfile');
Route::delete('v0/users/profile/{userProfile}', 'V0\UserProfilesController@deleteProfile');

Route::get('v0/db/users/profile/{id}', 'V0\UserProfilesController@showProfileDB');
Route::get('v0/db/user/{userId}/profiles', 'V0\UserProfilesController@showProfilesDB');
Route::get('v0/db/users/profiles', 'V0\UserProfilesController@showProfilesPerPageDB');
Route::patch('v0/db/users/profile/{id}', 'V0\UserProfilesController@updateProfileDB');
Route::delete('v0/db/users/profile/{id}', 'V0\UserProfilesController@deleteProfileDB');

// Task3
Route::post('v0/users/group', 'V0\UserGroupsController@addGroup');
Route::get('v0/user/{user}/groups', 'V0\UserGroupsController@showGroups');
Route::delete('v0/users/groups/{group}', 'V0\UserGroupsController@deleteGroup');
Route::post('v0/user/{user}/group/{group}', 'V0\UserGroupsController@addUserToGroup');
Route::delete('v0/user/{user}/group/{group}', 'V0\UserGroupsController@deleteUserFromGroup');
