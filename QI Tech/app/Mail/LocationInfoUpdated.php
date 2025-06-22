<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocationInfoUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $oldValue;
    public $newValue;
    public $field;

    /**
     * Create a new message instance.
     *
     * @param $user - the user receiving the email
     * @param $field - the field being updated (username/email)
     * @param $oldValue - old value of the field
     * @param $newValue - new value of the field
     */
    public function __construct($user, $field, $oldValue, $newValue)
    {
        $this->user = $user;
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
{
    $subject = 'Your ' . strtolower($this->field) . ' has changed';


    return $this->subject($subject)
                ->view('emails.location_info_updated');
}
}
