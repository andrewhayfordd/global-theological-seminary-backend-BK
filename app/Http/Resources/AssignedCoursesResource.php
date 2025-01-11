<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignedCoursesResource extends JsonResource
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
            "id" => $this->transid,
            "course_code" => $this->course_code,
            "course_desc" => $this->course_title,
            "staff" =>"{$this->fname} {$this->mname} {$this->lname}",
            "date_assigned" =>date('jS M Y', strtotime($this->date_assigned)),
            "semester" => $this->sem_desc,
            "action" => "
            <button class='btn btn-sm btn-success assign-edit-btn'>
                <i class='fas fa-edit'></i>
            </button>
            <button class='btn btn-sm btn-danger assign-delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
