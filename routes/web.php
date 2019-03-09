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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('task1/hello_world', 'Task1\Task1Controller@helloWorld');
Route::get('task1/uuid', 'Task1\Task1Controller@uuid');
Route::get('task1/data_from_config ', 'Task1\Task1Controller@data_from_config');

Route::get('/', function () {
        return view('index');
    });
Route::get('/groups/{user}', 'GroupsController@index');
Route::get('/profiles', 'ProfilesController@index');