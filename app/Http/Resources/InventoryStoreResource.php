<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryStoreResource extends JsonResource
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
            'item_code' => $this->item->item_desc,
            'item_name' => $this->item_code,
            'item_quantity' => $this->item_quantity,
            'supply_date' => $this->supply_date,
            'action' => <<<TXT
            <button class='btn btn-sm btn-info info-btn '><i class='fa fa-info'></i> </button>
            <button class='btn btn-sm btn-success update-btn'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
            TXT,
        ];
    }
}
