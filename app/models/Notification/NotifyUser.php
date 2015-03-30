<?php

namespace LifeLi\models\Notification;


use LifeLi\models\Request_users\Request_user;
use LifeLi\models\UserNotification\UserNotification;

class NotifyUser {
    protected $_blood_request_message = 'Need urgent blood';
    protected $_blood_donor_message = 'Will donate blood';
    protected $_blood_accept_message = 'accept blood request';

    public function __construct()
    {

    }

    /**
     * @param $users
     * @param $donor
     * @param $request
     * @internal param null $msg
     * @return mixed
     */
    public function blood_donate_requests($users, $donor, $request ){

       $arr_push = [];
        $msg = $request->content ? $request->content : $this->_blood_donor_message;
        $msg .= 'Communication : ' . $donor->mobile_no;
       foreach($users as $user) {
           //create user requests
           Request_user::create([
               'request_id' => $request->id,
               'receiver' => $user->id,
               'status_id' => 1,
               'content'   => $msg
           ]);
           //add notification to the table
           $this->add_notification( $donor->id, $user->id, 'blood_donate', $msg);
           $arr_push[] =  \PushNotification::Device($user->device_id);
       }
        return $this->send_notification($arr_push, $msg);
    }

    /**
     * @param $users
     * @param $requester
     * @param $request
     * @internal param null $msg
     * @return mixed
     */
    public function blood_requests($users, $requester, $request ){

        $arr_push = [];
        $msg = $request->content ? $request->content : $this->_blood_request_message;
        $msg .= ' Communication : ' . $requester->mobile_no;
        foreach($users as $user) {
            //create user requests
            Request_user::create([
                'request_id'    => $request->id,
                'receiver'      => $user->id,
                'status_id'     => 1,
                'content'       => $msg
            ]);
            //add notification to the table
            $this->add_notification($requester->id, $user->id, 'blood_request', $msg);
            $arr_push[] =  \PushNotification::Device($user->device_id);
        }
        return $this->send_notification($arr_push, $msg);
    }

    /**
     * @param $acceptor
     * @param $requester
     * @internal param $request
     * @return mixed
     */
    public function ack_accept($acceptor, $requester){

        $push = \PushNotification::app('appNameAndroid')
            ->to($requester->device_token)
            ->send($this->_blood_accept_message. ' Contact: '. $acceptor->mobile_no);
        $this->add_notification($requester->id, $acceptor->id, 'accept_request');
        $response = $push->getAdapter()->getResponse();
        return $response;
    }

    /**
     * @param $arr_push
     * @param $msg
     * @param string $app_name
     * @return mixed
     */
    private function send_notification($arr_push, $msg, $app_name = 'appNameAndroid') {

        $devices = \PushNotification::DeviceCollection($arr_push);
        $collection = \PushNotification::app($app_name)
            ->to($devices)
            ->send($msg);
        foreach ($collection->pushManager as $push) {
            $response = $push->getAdapter()->getResponse();
        }
        return $response;
    }

    /**
     * @param $user_id
     * @param $request_user_id
     * @param $type
     * @param null|string $desc
     * @return static
     */
    public function add_notification($user_id, $request_user_id, $type, $desc = 'no contents'){

        $arrData = [
            'user_id'               =>  $user_id,
            'request_user_id'       =>  $request_user_id,
            'notify_type'           =>  $type,
            'desc'                  =>  $desc
        ];
        return UserNotification::create($arrData);
    }

} 