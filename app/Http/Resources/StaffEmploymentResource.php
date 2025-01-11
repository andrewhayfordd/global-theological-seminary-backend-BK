<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffEmploymentResource extends JsonResource
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
            "id" => $this->transid,
            "type" => $this->emptype_desc,
            "date" => date("jS F Y", strtotime($this->date_employed)),
            "position" => $this->position,
            "staff" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => "
            <button class='btn btn-sm btn-danger emp-delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
