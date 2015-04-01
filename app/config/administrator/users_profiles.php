<?php
return array(

    'title' => 'Profiles',
    'single' => 'profile',
    'model' => 'LifeLi\models\Profiles\Profile',
    'columns' => array(
        'name' => array(
            'title' => 'Name',
        ),
        'country' => array(
            'title' => 'Country',
        ),
        'blood_group' => array(
            'title' => 'Blood Group',
        ),
        'user_id' => array(
            'title' => 'User',
        ),

    ),

    'edit_fields' => array(
        'name' => array(
            'title' => 'Name',
            'type' => 'text',
        ),
        'country' => array(
            'title' => 'Country',
        ),
        'blood_group' => array(
            'title' => 'Blood Group',
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => 'Name',
            'type' => 'text',
        ),
        'country' => array(
            'title' => 'Country',
        ),
        'blood_group' => array(
            'title' => 'Blood Group',
        ),
    )
);