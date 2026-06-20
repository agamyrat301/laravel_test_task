<?php

namespace App\Services;

use App\Mail\ContactOwnerMail;
use App\Mail\ContactUserMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendContactNotifications(array $contactData, array $aiResult): array
    {
        return [
            'owner_email_sent' => $this->notifyOwner($contactData, $aiResult),
            'user_email_sent'  => $this->confirmToUser($contactData, $aiResult),
        ];
    }

    private function notifyOwner(array $data, array $aiResult): bool
    {
        $ownerEmail = config('mail.owner_email');

        if (empty($ownerEmail)) {
            Log::channel('contact_requests')->warning('Owner email not configured; skipping owner notification.');
            return false;
        }

        try {
            Mail::to($ownerEmail)->send(new ContactOwnerMail($data, $aiResult));
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
            Mail::to($data['email'])->send(new ContactUserMail($data, $aiResult));
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
