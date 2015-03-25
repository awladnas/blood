<?php namespace LifeLi\models\Block_users;

class BlockUser extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();
    /**
     * @var string
     */
    protected $table = 'block_users';
}
