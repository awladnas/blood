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
     * @param $receivers
     * @param $sender
     * @param $message
     */

    public function blood_donor_mail_request($receivers, $sender, $message){
        $data = [
            'sender' => $sender->email,
            'subject' => 'Blood Donate Request'
        ];

        foreach ($receivers as $receiver) {
            \Mail::queue('email_body', $data, function ($message) use ($receiver, $data) {
                $message
                    ->from($data['sender'], 'Lifeli')
                    ->to($receiver->email,
                        $receiver->name)
                    ->subject($data['subject']);
            });
        }
    }

    /**
     * @param $receivers
     * @param $sender
     * @param $message
     */

    public function blood_recipient_mail_request($receivers, $sender, $message){
        $data = [
            'sender' => $sender->email,
            'subject' => 'Blood Request'
        ];

        foreach ($receivers as $receiver) {

            \Mail::queue('email_body', $data, function($message) use ($receiver, $data) {
                $message
                    ->from($data['sender'], 'Lifeli')
                    ->to($receiver->email,
                        $receiver->name)
                    ->subject($data['subject']);
            });
        }

    }

    /**
     * @param $users
     * @param $donor
     * @param $request
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
               'receiver'   => $user->id,
               'status_id'  => Request_user::$request_status['unread'],
               'content'    => $msg
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
                'status_id'     => Request_user::$request_status['unread'],
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
     * @return mixed
     */
    public function ack_accept($acceptor, $requester){

//        $push = \PushNotification::app('appNameAndroid')
//            ->to($requester->device_token)
//            ->send($this->_blood_accept_message. ' Contact: '. $acceptor->mobile_no);
//
        $this->add_notification($requester->id, $acceptor->id, 'accept_request');
//        $response = $push->getAdapter()->getResponse();
//        return $response;
    }

    /**
     * @param $user
     * @param $requester
     */
    public function decline_request($user, $requester){
        //        $push = \PushNotification::app('appNameAndroid')
//            ->to($requester->device_token)
//            ->send($this->_blood_accept_message. ' Contact: '. $acceptor->mobile_no);
//
        $this->add_notification($requester->id, $user->id, 'decline_request');
//        $response = $push->getAdapter()->getResponse();
//        return $response;
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
     * @param $sender_id
     * @param $receiver_id
     * @param $type
     * @param null|string $desc
     * @return
     */
    public function add_notification($sender_id, $receiver_id, $type, $desc = 'no contents'){

        $arrData = [
            'sender_id'             =>  $sender_id,
            'receiver_id'           =>  $receiver_id,
            'notify_type'           =>  $type,
            'desc'                  =>  $desc
        ];
        return UserNotification::create($arrData);
    }

} 