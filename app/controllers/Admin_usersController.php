<?php namespace LifeLi\controllers;
use Illuminate\Support\Facades\Input;
use LifeLi\models\Admin_users\Admin_user;
use Auth;


class Admin_usersController extends BaseController {

	/**
	 * Admin_user Repository
	 *
	 * @var Admin_user
	 */
	protected $admin_user;

	public function __construct(Admin_user $admin_user)
	{
		$this->admin_user = $admin_user;
	}

    /**
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return \View::make('admin_users.login');
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function authenticate_login()
    {
        $user = array(
            'email' => \Input::get('email'),
            'password' => \Input::get('password')
        );
        if (Auth::attempt($user)) {
            $user = Auth::user();
            if(!$user) {
                return false;
            }
            if($user->has_dashboard_access()) {
                return \Redirect::intended('/dashboard')->with('message', 'You are successfully logged in.');
            }
            else {
                return \Redirect::intended('/admin/documents')->with('message', 'You are successfully logged in.');
            }
        }

        // authentication failure! lets go back to the login page
        return \Redirect::route('admin.login')
            ->with('message', 'Your username/password combination was incorrect.')
            ->withInput();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {   if(Auth::check()) {
            Auth::logout();
         }
        return \Redirect::route('admin.login')
            ->with('message', 'You are successfully logged out.');
    }

}
