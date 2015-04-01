<?php

  /**
   * Users model config
   */

  return array(

      'title' => 'Users',
      'single' => 'user',
      'model' => 'LifeLi\models\Users\User',

      'columns' => array(
          'id' => array(
              'title' => 'Id',
          ),
          'email' => array(
              'title' => 'Email',
          ),
          'mobile_no' => array(
              'title' => 'Mobile Number',
          ),
          'is_block' => array(
              'title' => 'Block',
          ),
          'created_at' => array(
              'type' => 'datetime',
              'title' => 'Date',
          ),

          'device_id' => array(
              'title' => 'Device Number',
          ),
          'out_of_req' => array(
              'relationship' => 'profile',
              'title' => 'Available',
              'select' => '(:table).out_of_req'
          ),
          'requests' => array(
              'title' => '#requests',
              'relationship' => 'requests',
              'select' => 'COUNT((:table).id)',
          ),
          'blood_group' => array(
              'relationship' => 'profile',
              'title' => 'Blood Group',
              'select' => '(:table).blood_group'
          )
      ),
      'rules' => array(
          'mobile_no' => 'required|between:5,64|unique:users',
          'email'     => 'required|between:3,64|email'
      ),
      'messages' => array(
          'mobile_no.required' => 'Mobile Number is required',
          'email.required' => 'User Email is required',
      ),
      'edit_fields' => array(
          'email' => array(
              'title' => 'Email',
              'type' => 'text',
          ),
          'mobile_no' => array(
              'title' => 'Mobile No',
              'type' => 'text',
          ),
          'created_at' => array(
              'type' => 'datetime',
              'title' => 'Date',
          ),

      ),
      'filters' => array(
			'mobile_no' => array(
				'title' => 'Mobile',
				'type' => 'text'
			),
			'email' => array(
				'title' => 'Email',
				'type' => 'text'
			),
          'out_of_req' => array(
              'title' => 'Out of Request',
              'type'  => 'bool'
          ),
          'created_at' => array(
              'type' => 'datetime',
              'title' => 'Date',
          )
      ),
      'actions' => array(
          'block' => array(
              'title' => function($model){
                      return $model->is_block ? 'UnBlock' : 'Block';
                  },
              'messages' => array(
                  'active' => function($model){
                          return ($model->is_block ? 'UnBlocking ' : 'Blocking ') . $model->email . '...';
                      },
                  'success' => function($model){
                          return $model->email . ($model->is_block ? ' UnBlocked!' : ' Blocked!');
                      },
                  'error' => function($model) {
                          return "There was an error while " . ($model->is_block ? 'UnBlocking  ' : 'Blocking ') . $model->email;
                      },
              ),
              'confirmation' => function($model) {
                      return "Are you sure you want to " . ($model->is_block ? 'UnBlock ' : 'Block ') . $model->email . '?';
                  },
              'action' => function($model) {
                      $model->is_block = $model->is_block ? false : true;
                      return $model->save();
                  }
          ),

      ),
      'global_actions' => array(
          'download_csv' => array(
              'title' => 'Export CSV',
              'messages' => array(
                  'active' => 'Creating the CSV...',
                  'success' => 'CSV created! Downloading now...',
                  'error' => 'There was an error while creating the CSV',
              ),
              'action' => function($query)
                  {
                     return \LifeLi\models\Users\User::get_csv();
                  }
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