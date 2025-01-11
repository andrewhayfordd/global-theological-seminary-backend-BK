<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PaymentResource extends JsonResource
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
        $total = DB::table("vtbloverall_total")->where("student_no", $this->student_no)
            ->where("school_code", $this->school_code)
            ->first();

        $arrears = $this->total_bill - $this->total_paid;
        if ( $total->total_paid > $bill) {
            $arrears = $bill - $total->total_paid;
        } else if ($bill == $total->total_paid){
            $arrears = 0;
        }else if ($arrears < 0){
            $arrears = 0;
        }else {
            $arrears = $bill - $total->total_paid;
        }
        return [
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
            "semester" => $this->semester,
            "bill" => $this->total_bill,
            "program" => $this->prog_desc,
            "amtpaid" => $this->total_paid,
            "studentNo" => $this->student_no,
            "arrears" => round($arrears, 2),
            "action" => <<<EOT
            <button href = "#" data-row-code = '{$this->student_no}'
             onclick="printStudentPaymentReceipt(this.dataset.rowCode)" 
             title='print receipt' class='rounded btn btn-outline-secondary btn-sm rounded'> 
                <i class="fa fa-print"></i>
            </button>
            <button href = "#" title='delete payment' 
            class='rounded btn btn-outline-danger btn-sm rounded payment-delete-btn'> 
                <i class="fas fa-trash"></i>
            </button>
        EOT,
        ];
    }
}
