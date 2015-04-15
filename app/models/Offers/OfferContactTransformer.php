<?php

namespace LifeLi\models\Offers;


use League\Fractal\TransformerAbstract;

class OfferContactTransformer extends TransformerAbstract {
    /**
     * @param OfferContact $contact
     * @return mixed
     */
    public function transform(OfferContact $contact)
    {
        return  $contact->contact;
    }
} 