<?php namespace LifeLi\controllers;

use LifeLi\models\Users\UserTransformer;
use Sorskod\Larasponse\Larasponse;
use LifeLi\models\Users\User;
class UsersController extends BaseController {

    protected $fractal;

    public function __construct(Larasponse $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		$users = User::all();
        $data = $this->fractal->collection($users, new UserTransformer());
        return $this->set_status(200, $data['data']);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $arr_user = \Input::get('user');
        $user = new User();
        $user->mobile_no = $arr_user['mobile_no'];
        $user->email = $arr_user['email'];
        $user->password = $arr_user['password'];
        $user->valid_until = $user->get_token_expired_date();
        $user->api_token = $user->generate_token();
        if($user->save()) {
            return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
        }
        return $this->set_status(500);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);
        if (!$user)
        {
           $this->set_status('404');
        }

        return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $user = User::find($id);
        $arr_user = \Input::get('user');
        $arr_user['valid_until'] = $user->get_token_expired_date();
        $arr_user['api_token'] = $user->generate_token();
        $bln_update =  User::find($id)->update($arr_user);

        if($bln_update) {
            return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
        }
        return $this->set_status(500);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $user =  User::find($id);
        if($user)
        {
            $user->destroy();
            return $this->set_status(200);
        }
	}


}
