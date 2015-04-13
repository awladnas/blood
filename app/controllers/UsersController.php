<?php namespace LifeLi\controllers;

use Illuminate\Support\Facades\Hash;
use LifeLi\models\Block_users\BlockUser;
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
        $mobile = isset($arr_user_data->is_mobile) ? true : false;
        $arr_user = $user->get_array_to_db($arr_user_data);
        $arr_user['valid_until'] = $user->get_token_expired_date();
        $arr_user['api_token'] = $user->generate_token();

        $v = $user->validate($arr_user, 'create');

        if($v->passes()) {
            if ($arr_user['steps'] < User::TOTAL_STEPS) $arr_user['steps']++;
            if ($arr_user['steps'] == User::TOTAL_STEPS){
                $arr_user['is_complete'] = true;
            }
            $length = $mobile ?  5 : 30;
            $arr_user['confirmation_code'] = $user->generate_token($length);
            if($mobile) {
               // send confirm code via services
            }
            elseif(isset($arr_user['email'])) {

                $data = array(
                    'email'     => $arr_user['email'],
                    'ConfirmationCode'  => $arr_user['confirmation_code']
                );

                \Mail::send('emails.signup', $data, function($message) use ($data)
                {
                    $message
                        ->from('lifeli@yahoo.com', 'Lifeli')
                        ->to($data['email'])
                        ->subject('Lifeli Account Confirmation');

                });
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
        if(!isset($arr_user['steps'])){
            return $this->set_status(204, 'steps is required');
        }
        $arr_user['valid_until'] = $user->get_token_expired_date();
        $arr_user['api_token'] = $user->generate_token();
        $v = $user->validate($arr_user, 'update');
        if($v->passes()){


            if ($arr_user['steps'] < User::TOTAL_STEPS) $arr_user['steps']++;
            if ($arr_user['steps'] == User::TOTAL_STEPS){
                $arr_user['is_complete'] = true;
            }
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
        if($user && \Hash::check($old_password, $user->password )) {

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

        $user = User::find($id);
        if($user) {
            $user->out_of_req = true;
            $user->save();
            return $this->set_status(200, $this->fractal->item($user, new UserTransformer()));
        }
        else {
            return $this->set_status(404, 'user not found');
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
            return $this->set_status(208, 'user already confirmed');
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
                if(\Hash::check($inputs->get('password'),$user->password)) {
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

    /**
     * @param $user_id
     * @return array
     */
    public function profile_complete($user_id){

        $user = User::find($user_id);
        if(!$user) {
            return $this->set_status(404, 'user not found');
        }
        if($user->is_complete) {
            return $this->set_status(200, ['is_complete' => true]);
        }
        else {
            return $this->set_status(200, ['steps' => $user->steps]);
        }
    }

    /**
     * @param null $profile_id
     * @internal param null $city
     * @internal param null $lat
     * @internal param null $lon
     * @internal param int $distance
     * @return array
     */
    public function search_user($user_id){

        /*TODO: generic message */

        $user = User::find($user_id);
        if(!$user) {
            return $this->set_status(404, 'user not found');
        }
        $city = \Input::get('city');
        $blood_group = \Input::get('blood_group');
        $lat = \Input::get('lat');
        $lng = \Input::get('lng');

        $distance = \Input::get('distance');
        if(!isset($lat, $lng) && isset($city) && $response = $user->get_location_from_city($city)) {
            $lat = $response->latitude();
            $lng = $response->longitude();
        }
        else{
            $lat =  $user->latitude;
            $lng =  $user->longitude;
        }
        $blood_group = isset($blood_group)? $blood_group : $user->blood_group;
        $distance = isset($distance)? $distance : 10;
        $block_users = BlockUser::where('blocked_by','=', $user->id)->lists('blocked_by');
        $unavailable_users = User::where('out_of_req', '=', true)->lists('id');
        $blocked_users = array_merge($block_users, $unavailable_users);
        $objUsers = $user->get_closest_profiles($user->id, $lat, $lng, $distance, $blood_group, $blocked_users);
        $arrSearchedUsers = $this->fractal->collection($objUsers, new UserTransformer());
        $arr_data['users'] = $arrSearchedUsers['data'];
        $arr_data['location'] = array('lat' => $lat, 'lng' => $lng);
        return $this->set_status(200, $arr_data);
    }
}
