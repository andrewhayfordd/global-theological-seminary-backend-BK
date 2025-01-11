<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentBillResourceController extends JsonResource
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

            "studentAmount" => $this->amount,
            "studentCode" => $this->student_no,
            "billCode" => $this->item,
            "billtransid" => $this->transid,
            "acYear" => $this->acyear,
            "acTerm" => $this->acterm,
            "billName" => $this->bill_desc,
            'action' => " <button type='button' rel='tooltip' title='Edit student bill'
                class='btn btn-success  btn-sm edit-btn'>
                <i class='fas fa-edit'></i>
            </button>
            
            <button type='button' rel='tooltip' title='Delete student bill'
                class='btn btn-danger btn-sm delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
              "
        ];
    }
}
