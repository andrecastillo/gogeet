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

Route::get('/home', 'HomeController@index')->name('home.index');
Route::get('/tasks', 'TaskController@home')->name('tasks.home');
Route::get('/goals', 'GoalController@index')->name('goals.index');

Route::resource('/task', 'TaskController');
