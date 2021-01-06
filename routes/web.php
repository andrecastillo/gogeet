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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// principles
Route::get('/principles', 'PrincipleController@home')->name('principles.home');
Route::resource('/principle', 'PrincipleController');

// missions
Route::get('/missions', 'MissionController@home')->name('missions.home');
Route::resource('/mission', 'MissionController');

// goals
Route::get('/goals', 'GoalController@home')->name('goals.home');
Route::resource('/goal', 'GoalController');

// tasks
Route::get('/tasks', 'TaskController@home')->name('tasks.index');
Route::resource('/task', 'TaskController');
