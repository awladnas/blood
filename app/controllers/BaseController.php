<?php namespace LifeLi\controllers;

class BaseController extends \Controller {

    protected $arr_status = array(
        '200' => 'success',
        '201' => 'created',
        '204' => 'validation failed',
        '400' => 'bad request',
        '401' => 'unauthorized',
        '403' => 'forbidden',
        '404' => 'not found',
        '405' => 'method not allowed',
        '500' => 'internal server error',
        '503' => 'service unavailable',
        '409' => 'already exists',
        '208' => 'Already Reported'
    );
    /**
     * @var array
     */
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
            $arr_response['data'] = isset($data['data']) ? $data['data'] : $data ;
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
