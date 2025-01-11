<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffMessageCentreEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageDetail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($messageDetail)
    {
         $this->messageDetail = $messageDetail;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $this->from(env("MAIL_FROM_ADDRESS"), "AC PORTAL")
        return $this->subject($this->messageDetail['subject'])
        ->with([
            'mail' => $this->messageDetail
        ])->view('emails.admin_message_centre_email');
    }
}
