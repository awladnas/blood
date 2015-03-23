<?php namespace LifeLi\controllers;

use LifeLi\models\Requests\Request;
use LifeLi\models\Requests\RequestTransformer;
use LifeLi\models\Users\User;
use Sorskod\Larasponse\Larasponse;

class RequestsController extends BaseController {

    protected $fractal;

    public function __construct(Larasponse $fractal)
    {
        $this->fractal = $fractal;
    }

	/**
	 * Display a listing of the resource.
	 * GET /requests
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /requests/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /requests
	 *
	 * @return Response
	 */
	public function store()
	{
        $request = new Request();
        $arr_inputs = \Input::json();
        $arr_request_data = $arr_inputs->get('request');
//        $arr_user_data = $arr_inputs->get('users');
//        return var_dump($arr_user_data);
        $arr_request = $request->get_array_to_db($arr_request_data);
        $user = User::find($arr_request_data['user']);
        if(!$user) {
            //user not found
            return $this->set_status(404);
        }

        $v = $request->validate($arr_request, 'create');
        if($v->passes()){
            //valid data
            $request = Request::create($arr_request);
            if($request) {
                return $this->set_status(201, $this->fractal->item($request, new RequestTransformer()));
            }

        }
        else {
            //validation failed
            return $this->set_status(204, $v->errors());
        }
	}

	/**
	 * Display the specified resource.
	 * GET /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /requests/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /requests/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}