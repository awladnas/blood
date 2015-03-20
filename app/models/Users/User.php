<?php namespace LifeLi\models\Users;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Support\Facades\Validator;

class User extends \Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
    /**
     * @var array
     */
    protected $fillable = array('mobile_no', 'email', 'valid_until', 'api_token' );
    /**
     * @var int
     */
    protected $token_expire_days = 10;

    protected static $rules = [
        'mobile_no' => 'required|min:3|max:50|unique:users',
        'email'     => 'required|between:3,64|email',
        'password'  =>'required|min:6',
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    protected $db_to_array = array(
        'id'             => 'id',
        'mobile_no'      => 'mobile',
        'email'          =>  'email',
        'is_active'      => 'is_active',
        'is_confirm'     => 'is_confirm',
        'created_at'     => 'created_at'
    );
    /**
     * @var array
     */
    protected $array_to_db = array(
        'id'             => 'id',
        'mobile'         => 'mobile_no',
        'password'         => 'password',
        'email'          =>  'email',
        'is_active'      => 'is_active',
        'is_confirm'     => 'is_confirm',
        'created_at'     => 'created_at'
    );
    /**
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('LifeLi\models\Profiles\Profile', 'user_id', 'id');
    }

    /**
     * @return string
     */
    public function generate_token() {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, 30);
    }

    /**
     *
     */
    public function get_token_expired_date() {
        return Date('d-m-Y H:i:s', strtotime("+$this->token_expire_days days"));
    }

    /**
     * @param $arrInput
     * @return array
     */
    public function get_array_to_db($arrInput) {
        $arrOutput = array();
        foreach($arrInput as $key => $val) {
            if(array_key_exists($key, $this->array_to_db)){
                $arrOutput[$this->array_to_db[$key]] = $val;
            }
        }
        return $arrOutput;
    }

    public function validate($inputs, $action = 'update') {

        $rules = [
                'mobile_no' => 'Required|Min:3|Max:50|Unique:users',
                'email'     => 'Required|Between:3,64|Email',
                'password'  =>'Required|Min:6'

        ];
        $arr_rules = [];
        if ($action == 'update'){
            foreach($inputs as $k => $v){
                if(isset($rules[$k])){
                    $arr_rules[$k] =  $rules[$k];
                }
            }
        }
        else {
            $arr_rules =  $rules;
        }

        return Validator::make($inputs, $arr_rules);
    }

}
