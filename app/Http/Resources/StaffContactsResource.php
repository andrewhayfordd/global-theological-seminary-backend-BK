<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffContactsResource extends JsonResource
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
            "phone" => $this->phone,
            "email" => $this->email,
            "rel" => $this->rel_desc,
            "name" => $this->name,
            "staff" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => "
            <button class='btn btn-sm btn-danger con-delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
