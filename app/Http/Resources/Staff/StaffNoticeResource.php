<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffNoticeResource extends JsonResource
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
            "type_desc" => $this->type_desc,
            "recipient" =>$this->recipient_desc,
            "news_title" =>$this->notice_title,
            "news_details" =>$this->notice_details,
            "date_start" =>date('jS M Y', strtotime($this->date_start)),
            "date_end" =>date('jS M Y', strtotime($this->date_end)),
            "transid" =>$this->transid,
            "type" =>$this->notice_type,
            "post" =>$this->posted_by,
            "date_s" =>$this->date_start,
            "date_e" =>$this->date_end,
            "rec" =>$this->recipient_code,
        ];
    }
}
