<?php namespace LifeLi\models\Profiles;

use League\Fractal\TransformerAbstract;
class ProfileTransformer extends TransformerAbstract {

    public function transform(Profile $profile)
    {
        return [
            'id'             => (int) $profile->id,
            'user'           => (int) $profile->user_id,
            'name'           => $profile->name,
            'country'        => $profile->country,
            'blood_group'    => $profile->blood_group,
            'out_of_req'     => (boolean)$profile->out_of_req,
            'zone'           => $profile->zone,
            'steps'          => $profile->steps,
            'is_complete'    => (boolean)$profile->is_complete,
            'created_date'   => $profile->created_at,
            'updated_date'   => $profile->updated_at,
        ];
    }

} 