<?php namespace LifeLi\models\Users;

use League\Fractal\TransformerAbstract;
use LifeLi\models\Profiles\ProfileTransformer;

class UserTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'profile'
    ];

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
            'device_id'      => $user->device_id
        ];
    }

    /**
     * Include Profile
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeProfile(User $user)
    {
        if($user->profile) {
            $profile = $user->profile;
            return $this->item($profile, new ProfileTransformer());
        }
    }

} 