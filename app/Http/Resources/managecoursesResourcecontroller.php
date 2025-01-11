<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class managecoursesResourcecontroller extends JsonResource
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
            "transid" => $this->transid,
            "coursecode" => $this->course_code,
            "coursetitle" => $this->course_title,
            "semesterDesc" => $this->sem_desc,
            "semester" => $this->semester,
            "credit" => $this->credit,
            "level_code" => $this->level_code,
            "coursedesc" => $this->course_desc,
            "action" => " <button type='button' rel='tooltip' title='Edit department'
            class='btn btn-success  btn-sm edit-btn'>
            <i class='fas fa-edit'></i>
        </button>
        
        <button type='button' rel='tooltip' title='Delete course'
            class='btn btn-danger btn-sm delete-btn'>
            <i class='fas fa-trash'></i>
        </button>
        <button type='button' href='#' class='btn btn-info btn-sm' rel='tooltip' title='View department lists'>
        <i class='fas fa-id-card-alt'></i>
    </button> 
          ",
        ];
    }
}
