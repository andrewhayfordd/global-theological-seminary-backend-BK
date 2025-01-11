<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffStudentResource extends JsonResource
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
            "name" => "{$this->fname} {$this->mname} {$this->lname}",
            "courseName" => $this->course_title,
            "courseCode" => $this->course_code,
            "studentCode" => $this->student_no,
            "gender" => $this->gender
        ];
    }
}
