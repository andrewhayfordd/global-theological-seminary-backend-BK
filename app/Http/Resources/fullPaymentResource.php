<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class fullPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $bill = DB::table("vtblbill_total")->where("student_no", $this->student_no)
            ->where("school_code", $this->school_code)
            ->sum("total_bill");

        $paid = DB::table("vtbloverall_total")->where("student_no", $this->student_no)
            ->where("school_code", $this->school_code)
            ->first();

        if (empty($paid)) {
            $newTotal = 0;
        } else {
            $newTotal = $paid->total_paid;
        }

        return [
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
            "amount" => $this->total_paid,
            "balance" => (int)$bill - (int)$newTotal,
        ];
    }
}
