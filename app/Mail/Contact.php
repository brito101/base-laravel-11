<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    private $data;

    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(env('MAIL_TO_ADDRESS'), env('MAIL_TO_NAME'))
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Contato ' . env('APP_NAME'))
            ->markdown('emails.contact', [
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'phone_number' => $this->data['phone_number'],
                'msg_subject' => $this->data['msg_subject'],
                'message' => $this->data['message']
            ]);
    }
}
