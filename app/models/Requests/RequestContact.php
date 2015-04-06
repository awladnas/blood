<?php namespace LifeLi\models\Requests;

class RequestContact extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();

    protected $table = 'users';

    public function request(){
        $this->belongsTo('LifeLi\models\Requests\Request');
    }
}
