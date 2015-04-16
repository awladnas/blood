<?php


namespace LifeLi\models\Offers;


use League\Fractal\TransformerAbstract;
use LifeLi\models\Offers\Offer;
use LifeLi\models\Offers\OfferUser;
use LifeLi\models\Requests\ContactTransformer;

class OfferTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'contacts'
    ];

    protected $availableIncludes = [
        'Response'
    ];

    public function transform(Offer $request)
    {
        return [
            'id'             => (int) $request->id,
            'user_id'        => (int) $request->user_id,
            'blood_group'    =>  $request->blood_group,
            'content'        =>  $request->content,
            'area'           =>  $request->area,
            'status'         =>  $request->status,
            'created_date'   =>  $request->created_at,
            'updated_date'   =>  $request->updated_at
        ];
    }

    /**
     * Include Response
     *
     * @param Request $request
     * @return \League\Fractal\Resource\Collection
     */
    public function includeResponse(Offer $request)
    {
        if($request->offer_users()) {
            $type = \Input::get('type');
            if(isset($type, OfferUser::$request_status[$type]) ) {
                $users_offers = $request->offer_users()->where('status_id', '=',OfferUser::$request_status[$type] )->get();
            }
            else {
                $users_offers = $request->offer_users()->get();
            }

            return $this->collection($users_offers, new OfferResponseTransformer());
        }
    }

    /**
     * @param Request $request
     * @return \League\Fractal\Resource\Collection
     */
    public function includeContacts(Offer $request){
        return $this->collection($request->contacts, new OfferContactTransformer());
    }
} 