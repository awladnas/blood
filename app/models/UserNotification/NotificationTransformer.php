<?php namespace LifeLi\models\UserNotification;

use League\Fractal\TransformerAbstract;
use LifeLi\models\UserNotification\UserNotification;


class NotificationTransformer extends TransformerAbstract {


    public function transform(UserNotification $notification)
    {
        return [
            'id'             => (int) $notification->id,
            'sender_id'      => $notification->sender_id,
            'receiver_id'    => $notification->receiver_id,
            'notify_type'    => $notification->notify_type,
            'content'        => $notification->desc,
            'created_at'     => $notification->created_at,
            'updated_at'     => $notification->updated_at


        ];
    }
} 