<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentListResource extends JsonResource
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
            "dept" => $this->dept_desc,
            "staff" => "{$this->fname} {$this->mname} {$this->lname}",
        ];
    }
}
