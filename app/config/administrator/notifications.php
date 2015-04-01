<?php

/**
 * Users model config
 */

return array(

    'title' => 'UserNotifications',
    'single' => 'UserNotification',
    'model' => 'LifeLi\models\UserNotification\UserNotification',

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'user' => array(
            'relationship' => 'user',
            'title' => 'sender',
            'select' => '(:table).email'
        ),
        'request_user_id' => array(
            'title' => 'Receiver',
            'relationship' => 'receiver_user',
            'select' => '(:table).email'
        ),
        'notify_type' => array(
            'title' => 'Request Type',
        ),
        'created_at' => array(
            'type' => 'Date',
            'title' => 'Date'
        )
    ),
    'edit_fields' => array(
        'notify_type' => array(
            'title' => 'Request Type',
            'type' => 'text',
        ),
        'created_at' => array(
            'type' => 'datetime',
            'title' => 'Date',
        ),

    ),
    'filters' => array(
        'user_id' => array(
            'type' => 'text',
            'title' => 'Blood Group',
        ),
        'created_at' => array(
            'type' => 'datetime',
            'title' => 'Date',
        ),
        'notify_type' => array(
            'title' => 'Type',
            'type' => 'text'
        ),
    ),
    'action_permissions'=> array(
        'delete' => function($model){
                return Auth::user()->is_superuser;
            }
    ,
        'block' => function($model){
                return Auth::user()->is_superuser;
            }
    ),
);