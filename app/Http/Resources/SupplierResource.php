<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'supplier_code' => $this->supplier_code,
            'transid' => $this->transid,
            'name' => $this->name,
            'address' => $this->address ?: 'N/A',
            'address_limit' =>  $this->address ? Str::of($this->address)->limit(30, "...") : 'N/A',
            'phone' => $this->phone,
            'email' => $this->email ?: 'N/A',
            'members' => $this->members_count,
            'action' => <<<TXT
            <button class='btn btn-sm btn-info info-btn '><i class='fa fa-info'></i> </button>
            <button class='btn btn-sm btn-success update-btn'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
            TXT,
        ];
    }
}
