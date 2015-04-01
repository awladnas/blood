<?php
namespace LifeLi\models\UserNotification;
class UserNotification extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();
    /**
     * @var string
     */
    protected $table = 'users_notifications';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('LifeLi\models\Users\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver_user(){
        return $this->belongsTo('LifeLi\models\Users\User','request_user_id', 'id');
    }
}
