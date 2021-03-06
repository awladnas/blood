<?php namespace LifeLi\controllers;

use Illuminate\Support\Facades\Input;
use LifeLi\models\Block_users\BlockUser;
use LifeLi\models\Request_users\Request_user;
use LifeLi\models\Request_users\ResponseTransformer;
use LifeLi\models\Requests\Request;
use LifeLi\models\Requests\RequestContact;
use LifeLi\models\Requests\RequestTransformer;
use LifeLi\models\RequestStatus\RequestStatus;
use LifeLi\models\UserNotification\UserNotification;
use LifeLi\models\Users\User;
use LifeLi\models\Users\UserTransformer;
use Sorskod\Larasponse\Larasponse;
use LifeLi\models\Notification\NotifyUser;

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
     * create a request for blood request
     * @param $id
     * @return array
     */

    public function store($id)
    {
        $user = User::find($id);
        //user not found
        if(!$user) {
            return $this->set_status(404, 'user not exist');
        }

        $request = new Request();
        //check if user already request today
        if(!$request->eligible_for_requests($id)) {
            return $this->set_status(405, 'already requested today');
        }
        //get all input
        $arr_inputs = \Input::json();
        $arr_request_data = $arr_inputs->get('request');
        $arr_user_data = $arr_inputs->get('users');
        $arr_request = $request->get_array_to_db($arr_request_data);
        $contacts = $arr_request['contacts'];
        unset($arr_request['contacts']);
        $v = $request->validate($arr_request, 'create');
        if($v->passes()){
            //valid data
            $arr_request['user_id'] = $user->id;

            $request = Request::create($arr_request);
            //save contact for request
            foreach($contacts as $contact) {
                $request->contacts()->create([
                    'contact' => $contact
                ]);
            }
            //get all requested users
            $users =  User::whereIn('id', $arr_user_data)->get();
            if($request) {
                $objNotification = new NotifyUser();
                $objNotification->blood_recipient_mail_request($users, $user, $request);
                $objNotification->blood_requests($users, $user,$request );

                return $this->set_status(201, $this->fractal->item(Request::find($request->id), new RequestTransformer()));
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
        /** @var Request $request */
        $request = Request::find($id);
        //request not found
        if(!$request) {
            return $this->set_status(404, 'request not found');
        }
        $this->fractal->parseIncludes('Response');
        return $this->set_status(200, $this->fractal->item($request, new RequestTransformer()));
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
     * list of requests created by an user
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
     * get the list of request with the requested user list to whom request is sent
     * @param $user_id
     * @return array
     */
    public function users_sent_requests($user_id){

        $user = User::find($user_id);
        if($user){
            $this->fractal->parseIncludes('Response');
            $request = $user->requests()->orderBy('id', 'desc')->first();
            if($request) {
                return $this->set_status(200, $this->fractal->item($request, new RequestTransformer()));
            }
            else {
                return $this->set_status(404, 'user\'s have no requests');
            }
        }
        else {
            return $this->set_status(404, 'user not exists');
        }
    }

    /**
     * get the list of received request list
     * @param $user_id
     * @return array
     */
    public function users_receive_requests($user_id) {

        $user = User::find($user_id);
        if($user){
            $requests = $user->request_user()->whereIn('status_id',[1,2,3])->orderBy('status_id')->get();
            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new ResponseTransformer()));
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
     * accept a request by request_user id
     * @param $request_user_id
     * @return array
     */
    public function accept_request($request_user_id) {

        $user_request = Request_user::find($request_user_id);
        //request not found
        if(!$user_request){
            return $this->set_status(404, 'request not found');
        }
        //check already accepted by someone
        if($user_request->request->status == 1) {
            return $this->set_status(404, 'Request already accepted');
        }
        //update user request to set it replied
        $user_request->status_id = Request_user::$request_status['replied'];
        $user_request->save();

        //add contract
        $arr_inputs = \Input::json();
        $contacts = $arr_inputs->get('contacts');
        //save contact for request
        foreach($contacts as $contact) {
            $user_request->contacts()->create([
                'contact' => $contact
            ]);
        }
        //update request a
        $user_request->request->status = 1;
        $user_request->request->save();
        $acceptor = User::find($user_request->receiver);
        if($acceptor) {
            $notify = new NotifyUser();
            $request = Request::find($user_request->request_id);
            $requester = User::find($request->user_id);
            //send accept request notification
            $notify->ack_accept($acceptor, $requester);
            $arrUser = $this->fractal->item($acceptor,new UserTransformer());
            //add contacts for this response
            $arrUser['contacts'] = $contacts;
            return $this->set_status(200, $arrUser);
        }
        else {
            return $this->set_status(200, array('request user not found'));
        }
    }

    /**
     * decline a request by request_user id
     * @param $request_user_id
     * @return array
     */
    public function decline_request($request_user_id){

        $user_request = Request_user::find($request_user_id);
        //user request not found
        if(!$user_request){
            return $this->set_status(404, 'request not found');
        }
        //get all input
        $input = \Input::json();
        $content = $input->get('message');
        if($content){
            $user_request->content = $content;
        }
        $request = Request::find($user_request->request_id);
        //check request exists or not
        if(!$request){
            return $this->set_status(404, 'request not found');
        }
        //check request is finished or not
        if($request->status){
            return $this->set_status(405, 'request finished');
        }

        //check all ready declined or not
        if($user_request->status_id != Request_user::$request_status['declined']){
            $user_request->status_id = Request_user::$request_status['declined'];
            $user_request->save();

            $notify = new NotifyUser();
            $decliner = User::find($user_request->receiver);
            $requester = User::find($request->user_id);
            $notify->decline_request($decliner, $requester);
            return $this->set_status(200,'request declined successfully');
        }
        else {
            return $this->set_status(405,'request already declined');
        }
    }

    /**
     * block user by user_request id
     * @param $id
     * @return array
     */
    public function block_user($id){

        $user_request = Request_user::find($id);
        //user request not found
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        $user_request->status_id = Request_user::$request_status['blocked'];
        $user_request->save();
        $request = Request::find($user_request->request_id);
        //check already block
        $alreadyBlocked = BlockUser::where(function ($query) use ($user_request,$request) {
            $query->where('blocked_by', '=', $user_request->receiver)
                ->Where('blocked_user', '=',  $request->user_id);
        })->get();
        if(!count($alreadyBlocked)) {
            $blnBlock = BlockUser::create(['blocked_by' => $user_request->receiver, 'blocked_user' => $request->user_id ]);
            return $blnBlock?  $this->set_status(200, array('user blocked successfully')) : $this->set_status(500, array('something wrong'));
        }
        return $this->set_status(200, array('already blocked'));
    }

    /**
     * ignore a user request
     * @param $id
     * @return array
     */
    public function ignore_request($id) {

        $user_request = Request_user::find($id);
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        if($user_request->status_id != Request_user::$request_status['ignored']) {
            //ignore request
            $user_request->status_id = Request_user::$request_status['ignored'];
            $user_request->save();
            return $this->set_status(200, array('request ignored successfully'));
        }
        return $this->set_status(403, array('request can not be ignore'));

    }

    /**
     * update request status
     * @param $id
     * @return array
     */
    public function update_status($id) {
        $user_request = Request_user::find($id);
        $req_status = \Input::get('action');
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }

        $status = RequestStatus::where('status', '=', $req_status)->first();
        $user_request->status_id = $status->id;
        $blnUpdate = $user_request->save();

        if($blnUpdate){
            return $this->set_status(200, array('request status is updated successfully'));
        }
        return $this->set_status(403, array('request is read already'));
    }


    /**
     * get list of all requests made by an user(sent_list)
     * @param $user_id
     * @return array
     */
    public function all_requested_users($user_id){

        $user = User::find($user_id);
        if(!$user){
            return $this->set_status(404, array('user not found'));
        }
        $arrUsers = \DB::table('users')
            ->join('requested_users', 'users.id', '=', 'requested_users.receiver')
            ->join('requests', 'requested_users.request_id', '=', 'requests.id')
            ->join('request_status', 'requested_users.status_id', '=', 'request_status.id')
            ->where('requests.user_id', '=',$user_id )
            ->select('users.email','users.mobile_no AS mobile', 'request_status.status', 'requests.created_at' )
            ->orderBy('requested_users.status_id')
            ->get();
        return $this->set_status(200, $arrUsers);
    }

    /**
     * @param $request_id
     * @return array
     */
    public function filter_request($request_id){

        $request = Request::find($request_id);
        if(!$request){
            return $this->set_status(404, 'request not found');
        }
        $type = \Input::get('status');

        if( !isset(Request_user::$request_status[$type])){
            return $this->set_status(404, 'request status type not found');
        }
        $status = RequestStatus::where('status', '=', $type)->first();

        $response = $request->Request_users()->where('status_id', '=', $status->id)->get();
        return $this->set_status(200, $this->fractal->collection($response, new ResponseTransformer()));
    }

    /**
     * @desc delete user request by id
     * @since 2015-03-31
     * @version 2015-03-31
     * @author awlad < awlad@nascenia.com >
     * @param int $user_request_id
     * @return array
     */
    public function destroy_user_requests($user_request_id){
        $blnIsDeleted = Request_user::find($user_request_id)->delete();
        return $this->set_status(200, $blnIsDeleted);
    }
}