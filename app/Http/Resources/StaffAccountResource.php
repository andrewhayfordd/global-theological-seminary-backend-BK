<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffAccountResource extends JsonResource
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
            "bank" => $this->bank_desc,
            "account" => $this->account_desc,
            "accountNo" => $this->account_no,
            "staff" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => "
            <button class='btn btn-sm btn-danger acc-delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
