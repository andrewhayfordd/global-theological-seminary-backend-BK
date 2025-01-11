<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenditureCategoryResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "code" => $this->code,
            "action" => <<<EOT
            <button class='btn btn-sm btn-outline-success rounded cat-update-btn' 
            title='update details'>
            <i class='fas fa-edit'></i>
            </button>
            <button class='btn btn-sm btn-outline-danger rounded cat-delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
            EOT
        ];
    }
}
