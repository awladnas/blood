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

Route::get('admin/login',['before' => 'auth.logout','as' => 'auth.login', 'uses'=>'LifeLi\controllers\Admin_usersController@login']);
Route::post('admin/login',['as' => 'admin.login','uses'=> 'LifeLi\controllers\Admin_usersController@authenticate_login']);
Route::get('admin/logout',array('as' => 'logout', 'uses'=> 'LifeLi\controllers\Admin_usersController@logout'));

Route::group(['before' => 'auth', 'prefix' => 'admin'], function(){
    Route::resource('admin_users', 'LifeLi\controllers\Admin_usersController');
});


Route::group(array('prefix' => 'admin', 'before' => 'auth'), function(){
    Route::resource('documents', 'LifeLi\controllers\DocumentsController');
    Route::get('db-doc', 'LifeLi\controllers\DocumentsController@db' );
});


Route::group(array('prefix' => 'api/v1'),function(){
    Route::post('users/create',array('as' => 'users.create.path', 'uses' => 'LifeLi\controllers\UsersController@store'));
    Route::group(['before' => 'auth.validate_token'], function(){
        Route::group(['prefix' => 'users'], function(){
            Route::get('{user_id}/requests', 'LifeLi\controllers\RequestsController@user_requests');
            Route::post('{user_id}/requests', 'LifeLi\controllers\RequestsController@store');

            Route::get('{request_id}/filter_request', 'LifeLi\controllers\RequestsController@filter_request');
            Route::post('requests/{request_id}/accept', 'LifeLi\controllers\RequestsController@accept_request');
            Route::get('requests/{request_id}/ignore', 'LifeLi\controllers\RequestsController@ignore_request');
            Route::get('requests/{request_id}/block', 'LifeLi\controllers\RequestsController@block_user');
            Route::post('requests/{request_id}/reject', 'LifeLi\controllers\RequestsController@decline_request');
            Route::get('requests/{request_id}/update_status', 'LifeLi\controllers\RequestsController@update_status');
            Route::get('{user_id}/requests/sent', 'LifeLi\controllers\RequestsController@users_sent_requests');
            Route::get('{user_id}/requests/receive', 'LifeLi\controllers\RequestsController@users_receive_requests');
            Route::get('{user_id}/requests/sent_list', 'LifeLi\controllers\RequestsController@all_requested_users');
            Route::resource('requests', 'LifeLi\controllers\RequestsController');

            Route::get('{user_id}/offers', 'LifeLi\controllers\OffersController@user_offers');
            Route::post('{user_id}/offers', 'LifeLi\controllers\OffersController@store');
            Route::get('{user_id}/offers/receive', 'LifeLi\controllers\OffersController@users_receive_offers');
            Route::get('{user_id}/offers/sent', 'LifeLi\controllers\OffersController@users_sent_offers');
            Route::get('{user_id}/offers/sent_list', 'LifeLi\controllers\OffersController@all_offered_users');
            Route::post('offers/{offer_id}/accept', 'LifeLi\controllers\OffersController@accept_offer');
            Route::post('offers/{offer_id}/reject', 'LifeLi\controllers\OffersController@decline_offer');
            Route::get('offers/{offer_id}/block', 'LifeLi\controllers\OffersController@block_user');
            Route::get('offers/{offer_id}/ignore', 'LifeLi\controllers\OffersController@ignore_offer');
            Route::get('offers/{offer_id}/update_status', 'LifeLi\controllers\OffersController@update_status');

            Route::get('{offer_id}/filter_offer', 'LifeLi\controllers\OffersController@filter_offer');
            Route::resource('offers', 'LifeLi\controllers\OffersController');

            Route::get('{user_id}/notifications', 'LifeLi\controllers\NotificationController@show');
        });

        Route::post('users/{id}/change_password', 'LifeLi\controllers\UsersController@change_password');
        Route::get('users/{id}/search_user', 'LifeLi\controllers\UsersController@search_user');
        Route::get('users/{id}/update_token', 'LifeLi\controllers\UsersController@update_token');
        Route::get('users/{id}/check_profile', 'LifeLi\controllers\UsersController@profile_complete');
        Route::get('users/{id}/out_of_request', 'LifeLi\controllers\UsersController@out_of_request');
        Route::get('users/{id}/confirm/{confirmation_code}', 'LifeLi\controllers\UsersController@confirm');
        Route::post('users/login', 'LifeLi\controllers\UsersController@user_login');
        Route::resource('users', 'LifeLi\controllers\UsersController');
    });
});
App::missing(function($exception)
{

    $response = Response::json([
            'error' => true,
            'message' => 'invalid url',
            'code' => 404],
        404
    );
    $response->header('Content-Type', 'application/json');
    return $response;
});


