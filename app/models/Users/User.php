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
    protected $fillable = array('mobile_no', 'email', 'valid_until', 'api_token', 'password', 'device_id','name', 'zone', 'country', 'blood_group', 'created_at', 'updated_at', 'city', 'steps', 'is_complete', 'confirmation_code' );
    /**
     * @var int
     */
    protected $token_expire_days = 10;
    /**
     * @var int
     */
    protected $max_user_in_search = 20;

    /**
     * total profile steps
     */
    const TOTAL_STEPS = 3;

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
        'name'           => 'name',
        'zone'           =>  'zone',
        'country'        => 'country',
        'city'           => 'city',
        'blood_group'    => 'blood_group',
        'steps'          => 'steps',
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
     * @param int $length
     * @return string
     */
    public function generate_token( $length = 30) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
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
                'mobile_no' =>  'Required|Min:3|Max:50|Unique:users',
                'email'     =>  'Required|Between:3,64|Email',
                'password'  => 'Required|Min:6',
                'device_id' => 'Required|Min:2',
                'name'      => 'Min:3',
                'zone'      => 'Min:3',
                'country'   => 'Min:2',
                'city'      => 'Min:2',
                'steps'     => 'Required'

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
//            $rules = [
//                'mobile_no' =>  'Required|Min:3|Max:50|Unique:users',
//                'email'     =>  'Required|Between:3,64|Email',
//                'password'  =>  'Required|Min:6',
//                'device_id' =>  'Required|Min:2'
//                ];
            $arr_rules =  $rules;
        }
        return Validator::make($inputs, $arr_rules);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function get_csv() {

        $users = User::all();
        $path = public_path("downloads/");
        $filename = $path . "users.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('id', 'email', 'mobile', 'name', '#requests', 'city', 'block', 'date','complete', 'latitude', 'longitude'));

        foreach($users as $row) {
            fputcsv($handle, array($row['id'], $row['email'], $row['mobile_no'], $row['name'], $row->requests()->count(), $row['city'],$row['block'], $row['date'], $row['complete'], $row['latitude'],$row['longitude'] ));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return \Response::download($filename, 'user_data.csv', $headers);
    }

    /**
     * @param $user_id
     * @param $lat
     * @param $lng
     * @param int $max_distance
     * @param $blood_group
     * @param array $blocked_users
     * @internal param $profile_id
     * @return array|static[]
     */
    public function get_closest_profiles($user_id, $lat, $lng, $max_distance = 20, $blood_group, $blocked_users = array()){

        $gr_circle_radius = 6371; //km


        /*
         *  Generate the select field for distance
         */
        $disctance_select = sprintf(
            "*, ( %d * acos( cos( radians(%s) ) " .
            " * cos( radians( latitude ) ) " .
            " * cos( radians( longitude ) - radians(%s) ) " .
            " + sin( radians(%s) ) * sin( radians( latitude ) ) " .
            ") " .
            ") " .
            "AS distance",
            $gr_circle_radius,
            $lat,
            $lng,
            $lat
        );
        return $this
            ->select( \DB::raw($disctance_select) )
            ->having( 'distance', '<', $max_distance )
            ->take( $this->max_user_in_search )
            ->where('id', '!=', $user_id)
            ->where('out_of_req', '=', false)
            ->where('blood_group', '=', $blood_group)
            ->whereNotIn('id', $blocked_users)
            ->orderBy( 'distance', 'ASC' )
            ->get();
    }

    /**
     * @param $city
     * @return array
     */
    public function get_location_from_city($city) {
        return \Geocode::make()->address($city);
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

        if($this->city && !$this->latitude) {
            $response = $this->get_location_from_city($this->city);
            if($response) {
                $this->latitude = $response->latitude();
                $this->longitude = $response->longitude();
            }
        }
        return parent::save($options);
    }


}
