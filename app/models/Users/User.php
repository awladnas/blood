<?php namespace LifeLi\models\Users;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Support\Facades\Validator;
use LifeLi\models\Requests\Request;

class User extends \Eloquent {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
    /**
     * @var array
     */
    protected $fillable = array('mobile_no', 'email', 'valid_until', 'api_token', 'password', 'device_id' );
    /**
     * @var int
     */
    protected $token_expire_days = 10;

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
        'password'       => 'password',
        'email'          =>  'email',
        'device_id'      =>  'device_id',
        'is_active'      => 'is_active',
        'is_confirm'     => 'is_confirm',
        'created_at'     => 'created_at'
    );
    /**
     * @var array
     */
//    protected $hidden = array('password', 'remember_token');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('LifeLi\models\Profiles\Profile', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests(){
        return $this->hasMany('LifeLi\models\Requests\Request', 'user_id', 'id');
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
        return Date('Y-m-d H:i:s', strtotime("+$this->token_expire_days days"));
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

    /**
     * @param $inputs
     * @param string $action
     * @return mixed
     */
    public function validate($inputs, $action = 'update') {

        $rules = [
                'mobile_no' => 'Required|Min:3|Max:50|Unique:users',
                'email'     => 'Required|Between:3,64|Email',
                'password'  =>'Required|Min:6',
                'device_id'  =>'Required|Min:2'

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

    public static function get_csv(){
        $table = User::all();
        $filename = "users.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('id', 'email', 'mobile'));

        foreach($table as $row) {
            fputcsv($handle, array($row['id'], $row['email'], $row['mobile_no']));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'user2.csv', $headers);
    }

}
