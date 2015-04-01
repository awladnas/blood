<?php

/**
 * Users model config
 */

return array(

    'title' => 'Requests',
    'single' => 'request',
    'model' => 'LifeLi\models\Requests\Request',

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'user' => array(
            'relationship' => 'user',
            'title' => 'User',
            'select' => '(:table).email'
        ),
        'area' => array(
            'title' => 'Location',
        ),
        'blood_group' => array(
            'title' => 'Blood Group',
        ),
        'request_type' => array(
            'title' => 'Type',
        ),

        'status' => array(
            'title' => 'Status',
        ),
        'created_at' => array(
            'type' => 'Date',
            'title' => 'Date'
        )
    ),
    'edit_fields' => array(
        'area' => array(
            'title' => 'Location',
            'type' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'type' => 'bool',
        ),
    ),
    'filters' => array(
        'blood_group' => array(
            'type' => 'text',
            'title' => 'Blood Group',
        ),
        'created_at' => array(
            'type' => 'datetime',
            'title' => 'Date',
        ),
        'user_id' => array(
            'relationship' => 'user',
            'title' => 'User',
            'select' => '(:table).email',
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