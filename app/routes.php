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

Route::get('admin/login',['uses'=>'LifeLi\controllers\Admin_usersController@login']);
Route::post('admin/login',['as' => 'admin.login','uses'=> 'LifeLi\controllers\Admin_usersController@authenticate_login']);
Route::get('admin/logout',array('as' => 'logout', 'uses'=> 'LifeLi\controllers\Admin_usersController@logout'));

Route::get('admin', function()
{
    return View::make('admin_users.adminPage');
});

Route::group(['before' => 'auth'], function(){
    Route::resource('admin_users', 'LifeLi\controllers\Admin_usersController');
});

Route::group(['before' => 'auth'], function(){
    Route::group(array('prefix' => 'admin'), function(){
        Route::resource('documents', 'LifeLi\controllers\DocumentsController');
    });
});

Route::group(array('prefix' => 'api/v1'),function(){
    Route::post('users/create',array('as' => 'users.create.path', 'uses' => 'LifeLi\controllers\UsersController@store'));
    Route::group(['before' => 'auth.validate_token'], function(){
        Route::resource('users', 'LifeLi\controllers\UsersController');
        Route::group(['prefix' => 'users'], function(){
            Route::get('search/{profile_id}',['uses' => 'LifeLi\controllers\ProfilesController@search_user']);
            Route::resource('profiles', 'LifeLi\controllers\ProfilesController');
            Route::resource('requests', 'LifeLi\controllers\RequestsController');
        });
    });
});


