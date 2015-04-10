<?php

/**
 * Notification model config
 */

return array(

    'title' => 'UserNotifications',
    'single' => 'UserNotification',
    'model' => 'LifeLi\models\UserNotification\UserNotification',

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'sender_id' => array(
            'relationship' => 'user',
            'title' => 'sender',
            'select' => '(:table).email'
        ),
        'receiver_id' => array(
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
            'type' => 'date',
            'title' => 'Date',
        ),
        'notify_type' => array(
            'title' => 'Type',
            'type' => 'text'
        ),
        'user' => array(
            'type' => 'relationship',
            'relationship' => 'user',
            'title' => 'Sender',
            'name_field' => 'email'
        ),
        'receiver_user' => array(
            'type' => 'relationship',
            'relationship' => 'receiver_user',
            'title' => 'Receiver',
            'name_field' => 'email'
        ),
    ),
    'action_permissions'=> array(
        'delete' => function($model){
                return Auth::user()->role == 'super_admin';
            }
    ,
        'block' => function($model){
                return Auth::user()->role == 'super_admin';
            },
        'create' => false,
        'new' => false
    ),

);