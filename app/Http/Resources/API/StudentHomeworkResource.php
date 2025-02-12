<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentHomeworkResource extends JsonResource
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
            "title" => $this->homework_title,
            //"body" => $this->homework_details,
            "datePosted" => $this->when(isset($this->date_posted), $this->date_posted),
            //"dateEnd" => $this->when(isset($this->date_end), $this->date_end),
        ];
    }
}