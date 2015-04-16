<?php
namespace LifeLi\controllers;

use LifeLi\models\Offers\OfferTransformer;
use LifeLi\models\Block_users\BlockUser;
use LifeLi\models\Notification\NotifyUser;
use LifeLi\models\Offers\Offer;
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
     * create a offer by user_id
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

        //check if user already offered today
        if(!$request->eligible_for_requests($id, 'OFFER')) {
            return $this->set_status(405, 'already offer today');
        }
        //get all input
        $arr_inputs = \Input::json();
        $arr_request_data = $arr_inputs->get('offer');
        $arr_user_data = $arr_inputs->get('users');
        $arr_request = $request->get_array_to_db($arr_request_data);
        $contacts = $arr_request['contacts'];
        unset($arr_request['contacts']);
        $v = $request->validate($arr_request, 'create');
        if($v->passes()){
            //valid data
            $arr_request['user_id'] = $user->id;
            $arr_request['request_type'] = 'OFFER';

            $request = Offer::create($arr_request);
            //save contact for offer
            foreach($contacts as $contact) {
                $request->contacts()->create([
                    'contact' => $contact
                ]);
            }
            //get all offered users
            $users =  User::whereIn('id', $arr_user_data)->get();
            if($request) {
                $objNotification = new NotifyUser();
                $objNotification->blood_donor_mail_request($users, $user, $request);
                $objNotification->blood_donate_requests($users, $user,$request );
                return $this->set_status(201, $this->fractal->item(Offer::find($request->id), new OfferTransformer()));
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
        //offer not found
        if(!$request) {
            return $this->set_status(404, 'offer not found');
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
     * PUT /offers/{id}
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
     * list of offers created by an user
     * @param $user_id
     * @return array
     */
    public function user_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $requests = $user->offers;
            return $requests;
            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new OfferTransformer()));
            }
            else {
                return $this->set_status(404, 'no offer of this user');
            }
        }
        else {
            return $this->set_status(404, 'user not exists');
        }
    }

    /**
     * get the list of offer user sent
     * @param $user_id
     * @return array
     */
    public function users_sent_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $offers = $user->offers()->orderBy('id', 'desc')->first();
            if($offers) {
                $this->fractal->parseIncludes('Response');
                return $this->set_status(200, $this->fractal->item($offers, new OfferTransformer()));
            }
            else {
                return $this->set_status(404, 'no offer of this user');
            }
        }
        else {
            return $this->set_status(404, 'user not exists');
        }
    }

    /**
     * get the list of received offer
     * @param $user_id
     * @return array
     */
    public function users_receive_offers($user_id){

        $user = User::find($user_id);
        if($user){
            $requests = $user->offer_users()->whereIn('status_id',[1,2,3,5])->orderBy('status_id')->get();

            if($requests->count()) {
                return $this->set_status(200, $this->fractal->collection($requests, new OfferResponseTransformer()));
            }
            else {
                return $this->set_status(404, 'no offer of this user');
            }
        }
        else {
            return $this->set_status(404, 'user not exists');
        }
    }

    /**
     * accept a offer by offer_id
     * @param $request_user_id
     * @return array
     */
    public function accept_offer($offer_id) {

        $user_offer = OfferUser::find($offer_id);
        //request not found
        if(!$user_offer){
            return $this->set_status(404, 'offer not found');
        }
        //check already accepted by someone
        if($user_offer->offer->status == 1) {
            return $this->set_status(404, 'Offer already accepted');
        }
        //update user request to set it replied
        $user_offer->status_id = OfferUser::$request_status['replied'];
        $user_offer->save();

        //add contract
        $arr_inputs = \Input::json();
        $contacts = $arr_inputs->get('contacts');
        //save contact for offer
        foreach($contacts as $contact) {
            $user_offer->contacts()->create([
                'contact' => $contact
            ]);
        }
        //update offer status
        $user_offer->offer->status = 1;
        $user_offer->offer->save();
        $acceptor = User::find($user_offer->receiver);
        if($acceptor) {
            $notify = new NotifyUser();
            $offer = Offer::find($user_offer->offer_id);
            $requester = User::find($offer->user_id);
            //send accept request notification
            $notify->ack_accept($acceptor, $requester);
            $arrUser = $this->fractal->item($acceptor,new UserTransformer());
            //add contacts for this response
            $arrUser['contacts'] = $contacts;
            return $this->set_status(200, $arrUser);
        }
        else {
            return $this->set_status(200, array('offer user not found'));
        }
    }

    /**
     * decline a offer by user offer_id
     * @param $request_user_id
     * @return array
     */
    public function decline_offer($offer_id){

        $user_offer = OfferUser::find($offer_id);
        //user offer not found
        if(!$user_offer){
            return $this->set_status(404, 'offer not found');
        }
        //get all input
        $input = \Input::json();
        $content = $input->get('message');
        if($content){
            $user_offer->content = $content;
        }
        $offer = Offer::find($user_offer->offer_id);
        //check request exists or not
        if(!$offer){
            return $this->set_status(404, 'offer not found');
        }
        //check request is finished or not
        if($offer->status){
            return $this->set_status(405, 'offer finished');
        }

        //check all ready declined or not
        if($user_offer->status_id != OfferUser::$request_status['declined']){
            $user_offer->status_id = OfferUser::$request_status['declined'];
            $user_offer->save();

            $notify = new NotifyUser();
            $decliner = User::find($user_offer->receiver);
            $requester = User::find($offer->user_id);
            $notify->decline_request($decliner, $requester);
            return $this->set_status(200,'offer declined successfully');
        }
        else {
            return $this->set_status(405,'offer already declined');
        }
    }

    /**
     * block user by user_offer id
     * @param $id
     * @return array
     */
    public function block_user($id){

        $user_offer = OfferUser::find($id);
        //user request not found
        if(!$user_offer){
            return $this->set_status(404, array('offer not found'));
        }
        $user_offer->status_id = OfferUser::$request_status['blocked'];
        $user_offer->save();
        $offer = Offer::find($user_offer->offer_id);
        //check already block
        $alreadyBlocked = BlockUser::where(function ($query) use ($user_offer, $offer) {
            $query->where('blocked_by', '=', $user_offer->receiver)
                ->Where('blocked_user', '=',  $offer->user_id);
        })->get();
        if(!count($alreadyBlocked)) {
            $blnBlock = BlockUser::create(['blocked_by' => $user_offer->receiver, 'blocked_user' => $offer->user_id ]);
            return $blnBlock?  $this->set_status(200, array('user blocked successfully')) : $this->set_status(500, array('something wrong'));
        }
        return $this->set_status(200, array('already blocked'));
    }

    /**
     * ignore a user offer
     * @param $id
     * @return array
     */
    public function ignore_offer($id) {

        $user_offer = OfferUser::find($id);
        if(!$user_offer){
            return $this->set_status(404, array('offer not found'));
        }
        if($user_offer->status_id != OfferUser::$request_status['ignored']) {
            //ignore request
            $user_offer->status_id = OfferUser::$request_status['ignored'];
            $user_offer->save();
            return $this->set_status(200, array('offer ignored successfully'));
        }
        return $this->set_status(403, array('offer can not be ignore'));

    }

    /**
     * update status of the user offer
     * @param $id
     * @return array
     */

    public function update_status($id) {
        $user_request = OfferUser::find($id);
        $req_status = \Input::get('action');
        if(!$user_request){
            return $this->set_status(404, array('offer not found'));
        }

        $status = RequestStatus::where('status', '=', $req_status)->first();
        $user_request->status_id = $status->id;
        $blnUpdate = $user_request->save();

        if($blnUpdate){
            return $this->set_status(200, array('offer status is updated successfully'));
        }
        return $this->set_status(403, array('offer is read already'));
    }


    /**
     * get list of all user offer made by an user
     * @param $user_id
     * @return array
     */
    public function all_offered_users($user_id){

        $user = User::find($user_id);
        if(!$user){
            return $this->set_status(404, array('user not found'));
        }
        $arrUsers = \DB::table('users')
            ->join('offers_users', 'users.id', '=', 'offers_users.receiver')
            ->join('offers', 'offers_users.offer_id', '=', 'offers.id')
            ->join('request_status', 'offers_users.status_id', '=', 'request_status.id')
            ->where('offers.user_id', '=',$user_id )
            ->select('users.email','users.mobile_no AS mobile', 'request_status.status', 'offers.created_at' )
            ->orderBy('offers_users.status_id')
            ->get();
        return $this->set_status(200, $arrUsers);
    }

    /**
     * filter the offer based on status
     * @param $request_id
     * @return array
     */
    public function filter_offer($offer_id){

        $offer = Offer::find($offer_id);
        if(!$offer){
            return $this->set_status(404, 'offer not found');
        }
        $type = \Input::get('status');

        $status = RequestStatus::where('status', '=', $type)->first();

        $response = $offer->offer_users()->where('status_id', '=', $status->id)->get();
        return $this->set_status(200, $this->fractal->collection($response, new OfferResponseTransformer()));
    }

    /**
     * @desc delete user offer by id
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
