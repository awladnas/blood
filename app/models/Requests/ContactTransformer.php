<?php
/**
 * Created by PhpStorm.
 * User: awlad
 * Date: 4/6/15
 * Time: 11:24 AM
 */

namespace LifeLi\models\Requests;


use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract{

    public function transform(RequestContact $contact)
    {
        return [
            'contact'    =>  $contact->contact,
        ];
    }
} 