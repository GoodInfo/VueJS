<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('name', function () {
	return \App\User::first()->name;
});

Route::group(['namespace' => 'Api'], function () {
	Route::resource('api/v1/notes', 'NoteController');
});

