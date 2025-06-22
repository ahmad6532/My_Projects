<?php

namespace App\Mail\Headoffice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailAlertWhenHoldingAreaOn extends Mailable
{
    use Queueable, SerializesModels;

    public $alert = null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nationalAlert)
    {
        $this->alert = $nationalAlert;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'A new patient safety alert require actioning!',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.head_office.mail_alert_when_holding_area_on',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
