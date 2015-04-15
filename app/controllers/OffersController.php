<?php
namespace LifeLi\controllers;

use LifeLi\models\Block_users\BlockUser;
use LifeLi\models\Notification\NotifyUser;
use LifeLi\models\Offer\OfferTransformer;
use LifeLi\models\Offers\Offer;
use LifeLi\models\Offers\OfferResponse;
use LifeLi\models\Offers\OfferResponseTransformer;
use LifeLi\models\Offers\OfferUser;
use LifeLi\models\RequestStatus\RequestStatus;
use LifeLi\models\Users\User;
use LifeLi\models\Users\UserTransformer;
use Sorskod\Larasponse\Larasponse;

class OffersController extends BaseController {


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

        $request = new Offer();

        //check if user already request today
        if(!$request->eligible_for_requests($id, 'OFFER')) {
            return $this->set_status(405, 'already offer today');
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
            $arr_request['request_type'] = 'OFFER';

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
                $objNotification->blood_donor_mail_request($users, $user, $request);
                $objNotification->blood_donate_requests($users, $user,$request );
                /* todo cron for checking after 24 hours and send expired to not response users */

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
        $request = Offer::find($id);
        //request not found
        if(!$request) {
            return $this->set_status(404, 'request not found');
        }
        $this->fractal->parseIncludes('Response');
        return $this->set_status(200, $this->fractal->item($request, new OfferTransformer()));
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
    public function user_receive_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $requests = $user->offers;
            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new OfferTransformer()));
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
     * @param $user_id
     * @return array
     */
    public function users_sent_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $offers = $user->offers()->order_by('id', 'desc')->first();
            if($offers) {
                $this->fractal->parseIncludes('Response');
                return $this->set_status(200, $this->fractal->collection($offers, new OfferTransformer()));
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
     * @param $user_id
     * @return array
     */
    public function users_receive_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $requests = $user->offer_users()->whereIn('status_id',[1,2,3]);
            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new OfferTransformer()));
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
    public function accept_offer($request_user_id) {

        $user_request = OfferUser::find($request_user_id);
        //request not found
        if(!$user_request){
            return $this->set_status(404, 'request not found');
        }
        //check already accepted by someone
        if($user_request->offer->status == 1) {
            return $this->set_status(404, 'Request already accepted');
        }
        //update user request to set it replied
        $user_request->status_id = OfferUser::$request_status['replied'];
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
        $user_request->offer->status = 1;
        $user_request->offer->save();
        $acceptor = User::find($user_request->receiver);
        if($acceptor) {
            $notify = new NotifyUser();
            $request = Offer::find($user_request->request_id);
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

        $user_request = OfferUser::find($request_user_id);
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
        $request = Offer::find($user_request->request_id);
        //check request exists or not
        if(!$request){
            return $this->set_status(404, 'request not found');
        }
        //check request is finished or not
        if($request->status){
            return $this->set_status(405, 'request finished');
        }

        //check all ready declined or not
        if($user_request->status_id != OfferUser::$request_status['declined']){
            $user_request->status_id = OfferUser::$request_status['declined'];
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

        $user_request = OfferUser::find($id);
        //user request not found
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        $user_request->status_id = OfferUser::$request_status['blocked'];
        $user_request->save();
        $request = Offer::find($user_request->request_id);
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

        $user_request = OfferUser::find($id);
        if(!$user_request){
            return $this->set_status(404, array('request not found'));
        }
        if($user_request->status_id != OfferUser::$request_status['ignored']) {
            //ignore request
            $user_request->status_id = OfferUser::$request_status['ignored'];
            $user_request->save();
            return $this->set_status(200, array('request ignored successfully'));
        }
        return $this->set_status(403, array('request can not be ignore'));

    }

    public function update_status($id) {
        $user_request = OfferUser::find($id);
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
     * get list of all user requests made by an user
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
            ->select('users.email','users.mobile_no AS mobile', 'request_status.status', 'requests.request_type', 'requests.created_at' )
            ->orderBy('requested_users.status_id')
            ->get();
        return $this->set_status(200, $arrUsers);
    }

    /**
     * @param $request_id
     * @return array
     */
    public function filter_request($request_id){

        $request = Offer::find($request_id);
        if(!$request){
            return $this->set_status(404, 'request not found');
        }
        $type = \Input::get('status');

        $status = RequestStatus::where('status', '=', $type)->first();

        $response = $request->offer_users()->where('status_id', '=', $status->id)->get();
        return $this->set_status(200, $this->fractal->collection($response, new OfferResponseTransformer()));
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
        $blnIsDeleted = OfferUser::find($user_request_id)->delete();
        return $this->set_status(200, $blnIsDeleted);
    }

}
