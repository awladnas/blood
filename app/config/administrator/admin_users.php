<?php

/**
 * Admin User model config
 */

return array(

    'title' => 'Admin Users',
    'single' => 'Admin User',
    'model' => 'LifeLi\models\Admin_users\Admin_user',
    'form_width' => 500,

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'name' => array(
            'title' => 'Name',
        ),
        'email' => array(
            'title' => 'Email',
        ),
        'role' => array(
            'title' => 'Role',
        ),
        'created_at' => array(
            'title' => 'Date'
        )
    ),
    'rules' => array(
        'name' => 'required',
        'email' => 'required',
        'role' => 'required',
        'password' => 'required|min:6|confirmed',
//        'password_confirmation' => 'required|min:6'
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => 'Name',
        ),
        'email' => array(
            'title' => 'Email',
        ),
        'password' => array(
            'type' => 'password',
            'title' => 'Password',
        ),
        'password_confirmation' => array(
            'type' => 'password',
            'title' => 'Confirm password',
            'setter' => true
        ),
        'role' => array(
            'title' => 'Super Admin',
            'type' => 'enum',
            'options' => array(
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'developer' => 'Developer'
            ),
        )
    ),
    'filters' => array(
        'name' => array(
            'title' => 'Name',
        ),
        'email' => array(
            'title' => 'Email',
        ),
        'role' => array(
            'title' => 'Super Admin',
            'type' => 'enum',
            'options' => array(
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'developer' => 'Developer'
            ),
        ),
        'created_at' => array(
            'type' => 'datetime',
            'title' => 'Date',
        ),
    ),
    'action_permissions'=> array(
        'delete' => function($model){
                return Auth::user()->role == 'super_admin';
        },
        'edit' => function($model){
                return Auth::user()->role == 'super_admin';
        },
        'update' => function($model){
                return Auth::user()->role == 'super_admin';
        },
        'create' => function($model){
                return Auth::user()->role == 'super_admin';
        }
    ),
    'permission' => function() {
            return Auth::user()->role == 'super_admin';
     }
);