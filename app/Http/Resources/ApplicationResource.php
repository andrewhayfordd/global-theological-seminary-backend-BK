<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            "app_no" => $this->app_code,
            "date" => date("jS F Y", strtotime($this->admdate)),
            "batch" => $this->batch_desc,
            "prog" => $this->prog_desc,
            "session" => $this->session_desc,
            "name" => "{$this->fname} {$this->mname} {$this->lname}",
            "action" => "
            <button class='btn btn-sm btn-success info-btn' rel='tooltip' title='View student info'>
                <i class='fas fa-eye'></i>
            </button>
            <button class='btn btn-sm btn-info info-btn' rel='tooltip' title='Applicant interview form'>
                <i class='fas fa-info'></i>
            </button>
            <button class='btn btn-sm btn-danger delete-btn'><i class='fas fa-trash'></i></button>
        "
        ];
    }
}
