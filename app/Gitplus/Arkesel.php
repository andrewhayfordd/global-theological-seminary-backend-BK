<?php

namespace App\Gitplus;

use Illuminate\Support\Facades\Http;

class Arkesel
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl = "https://sms.arkesel.com/sms/api";


    public function __construct(string $senderId, string $apiKey)
    {
        $this->senderId = $senderId;
        $this->apiKey = $apiKey;
    }

    public function send(string $recipient, string $msg)
    {
        $response = Http::get($this->baseUrl, [
            "action" => "send-sms",
            "api_key" => $this->apiKey,
            "to" => $recipient,
            "from" => $this->senderId,
            "msg" => $msg,
        ]);

        return $response->body();
    }
}