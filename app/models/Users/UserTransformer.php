<?php namespace LifeLi\models\Users;

use League\Fractal\TransformerAbstract;
use LifeLi\models\Profiles\ProfileTransformer;

class UserTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'profile'
    ];

    protected $db_to_array = array(
        'id'             => 'id',
        'mobile_no'      => 'mobile',
        'email'          =>  'email',
        'is_active'      => 'is_active',
        'is_confirm'     => 'is_confirm',
        'created_at'     => 'created_at'
    );
    protected $array_to_db = array(
        'id'             => 'id',
        'mobile'         => 'mobile_no',
        'email'          =>  'email',
        'is_active'      => 'is_active',
        'is_confirm'     => 'is_confirm',
        'created_at'     => 'created_at'
    );

    public function transform(User $user)
    {
        return [
            'id'             => (int) $user->id,
            'mobile'         => $user->mobile_no,
            'email'          =>  $user->email,
            'is_active'      => $user->is_active,
            'is_confirm'     => $user->is_confirm,
            'created_at'     => $user->created_at,
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
        $profile = $user->profile;
        return $this->item($profile, new ProfileTransformer());
    }

} 