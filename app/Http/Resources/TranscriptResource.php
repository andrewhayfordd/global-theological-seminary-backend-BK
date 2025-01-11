<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TranscriptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        foreach ($this as $individualStudent) {
            return [
                "student" => "{$individualStudent->fname} {$individualStudent->mname} {$individualStudent->lname}",
                "student_no" => $individualStudent->student_no,
                "class" => $individualStudent->gender,
                "action" => <<<EOT
                    <a href='#' class='btn-sm text-white btn-info shadow-sm'
                    data-row-assessment = '$individualStudent'
                    onclick='fetchStudentReport(this.dataset)'><i class=''></i>Print Transcript</a>
                EOT
            ];
        }
    }
}
