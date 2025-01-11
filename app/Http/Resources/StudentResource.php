<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $disabled = $this->status === 1? "disabled": 
        return [
            "id" => $this->transid,
            "student_no" => $this->student_no,
            "name" => "{$this->fname} {$this->mname} {$this->lname}",
            "prog" => $this->prog_desc,
            "prog_code" => $this->prog,
            "current_level" => $this->current_level,
            "level" => $this->level_desc,
            "level_admitted" => $this->level_admitted,
            "session" => $this->session,
            "sessionDesc" => $this->session_desc,
            "batch" => $this->batch,
            "batchDesc" => $this->batch_desc,
            "fname" => $this->fname,
            "mname" => $this->mname,
            "lname" => $this->lname,
            "gender" => $this->gender,
            "phone" => $this->phone,
            "email" => $this->email, 
            "marital_status" => $this->marital_status,
            "postal_address" => $this->postal_add,
            "residential" => $this->residential_gps,
            "dob" => $this->dob,
            "branch" => $this->branch_code,
            "eng_lang_grade" => $this->eng_lang_grade,
            "action" => "
            <button class='btn btn-sm btn-info info-btn '>
                <i class='fa fa-info'></i>
            </button>
            <button class='btn btn-sm btn-success student-update-btn'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
