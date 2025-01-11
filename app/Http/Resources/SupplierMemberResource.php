<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class SupplierMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        Log::info("$this->supplier");
        return [
            'transid' => $this->transid,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'full_name' => $this->fullName(),
            'supplier_code' => $this->supplier_code,
            'phone' => $this->phone,
            'position' => $this->position_code,
            'position_desc' => $this->position?->position_desc,
            'supplier_name' => $this->supplier?->name ,
            'supplier_phone' => $this->supplier?->phone,
            'action' => <<<TXT
            <button class='btn btn-sm btn-info info-btn '><i class='fa fa-info'></i> </button>
            <button class='btn btn-sm btn-success update-btn'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
            TXT,
        ];
    }
}
