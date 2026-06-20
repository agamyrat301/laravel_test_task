<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $contactData,
        public readonly array $aiResult,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your message — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-user');
    }
}
