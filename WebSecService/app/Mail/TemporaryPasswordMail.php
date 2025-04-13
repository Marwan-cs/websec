<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $temporaryPassword;

    public function __construct($temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Temporary Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.temporary-password',
            with: ['temporaryPassword' => $this->temporaryPassword],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}