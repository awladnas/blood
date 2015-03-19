<?php namespace LifeLi\models\Users;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $token_expire_days = 10;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


    public function profile()
    {
        return $this->hasOne('Profile');
    }

    public function generate_token() {

        return "token";
    }

    /**
     *
     */
    public function get_token_expired_date() {
        return date_add(date('y-m-d'), $this->token_expire_days());
    }
}
