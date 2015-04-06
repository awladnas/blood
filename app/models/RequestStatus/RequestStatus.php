<?php

namespace LifeLi\models\RequestStatus;

class RequestStatus extends \Eloquent {
    protected $table = 'request_status';

	protected $guarded = array();

	public static $rules = array();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function request_user(){
        return $this->hasOne('LifeLi\models\Request_users\Request_user');
    }

}
