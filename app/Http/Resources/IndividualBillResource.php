<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndividualBillResource extends JsonResource
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
            "semester" => $this->sem_code,
            "branch" => $this->branch_code,
            "itemcode" => $this->item,
            "bill" => $this->bill_desc,
            "program" => $this->prog_desc,
            "amount" => $this->amount,
            "student_no" => $this->student_no,
            "school_code" => $this->school_code,
            "action" => "
            <button href = '#' title='update bill item' class='rounded btn btn-outline-info btn-sm ind-edit-btn'> 
                <i class='fas fa-edit'></i> 
            </button>
            <button href = '#' title='Apply discount' class='rounded btn btn-outline-secondary btn-sm discount-btn'> 
                <i class='fas fa-percent'></i> 
            </button>
            <button href = '#' class='rounded btn btn-outline-danger btn-sm ind-delete-btn'> 
                <i class='fas fa-trash'></i> 
            </button>
           
            ",
        ];
    }
}


