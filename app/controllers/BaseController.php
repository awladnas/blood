<?php namespace LifeLi\controllers;

class BaseController extends \Controller {

    protected $arr_status = array(
        '200' => 'success',
        '201' => 'created',
        '401' => 'unauthorized',
        '404' => 'not found'
    );
    protected $arr_valid = [
      200, 201
    ];
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

    public function set_status($status, $data = array())
    {
        $arr_response = array(
            'status' => 500 ,
            'message' => 'Some error Occur!'
        );

        if (isset($this->arr_status[$status])) {
            $arr_response['status'] = $status;
            $arr_response['message'] = $this->arr_status[$status];
            $arr_response['data'] = $data;
        }
        if(in_array($status, $this->arr_valid)) {
            $arr_response['error'] = false;
        }
        else {
            $arr_response['error'] = true;
        }

        return $arr_response;
    }


}
