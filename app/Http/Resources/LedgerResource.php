<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class LedgerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (empty($this->cheque_bank)) {
            $trans = $this->network  . "/" . $this->phone_number;
        } else if (empty($this->network)) {
            $trans = $this->cheque_bank  . "/" . $this->cheque_no;
        } else {
            $trans = "N/A";
        }

        $debt = DB::table("tblledger_student")->where("type", "b")
            ->where("student_no", $this->student_no)
            ->where("deleted", 0)
            ->sum("debit");
        return [
            "date" => date("jS M Y", strtotime($this->payment_date)),
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
            "amount" => $this->credit,
            "cheque" => $trans,
            "studentNo" => $this->student_no,
            "semester" => $this->sem_desc,
            // "prog" => $this->prog_desc,
            "cheque" => $trans,
            "debit" => $this->debit,
            "credit" => $this->credit,
            "balance" => $this->balance,
            "bill" => $debt,
            "receipt" => $this->ref_code,
        ];
    }
}
