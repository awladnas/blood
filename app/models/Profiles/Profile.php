<?php namespace LifeLi\models\Profiles;

class Profile extends \Eloquent {
	protected $fillable = [];
    protected $table = 'users_profile';

    public function user(){
        return $this->belongsTo('LifeLi\models\Users\User');
    }
}