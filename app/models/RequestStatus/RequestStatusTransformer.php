<?php

namespace LifeLi\models\RequestStatus;


use League\Fractal\TransformerAbstract;

class RequestStatusTransformer extends TransformerAbstract {

    public function transform(RequestStatus $status)
    {
        return [
            'status'  =>  $status->status
        ];
    }
} 