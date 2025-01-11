<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillResourceController extends JsonResource
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
            "transid" => $this->transid,
            "billDesc" => $this->bill_desc,
            "billCode" => $this->bill_code,
            "amount" => $this->amount,
            "prog" => $this->prog_code,
            "sem" => $this->sem_code,
            "batch" => $this->batch_code,
            "branch" => $this->branch_code,
            'action' => " 
            <button type='button' rel='tooltip' title='Edit bill'
                class='btn btn-success  btn-sm edit-btn'>
                <i class='fas fa-edit'></i>
            </button>
            <button type='button' rel='tooltip' title='Delete bill'
                class='btn btn-danger btn-sm delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
                 "
        ];
    }
}