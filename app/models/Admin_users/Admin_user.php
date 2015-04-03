<?php namespace LifeLi\models\Admin_users;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserTrait;

class Admin_user extends \Eloquent implements UserInterface,RemindableInterface {

use UserTrait, RemindableTrait;
    /**
     * @var array
     */
    protected $guarded = array();

    /**
     * @var array
     */
    public static $rules = array(
		'name' => 'required',
		'email' => 'required',
		'password' => 'required|min:6|confirmed',
	);

    protected $hidden = array('password');


    protected $table = 'admin_users';

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed|string
     */
    public function getRememberToken(){
        return $this->remember_token;
    }

    /**
     * @param string $value
     */
    public function setRememberToken($value){
        $this->remember_token = $value;
    }


    /**
     * @return string
     */
    public function getRememberTokenName(){
        return 'remember_token';
    }

    public function map_superuser()
    {
        return ($this->role == 'super_admin')? 'Yes' : 'No';
    }


    /**
     * @param array $options
     * @return bool|void
     */
    public function save(array $options = array())
    {
        if(isset($this->attributes['password'])) {
            if (\Hash::needsRehash($this->attributes['password'])) {
                $this->attributes['password'] = \Hash::make($this->attributes['password']);
            }
            unset($this->attributes['password_confirmation']);
        }
        return parent::save($options);
    }


    /**
     * @return bool
     */
    public function is_admin(){
        return ($this->role == 'admin')? true : false;
    }

    /**
     * @return bool
     */
    public function is_super_admin(){
        return ($this->role == 'super_admin')? true : false;
    }

    /**
     * @return bool
     */
    public function has_dashboard_access(){
        return ($this->role == 'admin' || $this->role == 'super_admin' )? true : false;
    }



}
