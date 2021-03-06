<?php
namespace LifeLi\models\Offers;
class Offer extends \Eloquent {
	protected $guarded = array();
    protected  $table = 'offers';

	public static $rules = array();

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'blood_group', 'created_at', 'updated_at', 'area', 'content'];


    /**
     * @var array
     */
    protected $array_to_db = array(
        'id'             => 'id',
        'user'           => 'user_id',
        'area'           => 'area',
        'content'        => 'content',
        'blood_group'    => 'blood_group',
        'contacts'       => 'contacts',
        'created_date'   => 'created_at',
        'updated_date'   => 'updated_at'
    );

    /**
     * @return mixed
     */
    public function user(){
        return $this->belongsTo('LifeLi\models\Users\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offer_users()
    {
        return $this->hasMany('LifeLi\models\Offers\OfferUser');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts(){
        return $this->morphMany('LifeLi\models\Offers\OfferContact', 'contactable');
    }


    public function validate($inputs, $action = 'update') {

        $rules = [
            'area'          => 'Required|Min:3',
            'zone'          => 'Min:3',
            'content'       => 'Required|Min:2',
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

        return \Validator::make($inputs, $arr_rules);
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
     * @param $user_id
     * @return bool
     */
    public function eligible_for_requests($user_id){
        $requests = $this->where('user_id', $user_id)->where('created_at', '>=',  date('Y-m-d H:i:s',time()-86400))->get();
        return count($requests) > 0 ? false : true;
    }
}
