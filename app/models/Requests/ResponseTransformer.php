<?php

namespace LifeLi\models\Request_users;


use League\Fractal\TransformerAbstract;
use LifeLi\models\RequestStatus\RequestStatusTransformer;


class ResponseTransformer extends TransformerAbstract {
    protected $defaultIncludes = [
        'status'
    ];

    public function transform(Request_user $request)
    {
        $arr_response = [
            'id'             => (int) $request->id,
            'receiver'       =>  $request->receiver,
            'created_date'   =>  $request->created_at,
            'updated_date'   =>  $request->updated_at,
        ];

       if($request->status()->first()->status == 'replied'){
            $arr_response['contacts'] = $request->contacts()->lists('contact');
        }
        return $arr_response;
    }

    /**
     * Include Status
     *
     * @param \LifeLi\models\Request_users\Request|\LifeLi\models\Request_users\Request_user $request
     * @return \League\Fractal\Resource\Item
     */
    public function includeStatus(Request_user $request)
    {
        if($request->status()) {
            $status = $request->status()->getResults();
            return $this->item($status, new RequestStatusTransformer());
        }
    }

} 