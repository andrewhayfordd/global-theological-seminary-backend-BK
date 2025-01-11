<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            "desc" => $this->prog_desc,
            "code" => $this->prog_code,
            "type" => $this->prog_type_desc,
            "typeCode" => $this->prog_type_code,
            "durationCode" => $this->dur_code,
            "duration" => $this->dur_desc,
            "action" => "
                <button class='btn btn-sm btn-success edit-btn'><i class='fas fa-edit'></i></button>
                <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
                <button class='btn btn-info btn-sm prog-btn' title='View student program lists'>
                <i class='fas fa-id-card-alt'></i>
                </button> 
            "
        ];
    }
}
