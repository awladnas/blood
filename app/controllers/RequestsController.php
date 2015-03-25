<?php namespace LifeLi\controllers;

use Illuminate\Support\Facades\Input;
use LifeLi\models\Block_users\BlockUser;
use LifeLi\models\Request_users\Request_user;
use LifeLi\models\Requests\Request;
use LifeLi\models\Requests\RequestTransformer;
use LifeLi\models\Users\User;
use LifeLi\models\Users\UserTransformer;
use Sorskod\Larasponse\Larasponse;

class RequestsController extends BaseController {

    protected $fractal;

    public function __construct(Larasponse $fractal)
    {
        $this->fractal = $fractal;
    }

	/**
	 * Display a listing of the resource.
	 * GET /requests
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /requests/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

    /**
     * Store a newly created resource in storage.
     * POST /requests
     *
     * @param null $id
     * @return Response
     */
	public function store($id = null)
	{
        $user = User::find($id);
        if(!$user) {
            return $this->set_status(404, 'user not exist');
        }
        $request = new Request();
        $arr_inputs = \Input::json();
        $arr_request_data = $arr_inputs->get('request');
//        $arr_user_data = $arr_inputs->get('users');
//        return var_dump($arr_user_data);
        $arr_request = $request->get_array_to_db($arr_request_data);
        $v = $request->validate($arr_request, 'create');
        if($v->passes()){
            //valid data
            $arr_request['user_id'] = $user->id;
            $request = Request::create($arr_request);
            if($request) {
                if($request->request_type != 'blood') {
                    /* todo notification with phone number */
                    /* todo cron for checking after 24 hours and send expired to not response users*/
                }

                /** todo push notifications and email to all users here */
                return $this->set_status(201, $this->fractal->item($request, new RequestTransformer()));
            }
        }
        else {
            //validation failed
            return $this->set_status(204, $v->errors());
        }
	}

	/**
	 * Display the specified resource.
	 * GET /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /requests/{id}/edit
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
	 * PUT /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    /**
     * @param $user_id
     * @return array
     */
    public function user_requests($user_id){

        $user = User::find($user_id);
        if($user){
            $requests = $user->requests;
            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new RequestTransformer()));
            }
            else {
                return $this->set_status(404, 'no requests of this user');
            }
        }
        else {
           return $this->set_status(404, 'user not exists');
        }
    }

    /**
     * @param $request_user_id
     * @return array
     */
    public function accept_request($request_user_id){

        $user_request = Request_user::find($request_user_id);
        if(!$user_request){
            return $this->set_status(404, 'request not found');
        }
        $user_request->status_id = 1;
        $user_request->save();
        /** todo notify request creator */
        //$user_request->request->user_id;
        $user_request->request->status = 1;
        $user_request->request->save();
        $acceptor = User::find($user_request->receiver);
        if($acceptor) {
            return $this->set_status(200, $this->fractal->item($acceptor,new UserTransformer()));
        }
        else {
            return $this->set_status(200, array('request user not found'));
        }
    }

    /**
     * @param $request_user_id
     * @return array
     */
    public function decline_request($request_user_id){

        $user_request = Request_user::find($request_user_id);
        if(!$user_request){
            return $this->set_status(404, 'request not found');
        }
        $input = \Input::json();
        $content = $input->get('content');
        if($content){
            $user_request->content = $content;
        }
        $request = Request::find($user_request->request_id);
        if(!$request){
            return $this->set_status(404, 'request not found');
        }
        if($user_request->status_id != 2){
            $user_request->status_id = 2;
            $user_request->save();

            if($request->request_type !=  'blood'){
                /* todo send notification to request creator and create another user_request*/
            }
            return $this->set_status(200,'request declined successfully');
        }
        else {
            return $this->set_status(405,'request already declined');
        }
        /** todo notify request creator */
        //$user_request->request->user_id;
//        $user_request->request->status = 1;
//        $user_request->request->save();
//        $acceptor = User::find($user_request->receiver);
//        if($acceptor) {
//            return $this->set_status(200, $this->fractal->item($acceptor,new UserTransformer()));
//        }
//        else {
//            return $this->set_status(200, array('request user not found'));
//        }
    }

    /**
     * block user by user_request id
     * @param $id
     * @return array
     */
    public function block_user($id){

        $user_request = Request_user::find($id);
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        $request = Request::find($user_request->request_id);
        $alreadyBlocked = BlockUser::where(function ($query) use ($user_request,$request) {
            $query->where('block_by', '=', $user_request->receiver)
                ->Where('blocked_user', '=',  $request->user_id);
        });
        if(!$alreadyBlocked) {
            $blnBlock = BlockUser::create(['blocked_by' => $user_request->receiver, 'blocked_user' => $request->user_id ]);
            return $blnBlock?  $this->set_status(200, array('user blocked successfully')) : $this->set_status(500, array('something wrong'));
        }
        return $this->set_status(200, array('already blocked'));
    }

    /**
     * @param $id
     * @return array
     */
    public function ignore_request($id){

        $user_request = Request_user::find($id);
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        if($user_request->status_id != 1) {
            //ignore request
            $user_request->status_id = 3;
            $user_request->save();
            return $this->set_status(200, array('request ignored successfully'));
        }
        return $this->set_status(403, array('request can not be ignore'));

    }

    public function all_requested_users($user_id){
        /** TODO: list of all users those requested by an user */
    }

    public function all_mixed_requests_record($user_id){
        /** TODO:  get all user requests of an user of specific type*/
        $type = Input::get('record_type');
        switch($type) {
            case 'replied' :
                break;
            case 'shared' :
                break;
            case 'declined' :
                break;
            case 'ignored' :
                break;
            case 'unread' :
                break;
        }
    }

    public function destroy_user_requests($user_request_id){
        /* TODO: delete user request */

    }
}