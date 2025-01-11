<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class courseRegisteredStuentsResourceController extends JsonResource
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
            "studentname"=>"{$this->fname} {$this->mname} {$this->lname}",
            "semester"=>$this->semester,
            "academicyear"=>$this->acyear,
            "action"=>"<button></button>",
        ];
    }
}
