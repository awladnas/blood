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
}
