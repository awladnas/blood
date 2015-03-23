<?php namespace LifeLi\models\Requests;

use League\Fractal\TransformerAbstract;
class RequestTransformer extends TransformerAbstract {

    public function transform(Request $request)
    {
        return [
            'id'             => (int) $request->id,
            'user_id'        => (int) $request->user_id,
            'blood_group'    =>  $request->blood_group,
            'request_type'   =>  $request->request_type,
            'content'        =>  $request->content,
            'area'           =>  $request->zone,
            'status'         =>  $request->status,
            'created_date'   =>  $request->created_at,
            'updated_date'   =>  $request->updated_at
        ];
    }

} 