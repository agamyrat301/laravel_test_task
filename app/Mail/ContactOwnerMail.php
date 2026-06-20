<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactOwnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $contactData,
        public readonly array $aiResult,
    ) {}

    public function envelope(): Envelope
    {
        $type = ucwords(str_replace('_', ' ', $this->aiResult['request_type'] ?? 'inquiry'));

        return new Envelope(
            subject: "[New Contact] {$this->contactData['name']} — {$type}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-owner');
    }
}
