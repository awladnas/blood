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
     * @internal param $user_id
     * @return Response
     */
	public function index()
	{
        $profiles = Profile::all();
        $data = $this->fractal->collection($profiles, new ProfileTransformer());
        return $this->set_status(200, $data['data']);
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
     * @internal param $user_id
     * @return Response
     */
	public function store()
	{
        $profile = new Profile();
        $inputs = \Input::json();
        $arr_profile_data = $inputs->get('profile');
        if(!isset($arr_profile_data['user'])) {
            return $this->set_status(404);
        }
        $user = User::find($arr_profile_data['user']);
        if(!$user) {
            //user not found
            return $this->set_status(404);
        }
        if($user->profile) {
            //already exists
            return $this->set_status(404);
        }
        $arr_profile = $profile->get_array_to_db($arr_profile_data);

        $v = $profile->validate($arr_profile, 'create');
        if($v->passes()){
            //vaid data
            $profile = Profile::create($arr_profile);
            if($profile) {
                return $this->set_status(201, $this->fractal->item($profile, new ProfileTransformer()));
            }

        }
        else {
            //validation failed
            return $this->set_status(204, $v->errors());
        }
	}

    /**
     * Display the specified resource.
     * GET users/{id}/profiles/{id}
     *
     * @param $profile_id
     * @internal param $user_id
     * @internal param int $id
     * @return Response
     */
	public function show( $profile_id)
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
     * @param $profile_id
     * @internal param $user_id
     * @internal param int $id
     * @return Response
     */
	public function update($profile_id)
	{
        $profile = new Profile();
        $arr_inputs = \Input::json();
        $objProfile =  Profile::find($profile_id);

        if(!$objProfile) {
            return $this->set_status(404);
        }
        $arr_profile_data = $arr_inputs->get('profile');

        $arr_profile = $profile->get_array_to_db($arr_profile_data);

        $v = $profile->validate($arr_profile, 'update');
        if($v->passes()){
            $bln_update =  $objProfile->update($arr_profile);
            $profile = Profile::find($profile_id);
            if($bln_update) {
                //profile updated
                return $this->set_status(200, $this->fractal->item($profile, new ProfileTransformer()));
            }
        }
        else {
            //validation failed
            return $this->set_status(204, $v->errors());
        }
	}

    /**
     * Remove the specified resource from storage.
     * DELETE /profiles/{id}
     *
     * @param $profile_id
     * @internal param $user_id
     * @internal param int $id
     * @return Response
     */
	public function destroy($profile_id)
	{
        $profile =  Profile::find($profile_id);
        if($profile)
        {
            $profile->delete();
            return $this->set_status(200);
        }
        else {
            return $this->set_status(404);
        }
	}

}