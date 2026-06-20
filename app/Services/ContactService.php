<?php

namespace App\Services;

use App\Repositories\ContactLogRepository;
use App\Repositories\MetricsRepository;

class ContactService
{
    public function __construct(
        private readonly AiService             $aiService,
        private readonly MailService           $mailService,
        private readonly ContactLogRepository  $contactLogRepository,
        private readonly MetricsRepository     $metricsRepository,
    ) {}

    public function process(array $contactData, string $ip): array
    {
        $startTime = microtime(true);

        // 1. AI: sentiment analysis, request classification, auto-response generation
        $aiResult = $this->aiService->analyzeContact($contactData);
        $aiFailed = !($aiResult['ai_enabled'] ?? false) && ($aiResult['ai_attempted'] ?? false);

        // 2. Email: notify owner + send copy to user
        $emailResults = $this->mailService->sendContactNotifications($contactData, $aiResult);
        $emailFailed  = !$emailResults['owner_email_sent'] && !$emailResults['user_email_sent'];

        $durationMs = (int) round((microtime(true) - $startTime) * 1000);

        // 3. Log structured contact entry
        $this->contactLogRepository->log([
            'ip'             => $ip,
            'name'           => $contactData['name'],
            'email'          => $contactData['email'],
            'phone'          => $contactData['phone'],
            'comment_length' => mb_strlen($contactData['comment']),
            'ai_result'      => $aiResult,
            'email_results'  => $emailResults,
            'duration_ms'    => $durationMs,
        ]);

        // 4. Update file-based metrics
        $this->metricsRepository->increment([
            'success'      => true,
            'sentiment'    => $aiResult['sentiment'],
            'request_type' => $aiResult['request_type'],
            'ai_failed'    => $aiFailed,
            'email_failed' => $emailFailed,
        ]);

        return [
            'ai_result'     => $aiResult,
            'email_results' => $emailResults,
        ];
    }
}
