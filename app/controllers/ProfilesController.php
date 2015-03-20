<?php namespace LifeLi\controllers;
use LifeLi\models\Profiles\Profile;
use LifeLi\models\Profiles\ProfileTransformer;
use LifeLi\models\Users\User;
use Sorskod\Larasponse\Larasponse;

class ProfilesController extends BaseController {

    protected $fractal;

    public function __construct(Larasponse $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * Display a listing of the resource.
     * GET /profiles
     *
     * @param $user_id
     * @return Response
     */
	public function index($user_id)
	{
        $user = User::find($user_id);
        if (!$user)
        {
            $this->set_status('404');
        }
        if(!$user->profile){
            return $this->set_status(200);
        }
        return $this->set_status(200, $this->fractal->item($user->profile, new ProfileTransformer()));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /profiles/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

    /**
     * Store a newly created resource in storage.
     * POST /profiles
     *
     * @param $user_id
     * @return Response
     */
	public function store($user_id)
	{
        $profile = new Profile();
        $user = User::find($user_id);
        if(!$user) {
            return $this->set_status(404);
        }
        if($user->profile) {
            //already exists
            return $this->set_status(404);
        }
        $arr_profile_data = \Input::json('profile');
        $arr_profile = $profile->get_array_to_db($arr_profile_data);
        $profile = $user->profile()->create($arr_profile);
        if($profile) {
            return $this->set_status(201, $this->fractal->item($profile, new ProfileTransformer()));
        }
        return $this->set_status(500);
	}

    /**
     * Display the specified resource.
     * GET users/{id}/profiles/{id}
     *
     * @param $user_id
     * @param $profile_id
     * @internal param int $id
     * @return Response
     */
	public function show($user_id, $profile_id)
	{
        $user = Profile::find($profile_id);
        if (!$user)
        {
            $this->set_status('404');
        }

        return $this->set_status(200, $this->fractal->item($user, new ProfileTransformer()));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /profiles/{id}/edit
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
     * PUT /profiles/{id}
     *
     * @param $user_id
     * @param $profile_id
     * @internal param int $id
     * @return Response
     */
	public function update($user_id, $profile_id)
	{
        $profile = new Profile();
        $arr_inputs = \Input::json();
        $user =  User::find($user_id);
        if(!$user || !$user->profile) {
            return $this->set_status(404);
        }
        $arr_profile_data = $arr_inputs->get('profile');
        $arr_profile = $profile->get_array_to_db($arr_profile_data);
        $bln_update =  $user->profile()->update($arr_profile);
        $profile = Profile::find($profile_id);
        if($bln_update) {
            return $this->set_status(200, $this->fractal->item($profile, new ProfileTransformer()));
        }
        return $this->set_status(500);
	}

    /**
     * Remove the specified resource from storage.
     * DELETE /profiles/{id}
     *
     * @param $user_id
     * @param $profile_id
     * @internal param int $id
     * @return Response
     */
	public function destroy($user_id, $profile_id)
	{
        $user =  User::find($user_id);
        if($user)
        {
            if($user->profile){
                $user->profile->delete();
                return $this->set_status(200);
            }
            else {
                return $this->set_status(404);
            }

        }
	}

}