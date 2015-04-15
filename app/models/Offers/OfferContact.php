<?php
namespace LifeLi\models\Offers;

class OfferContact extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();
    protected $table = 'offers_contacts';

    /**
     * @return mixed
     */
    public function contactable(){
        return $this->morphTo();
    }
}
