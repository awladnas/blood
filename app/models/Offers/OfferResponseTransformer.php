<?php


namespace LifeLi\models\Offers;


use League\Fractal\TransformerAbstract;
use LifeLi\models\RequestStatus\RequestStatusTransformer;

class OfferResponseTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'status'
    ];

    /**
     * @param OfferUser $request
     * @return array
     */
    public function transform(OfferUser $request)
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
     * @param \LifeLi\models\Offers\OfferUser|\LifeLi\models\Request_users\Request|\LifeLi\models\Request_users\Request_user $request
     * @return \League\Fractal\Resource\Item
     */
    public function includeStatus(OfferUser $request)
    {
        if($request->status()) {
            $status = $request->status()->getResults();
            return $this->item($status, new RequestStatusTransformer());
        }
    }

} 