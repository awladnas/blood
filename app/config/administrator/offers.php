<?php

/**
 * request model config
 */

return array(

    'title' => 'Offers',
    'single' => 'Offer',
    'model' => 'LifeLi\models\Offers\Offer',

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
        'content' => array(
            'title' => 'Content',
            'type' => 'text',
        ),
    ),
    'filters' => array(
        'blood_group' => array(
            'type' => 'text',
            'title' => 'Blood Group',
        ),
        'created_at' => array(
            'type' => 'date',
            'title' => 'Date',
        ),
        'user' => array(
            'type' => 'relationship',
            'title' => 'User',
            'relationship' => 'user',
            'name_field' => 'email'
        ),
        'status' => array(
            'title' => 'Status',
            'type' => 'bool'
        )
    ),
    'action_permissions'=> array(
        'delete' => function($model){
                return Auth::user()->role == 'super_admin';
            },
        'block' => function($model){
                return Auth::user()->role == 'super_admin';
            },
        'create' => false
    ),
);