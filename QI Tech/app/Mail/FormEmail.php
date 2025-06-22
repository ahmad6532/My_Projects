<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class FormEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {   
    } 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You have submitted a form in the application.')->view('emails.form-mail')->with('messageContent',$this->messageContent);
    }
}
