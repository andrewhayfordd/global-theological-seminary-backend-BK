<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffQualResource extends JsonResource
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
            "qual" => $this->qualification,
            "inst" => $this->institution,
            "comp" => $this->comp_year,
            "staff" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => "
            <button class='btn btn-sm btn-danger qual-delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
