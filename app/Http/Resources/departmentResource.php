<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class departmentResource extends JsonResource
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
            
            'departmentname'=>$this->dept_desc,
            'departmentcode'=>$this->dept_code,
            'action' => " <button type='button' rel='tooltip' title='Edit department'
                class='btn btn-success  btn-sm edit-btn'>
                <i class='fas fa-edit'></i>
            </button>
            
            <button type='button' rel='tooltip' title='Remove department'
                class='btn btn-danger btn-sm delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
            <button type='button' href='#' class='btn btn-info btn-sm dept-btn' rel='tooltip' title='View department lists'>
                <i class='fas fa-id-card-alt'></i>
            </button>     ",
           
        ]
        ;
    }
}

