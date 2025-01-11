<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchListResource extends JsonResource
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
            "batch" =>$this->batch_desc,
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
            "program" => $this->prog_desc,
        ];
    }
}
