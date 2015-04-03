<?php namespace LifeLi\controllers;

use LifeLi\models\Profiles\Profile;
use LifeLi\models\Profiles\ProfileTransformer;
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
        $user = new User();
        $arr_user_data = \Input::json('user');
        $mobile = $arr_user_data->is_mobile;
        $arr_user = $user->get_array_to_db($arr_user_data);
        $arr_user['valid_until'] = $user->get_token_expired_date();
        $arr_user['api_token'] = $user->generate_token();
        $v = $user->validate($arr_user, 'create');
        if($v->passes()) {
            $length = $mobile ?  5 : 30;
            $arr_user['confirmation_code'] = $user->generate_token($length);
            if($mobile) {
               // send confirm code via services
            }
            else {
                //send confirm code via email $arr_user['email']
            }
            $user = User::create($arr_user);
            if($user) {
                return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
            }

        }
        else {
            return $this->set_status(204, json_encode($v->errors()));
        }
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
           return $this->set_status('404', array('user not found'));
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
        $arr_inputs = \Input::json();
        $user = new User();
        $arr_user = $arr_inputs->get('user');
        $arr_user = $user->get_array_to_db($arr_user);
        $arr_user['valid_until'] = $user->get_token_expired_date();
        $arr_user['api_token'] = $user->generate_token();
        $v = $user->validate($arr_user, 'update');
        if($v->passes()){
            $bln_update =  User::find($id)->update($arr_user);
            $user = user::find($id);
            if(!$user){
                return $this->set_status(404);
            }
            if($bln_update) {
                return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
            }
        }
        else {
            return $this->set_status(204, json_encode($v->errors()));
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
            if($user->profile){
                $user->profile->delete();
            }
            $user->delete();
            return $this->set_status(200);
        }
	}

    /**
     * @param $id
     * @return array
     */
    public function change_password($id){
        $inputs = \Input::json();
        $old_password = $inputs->get('old_password');
        $new_password = $inputs->get('new_password');
        $user = User::find($id);
        if($user && $user->password == $old_password) {
            $user->password = $new_password;
            $user->api_token = $user->generate_token();
            $user->valid_until = $user->get_token_expired_date();
            $user->save();
            return $this->set_status(200, $this->fractal->item(User::find($id), new UserTransformer()));
        }
        return $this->set_status(404, 'user not found');
    }

    /**
     * @param $id
     * @return array
     */
    public function update_token($id){
        $user = User::find($id);
        if($user){
            $user->api_token = $user->generate_token();
            $user->valid_until = $user->get_token_expired_date();
            $user->save();
            return $this->set_status(200, $this->fractal->item(User::find($id), new UserTransformer()));
        }
        else {
           return $this->set_status(404, 'user not found');
        }
    }

    /**
     * user's can’t give blood set them to out of request
     * @param $id
     * @return array
     */
    public function out_of_request($id){

        $profile = Profile::where('user_id', '=', $id)->first();
        if($profile) {
            $profile->out_of_req = false;
            $profile->save();
            return $this->set_status(200, $this->fractal->item($profile, new ProfileTransformer()));
        }
        else {
            return $this->set_status(404, 'profile not found');
        }
    }

    /**
     * confirm user account by
     * @param $user_id
     * @param $confirmation_code
     * @return array
     */
    public function confirm($user_id, $confirmation_code){

        $user = User::find($user_id);
        if(!$user) {
            return $this->set_status(404, 'user not found');
        }
        if($user->is_confirm) {
            return $this->set_status(501, 'user already confirmed');
        }
        if( $confirmation_code == $user->confirmation_code ) {
            $user->is_confirm = 1;
            $user->confirmation_code = null;
            $user->save();
            return $this->set_status(200, 'account confirmed successfully');
        }
        return $this->set_status(501, 'invalid confirmation code');
    }


    /**
     * @return array
     */
    public function user_login(){

        $inputs = \Input::json();
        $user = User::where('mobile_no', $inputs->get('mobile_no'))->first();
        if($user) {
            if($user->is_confirm) {
                if($user->password == $inputs->get('password')) {
                    return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
                }
                return $this->set_status(404, 'invalid password');
            }
            else {
                return $this->set_status(404, 'unconfirmed account');
            }
        }
        return $this->set_status(404, 'invalid mobile number');
    }
}
