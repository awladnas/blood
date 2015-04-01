<?php

  /**
   * Users model config
   */

  return array(

      'title' => 'Users',
      'single' => 'user',
      'model' => 'LifeLi\models\Users\User',

      'columns' => array(
          'email' => array(
              'title' => 'Email',
          ),
          'mobile_no' => array(
              'title' => 'Mobile Number',
          ),
          'created_at' => array(
              'title' => 'Date',
          ),
          'device_id' => array(
              'title' => 'Device Number',
          ),
          'out_of_req' => array(
              'title' => 'Out of Request',
          ),
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
      )
  );