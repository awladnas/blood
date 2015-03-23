<?php namespace LifeLi\controllers;
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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$admin_users = $this->admin_user->all();

		return \View::make('admin_users.index', compact('admin_users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin_users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();
		$validation = \Validator::make($input, Admin_user::$rules);

		if ($validation->passes())
		{
			$this->admin_user->create($input);

			return \Redirect::route('admin_users.index');
		}

		return Redirect::route('admin_users.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$admin_user = $this->admin_user->findOrFail($id);

		return \View::make('admin_users.show', compact('admin_user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$admin_user = $this->admin_user->find($id);

		if (is_null($admin_user))
		{
			return \Redirect::route('admin_users.index');
		}

		return \View::make('admin_users.edit', compact('admin_user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(\Input::all(), '_method');
		$validation = \Validator::make($input, Admin_user::$rules);

		if ($validation->passes())
		{
			$admin_user = $this->admin_user->find($id);
			$admin_user->update($input);

			return \Redirect::route('admin_users.show', $id);
		}

		return \Redirect::route('admin_users.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->admin_user->find($id)->delete();

		return \Redirect::route('admin_users.index');
	}

    public function login()
    {
        return \View::make('admin_users.login');
    }

    public function authenticate_login()
    {
        $user = array(
            'email' => \Input::get('email'),
            'password' => \Input::get('password')
        );
        if (Auth::attempt($user)) {

            return \Redirect::route('admin_users.index')
                ->with('flash_notice', 'You are successfully logged in.');
        }

        // authentication failure! lets go back to the login page
        return \Redirect::route('admin.login')
            ->with('flash_error', 'Your username/password combination was incorrect.')
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();

        return Redirect::route('admin_users.index')
            ->with('flash_notice', 'You are successfully logged out.');
    }

}
