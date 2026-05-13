<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactFormMail extends Mailable
{
    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $mailSubject,
        public ?string $mailMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Сообщение с сайта: ' . $this->mailSubject,
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }
}