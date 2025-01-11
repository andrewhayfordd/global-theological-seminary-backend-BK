<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenditureResource extends JsonResource
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
            "id" => $this->id,
            "item" => $this->name,
            "rawYear" => $this->acyear,
            "acyear" => $this->acyear . " / " . $this->acterm,
            "acterm" => $this->acterm,
            "trans_type" => $this->trans_type,
            "bank" => $this->bank,
            "branch" => $this->branch,
            "bank_branch" => $this->bank_branch,
            "branch_desc" => $this->branch_desc,
            "payer" => $this->payer,
            "exp_type" => $this->exp_cat,
            "cheque_no" => $this->cheque_no,
            "cheque_bank" => $this->cheque_bank,
            "account_no" => $this->account_no,
            "holder" => $this->account_holder,
            "notes" => $this->notes,
            "amount" => "GHS " . $this->amount,
            "raw" => $this->amount,
            "date" => date("jS M Y",strtotime($this->trans_date)),
            "trans_date" => $this->trans_date,
            "action" => <<<EOT
            <button class='btn btn-sm btn-outline-info rounded exp-edit-btn' 
            title='expense details'>
            <i class='fas fa-edit'></i>
            </button>
            <button class='btn btn-sm btn-outline-danger rounded exp-delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
            EOT
        ];
    }
}
