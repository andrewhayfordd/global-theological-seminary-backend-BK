<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DebtorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $total = DB::table("vtbloverall_total")->where("student_no", $this['student_no'])
            ->where("school_code", $this['school_code'])
            ->first();

        if (empty($total)) {
            $newTotal = 0;
        } else {
            $newTotal = $total->total_paid;
        }

        $amount = DB::table("tblbills")
            ->where("student_no", "=", $this['student_no'])
            ->where("deleted", 0)->sum("amount");
            
        $balance = (int)$amount - (int)$newTotal;

        return [
            "student" => "{$this['fname']} {$this['mname']} {$this['lname']}",
            "amount" => $this['total_paid'],
            "balance" => $balance,
        ];
    }
}
