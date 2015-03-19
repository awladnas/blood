<?php namespace LifeLi\models\Users;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        return [
            'user' => [
                'id'             => (int) $user->id,
                'mobile_no'      => $user->mobile_no,
                'email'          =>  $user->email,
                'is_active'      => $user->is_active,
                'is_confirm'     => $user->is_confirm,
                'created_at'     => $user->created_at
            ]
        ];
    }

} 