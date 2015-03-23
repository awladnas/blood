<?php namespace LifeLi\models\Documents;

class Document extends \Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'url' => 'required',
		'input_format' => 'required',
		'output_format' => 'required',
		'api_version' => 'required',
        'request_method' => 'required',
        'description' => 'required'
	);
}
