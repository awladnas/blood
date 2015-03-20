<?php namespace LifeLi\models\Profiles;

use League\Fractal\TransformerAbstract;
class ProfileTransformer extends TransformerAbstract {

    public function transform(Profile $profile)
    {
        //dd($profile);
        return [
            'id'             => (int) $profile->id,
            'user_id'        => (int) $profile->user_id,
            'name'           =>  $profile->name,
            'country_name'   => $profile->country,
            'blood_group'    =>  $profile->blood_group,
            'zone'           => $profile->zone,
            'created_date'   => $profile->created_at,
            'updated_date'   => $profile->updated_at,
        ];
    }

} 