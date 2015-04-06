<?php namespace LifeLi\models\Requests;

use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use LifeLi\models\Request_users\Request_user;
use LifeLi\models\Request_users\ResponseTransformer;

class RequestTransformer extends TransformerAbstract {



    protected $defaultIncludes = [
        'contacts'
    ];

    protected $availableIncludes = [
        'Response'
    ];

    public function transform(Request $request)
    {
        return [
            'id'             => (int) $request->id,
            'user_id'        => (int) $request->user_id,
            'blood_group'    =>  $request->blood_group,
            'request_type'   =>  $request->request_type,
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
    public function includeResponse(Request $request)
    {
        if($request->Request_users()) {
            $type = Input::get('type');
            if(isset($type, Request_user::$request_status[$type]) ) {
                $users_requests = $request->Request_users()->where('status_id', '=',Request_user::$request_status[$type] )->get();
            }
            else {
                $users_requests = $request->Request_users()->get();
            }

            return $this->collection($users_requests, new ResponseTransformer());
        }
    }

    /**
     * @param Request $request
     * @return \League\Fractal\Resource\Collection
     */
    public function includeContacts(Request $request){
        return $this->collection($request->contacts()->get(), new ContactTransformer());
    }

} 