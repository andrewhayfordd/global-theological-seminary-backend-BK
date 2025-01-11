<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class dailyPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalPaid = DB::table("vtbloverall_total")
            ->where("student_no", "=", $this->student_no)
            // ->where("semester", $this->sem_desc)
            ->sum("total_paid");
        $amount = DB::table("tblbills")
            ->where("student_no", "=", $this->student_no)
            // ->where("sem_code", $this->semester)
            // ->where("branch_code", $this->branch)
            ->where("deleted", 0)->sum("amount");
        $balance = $amount - $totalPaid;
        // if ($balance < 0) {
        //     $balance = 0;
        // } else if ($totalPaid >= $this->debit) {
        //     $balance = $this->debit - $totalPaid;
        // } else {
        //     $balance = $this->debit - $this->credit;
        // }
        return [
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
            "semester" => $this->sem_desc,
            "bill" => $this->debit,
            "amtpaid" => $this->credit,
            "arrears" => round($balance, 2),
        ];
    }
}
