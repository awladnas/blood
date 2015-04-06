<?php namespace LifeLi\models\Requests;

class RequestContact extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();

    protected $table = 'requests_contacts';


    public function contactable(){
        return $this->morphTo();
    }
}
