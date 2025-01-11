<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PaymentControllerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

      
        $studentData = DB::table('tblpayment')

            ->select(DB::raw('SUM(amount) AS amount'), (DB::raw('SUM(total_paid) AS totalamount')), (DB::raw('SUM(amount)-SUM(total_paid) AS arrears ')))
            ->where('student_no', $this->student_no)
            ->where('deleted', '0')
            ->where('school_code', $this->school_code)
            ->first();
        // }



        return [
            "acyear" => $this->acyear,
            "term" => $this->acterm,
            "arrear" => $studentData->arrears,
            "studentName" => $this->fname . $this->lname,
            "amount" => $studentData->amount,
            "amountPaid" => $studentData->totalamount,
            "action" =>  " 
        <button type='button' rel='tooltip' title='Delete payment'
            class='btn btn-danger btn-sm delete-btn'>
            <i class='fas fa-trash'></i>
        </button>"
        ];
    }
}
