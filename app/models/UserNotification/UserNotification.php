<?php
namespace LifeLi\models\UserNotification;
class UserNotification extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();
    /**
     * @var string
     */
    protected $table = 'users_notifications';
}
