<?php namespace LifeLi\models\Users;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {


    public function transform(User $user)
    {
        return [
            'id'             => (int) $user->id,
            'mobile'         => $user->mobile_no,
            'email'          => $user->email,
            'is_active'      => $user->is_active,
            'is_confirm'     => $user->is_confirm,
            'created_at'     => $user->created_at,
            'token_expires'  => $user->valid_until,
            'api_token'      => $user->api_token,
            'device_id'      => $user->device_id,
            'name'           => $user->name,
            'zone'           => $user->zone,
            'country'        => $user->country,
            'city'           => $user->city,
            'blood_group'    => $user->blood_group,
            'steps'          => $user->steps,
            'is_complete'    => $user->is_complete,
            'out_of_req'     => $user->out_of_req
        ];
    }
} 