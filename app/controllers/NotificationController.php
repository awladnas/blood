<?php namespace LifeLi\controllers;
use LifeLi\models\UserNotification\NotificationTransformer;
use LifeLi\models\Users\User;
use Sorskod\Larasponse\Larasponse;

class NotificationController extends BaseController {
    protected $fractal;

    public function __construct(Larasponse $fractal)
    {
        $this->fractal = $fractal;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('notifications.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('notifications.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $user_id
	 * @return Response
	 */
	public function show($user_id)
	{
        $user = User::find($user_id);
        if (!$user)
        {
            return $this->set_status('404', array('user not found'));
        }

        $notifications = $user->notifications()->get();
        return $this->set_status(200, $this->fractal->collection($notifications, new NotificationTransformer()));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('notifications.edit');
	}

	/**
	 * Update the specified resource in storage.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
