<?php namespace LifeLi\models\Request_users;

class Request_user extends \Eloquent {
    /**
     * @var array
     */
    protected $guarded = array();
    /**
     * @var array
     */
    public static $rules = array();
    /**
     * @var string
     */
    protected $table = 'requested_users';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request(){
        return $this->belongsTo('LifeLi\models\Requests\Request');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(){
        return $this->belongsTo('LifeLi\models\RequestStatus\RequestStatus');
    }

    /*
     * @var array
     */
    public static $request_status = [
        'replied'   =>  1,
        'shared'    =>  2,
        'declined'  =>  3,
        'read'      =>  4,
        'unread'    =>  5,
        'ignored'   =>  6,
        'blocked'   =>  7
    ];
}
