<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
    return View::make('hello');
});

Route::group(array('prefix' => 'api/v1'),function(){
    Route::post('users/create',array('as' => 'users.create.path', 'uses' => 'LifeLi\controllers\UsersController@store'));
    Route::group(['before' => 'auth.validate_token'], function(){
        Route::resource('users', 'LifeLi\controllers\UsersController');
        Route::resource('users.profiles', 'LifeLi\controllers\ProfilesController');
    });
});
