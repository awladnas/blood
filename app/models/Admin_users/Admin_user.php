<?php namespace LifeLi\models\Admin_users;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Admin_user extends \Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		'email' => 'required',
		'password' => 'required',
		'is_superuser' => 'required'
	);


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
}
