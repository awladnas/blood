<?php
namespace LifeLi\models\Offers;
class OfferUser extends \Eloquent {

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
    protected $table = 'offers_users';

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer(){
        return $this->belongsTo('LifeLi\models\Offers\Offer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(){
        return $this->belongsTo('LifeLi\models\RequestStatus\RequestStatus');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contacts(){
        return $this->morphMany('LifeLi\models\Offers\OfferContact', 'contactable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('LifeLi\models\Users\User','receiver', 'id');
    }

}
