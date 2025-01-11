<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class messageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "academicyear"=>$this->academic_year,
            "messagedetails"=>$this->sms_details,
            "action" => "
            <button class='btn btn-sm btn-danger qual-delete-btn'><i class='fas fa-trash'></i></button>
        "
            
        ];
    }
}
