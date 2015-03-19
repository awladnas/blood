<?php namespace LifeLi\controllers;

class BaseController extends \Controller {

    protected $arr_status = array(
        '401' => 'UnAuthorized'
    );
        /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public function set_status($status)
    {
        $arr_response = array(
            'status' => 500 ,
            'message' => 'Some error Occur!'
        );
        if(in_array($status, $this->arr_status)) {
            $arr_response['status'] = $status;
            $arr_response['message'] = $this->arr_status[$status];
        }

    }


}
