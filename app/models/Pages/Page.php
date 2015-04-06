<?php

namespace LifeLi\models\Pages;

class Page extends \Eloquent {
	protected $guarded = array();

	public static $rules = array();

    protected $table =  'settings';
    protected $fillable = ['id', 'about'];
}
