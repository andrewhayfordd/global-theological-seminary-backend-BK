<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'asessment_id' => $this->assid,
            'student_id' => $this->student_code,
            'student_name' => Str::title("{$this->fname} {$this->mname} {$this->lname}"),
            'total_score' => $this->total_score,
            'course_id' => $this->course_code,
            'branch_id' => $this->branch_code,
            'exam_score' => ($this->total_exam *100) / 60 ,
            'test_score' => ($this->total_test * 100) / 40,
            "pure_test" => $this->total_test,
            "pure_exam" => $this->total_exam,
            "sem_id" => $this->semester,
            "action" => 
            "
            <button class='btn btn-sm btn-success edit-btn'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
            "

        ];
    }
}
// <button class='btn btn-sm btn-info info-btn'>
// <i class='fa fa-info'></i>
// </button>
