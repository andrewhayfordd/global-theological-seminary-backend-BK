<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
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
            
            "recipient" => $this->recipient,
            "email_subject" => $this->email_subject,
            "email_message" => wordwrap($this->email_message, 45,"<br>\n"),
            "date" => date('jS M Y', strtotime($this->createdate)),
        ];
    }
}
