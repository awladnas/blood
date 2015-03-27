<?php

namespace LifeLi\models\Notification;


use LifeLi\models\Users\User;

class NotifyUser {
    protected $_blood_request_message = 'Need urgent blood';
    protected $_blood_donor_message = 'Need urgent blood';

    public function __construct()
    {

    }

    /**
     * @param $users
     * @param $donor
     * @param null $msg
     * @return mixed
     */
    public function blood_donate_requests($users, $donor, $msg = null ){
       $arr_push = [];
       foreach($users as $user) {
           return \PushNotification::Device($user->device_id);
               $arr_push[] =  \PushNotification::Device($user->device_id);
       }
        $msg = $msg ? $msg : $this->_blood_donor_message;
        $msg .= 'Communication : ' . $donor->mobile_no;
        return $this->send_notification($arr_push, $msg);
    }

    /**
     * @param $users
     * @param $requester
     * @param null $msg
     * @return mixed
     */
    public function blood_requests($users, $requester, $msg = null ){
        $arr_push = [];
        foreach($users as $user) {
            $arr_push[] =  \PushNotification::Device($user->device_id);
        }
        $msg = $msg ? $msg : $this->_blood_donor_message;
        $msg .= 'Communication : ' . $requester->mobile_no;
        return $this->send_notification($arr_push, $msg);
    }

    /**
     * @param $request
     * @param $acceptor
     * @param $requester
     * @return mixed
     */
    public function ack_accept($request, $acceptor, $requester){

        $push = \PushNotification::app('appNameAndroid')
            ->to($requester->device_token)
            ->send('Hello World, i`m a push message');
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

} 