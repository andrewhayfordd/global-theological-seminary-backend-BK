<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReqCatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "code" => $this->item_code,
            "desc" => $this->item_desc,
            "action" => "
            <button class='btn btn-sm btn-outline-success rounded edit-req-btn'>
                <i class='fas fa-edit'></i>
            </button>
            <button class='btn btn-sm btn-outline-danger rounded delete-req-btn'>
                <i class='fas fa-trash'></i>
            </button>
            "
        ];
    }
}
