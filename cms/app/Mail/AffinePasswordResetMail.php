<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AffinePasswordResetMail extends Mailable
{
    public function __construct(
        public string $recipientName,
        public string $resetUrl,
        public string $baseUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Смена пароля AFFiNE',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affine-password-reset',
        );
    }
}
