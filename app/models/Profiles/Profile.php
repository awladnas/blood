<?php namespace LifeLi\models\Profiles;

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
        'blood_group'     => 'blood_group',
        'created_date'   => 'created_at',
        'created_date'   => 'updated_at'
    );

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'name', 'zone', 'country', 'blood_group', 'created_at', 'updated_at'];
    /**
     * @var string
     */
    protected $table = 'users_profile';

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
}