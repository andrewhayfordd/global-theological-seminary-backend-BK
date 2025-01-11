<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramBillResource extends JsonResource
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
            "amount" => "GHS" . $this->total_bill,
            "student_no" => $this->student_no,
            "student_name" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => <<<EOT
            <button href = "#" data-row-code = '{$this->student_no}'
             onclick="indiPrintStudentBillReport(this.dataset.rowCode)" 
             title='print bill' class='rounded btn btn-outline-secondary btn-sm'> 
                Print Bill
            </button>
EOT,
        ];
    }
}
