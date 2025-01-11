<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramStudentResource extends JsonResource
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
            "programcode" => $this->prog_code,
            "programtitle" => $this->prog_desc,
            "student" => $this->fname . $this->lname,
            "acyear" => $this->admyear,
            "semester" => $this->admsemester,


        ];
    }
}
