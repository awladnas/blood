<?php
/**
 * Created by PhpStorm.
 * User: awlad
 * Date: 3/19/15
 * Time: 4:21 PM
 */

namespace LifeLi\models\Profiles;

use League\Fractal\TransformerAbstract;
class ProfileTransformer extends TransformerAbstract {

    public function transform(Profile $profile)
    {
        return [
            'id'             => (int) $profile->id,
            'user_id'        => (int) $profile->user_id,
            'country_name'   => $profile->country,
            'blood_group'    =>  $profile->blood_group,
            'zone'           => $profile->zone,
        ];
    }

} 