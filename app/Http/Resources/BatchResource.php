<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
                
            'desc'=>$this->batch_desc,
            'code'=>$this->batch_code,
            'action' => " 
            <button type='button' rel='tooltip' title='Remove batch'
                class='btn btn-danger btn-sm delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
            <button type='button' href='#' class='btn btn-info btn-sm list-btn' rel='tooltip' title='View batch lists'>
                <i class='fas fa-id-card-alt'></i>
            </button>     ",
        ];
    }
}
// <button type='button' rel='tooltip' title='Edit batch'
//                 class='btn btn-success  btn-sm edit-btn'>
//                 <i class='fas fa-edit'></i>
//             </button>