<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Mailtrap\Api\EmailsSendApiInterface;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class MailService
{
    public function sendContactNotifications(array $contactData, array $aiResult): array
    {
        return [
            'owner_email_sent' => $this->notifyOwner($contactData, $aiResult),
            'user_email_sent'  => $this->confirmToUser($contactData, $aiResult),
        ];
    }

    private function client(): EmailsSendApiInterface
    {
        return MailtrapClient::initSendingEmails(
            apiKey:    config('services.mailtrap.api_key'),
            isSandbox: (bool) config('services.mailtrap.sandbox'),
            inboxId:   config('services.mailtrap.inbox_id') ? (int) config('services.mailtrap.inbox_id') : null,
        );
    }

    private function fromAddress(): Address
    {
        return new Address(
            config('mail.from.address', 'noreply@example.com'),
            config('mail.from.name', config('app.name'))
        );
    }

    private function notifyOwner(array $data, array $aiResult): bool
    {
        $ownerEmail = config('mail.owner_email');
        if (empty($ownerEmail)) {
            Log::channel('contact_requests')->warning('Owner email not configured; skipping owner notification.');
            return false;
        }

        try {
            $type = ucwords(str_replace('_', ' ', $aiResult['request_type'] ?? 'inquiry'));
            $html = view('emails.contact-owner', ['contactData' => $data, 'aiResult' => $aiResult])->render();

            $email = (new MailtrapEmail())
                ->from($this->fromAddress())
                ->to(new Address($ownerEmail, config('mail.owner_name', 'Portfolio Owner')))
                ->subject("[New Contact] {$data['name']} — {$type}")
                ->html($html);

            $this->client()->send($email);
            return true;
        } catch (\Exception $e) {
            Log::channel('contact_requests')->error('Failed to send owner notification email', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function confirmToUser(array $data, array $aiResult): bool
    {
        try {
            $html = view('emails.contact-user', ['contactData' => $data, 'aiResult' => $aiResult])->render();

            $email = (new MailtrapEmail())
                ->from($this->fromAddress())
                ->replyTo(new Address(
                    config('mail.owner_email') ?: config('mail.from.address')
                ))
                ->to(new Address($data['email'], $data['name']))
                ->subject('We received your message — ' . config('app.name'))
                ->html($html);

            $this->client()->send($email);
            return true;
        } catch (\Exception $e) {
            Log::channel('contact_requests')->error('Failed to send user confirmation email', [
                'error' => $e->getMessage(),
                'user'  => $data['email'],
            ]);
            return false;
        }
    }
}
