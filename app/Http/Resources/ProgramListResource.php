<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramListResource extends JsonResource
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
            "picture" => "<img src='{$this->picture}' class='rounded' style='height:50px; width:50px;'>",
            "prog" => $this->prog_desc,
            "student" => "{$this->fname} {$this->mname} {$this->lname}",
        ];
    }
}
