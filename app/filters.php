<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
use LifeLi\models\Users\User;
App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('admin/login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic('email');
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/admin');
});

//Route::filter('auth.admin', function() {
//    // if not logged in redirect to the login page
//    if (Auth::guest()) return Redirect::guest('admin/login');
//});
Route::filter('auth.logout', function() {
    // if already logged in don't show login page again
//    if (Auth::check()) return Redirect::to('/dashboard');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


Route::filter('auth.validate_token', function($route, $request)
{
    $token = $request->header('X-Auth-Token');

    $user =  User::where('api_token', $token)->first();
    //check if token expired
    if($user->valid_untill < date('Y-m-d H:i:s') ) {
        $response = Response::json([
                'error' => true,
                'message' => 'token expired',
                'code' => 401],
            401
        );
        $response->header('Content-Type', 'application/json');
        return $response;
    }

    //token is invalid
    if(!$user)
    {
        $response = Response::json([
                'error' => true,
                'message' => 'Not authenticated',
                'code' => 401],
            401
        );
        $response->header('Content-Type', 'application/json');
        return $response;
    }

});
