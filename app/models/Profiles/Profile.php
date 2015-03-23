<?php namespace LifeLi\models\Profiles;
use Illuminate\Support\Facades\Validator;
class Profile extends \Eloquent {

    /**
     * @var array
     */
    protected $array_to_db = array(
        'id'             => 'id',
        'user'           => 'user_id',
        'name'           => 'name',
        'zone'           =>  'zone',
        'country'        => 'country',
        'city'           => 'city',
        'blood_group'    => 'blood_group',
        'created_date'   => 'created_at',
        'updated_date'   => 'updated_at'
    );

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'name', 'zone', 'country', 'blood_group', 'created_at', 'updated_at', 'city'];
    /**
     * @var string
     */
    protected $table = 'users_profile';

    protected $max_user_in_search = 20;
    /**
     * @return mixed
     */
    public function user(){
        return $this->belongsTo('LifeLi\models\Users\User');
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
            'user_id'       => 'Required',
            'name'          => 'Required|Min:3',
            'zone'          => 'Min:3',
            'country'       => 'Required|Min:2',
            'city'          => 'Required|Min:2',
            'blood_group'   => 'Required'
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

    public function get_closest_profiles($profile_id, $lat, $lng, $max_distance = 20, $blood_group){

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
            ->where('id', '!=', $profile_id)
            ->where('blood_group', '=', $blood_group)
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

    public function save(array $options = array())
    {
        if($this->city && !$this->latitude) {
            $response = $this->get_location_from_city($this->city);
            if($response) {
                $this->latitude = $response->latitude();
                $this->longitude = $response->longitude();
            }
        }
        parent::save($options);
        // after save code
    }

}