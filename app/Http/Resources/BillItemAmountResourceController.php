<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BillItemAmountResourceController extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        //Fetching bill description
        $BillDesc = DB::table('tblbill_item')
            ->where('bill_code', $this->bill_code)
            ->where('deleted', 0)
            ->where('school_code', $this->school_code)
            ->first();

        return [
            "billDesc" => $BillDesc->bill_desc,
            "billCode" => $this->bill_code,
            "billAmount" => $this->amount,
            "billSemester" => $this->sem_code,
            "billSession" => $this->session_code,
            "billBatch" => $this->batch_code,
            "billLevel" => $this->level,
            "billProgram" => $this->prog_code,
            "billBranch" => $this->branch_code,
            "billTransid" => $this->transid,

            'action' => " <button type='button' rel='tooltip' title='Edit department'
                class='btn btn-success  btn-sm edit-btn'>
                <i class='fas fa-edit'></i>
            </button>
            
            <button type='button' rel='tooltip' title='Remove department'
                class='btn btn-danger btn-sm delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
                 "
        ];
    }
}
