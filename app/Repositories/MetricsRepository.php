<?php

namespace App\Repositories;

class MetricsRepository
{
    private string $filePath;

    private array $schema = [
        'total_requests'         => 0,
        'successful_requests'    => 0,
        'failed_requests'        => 0,
        'sentiment_breakdown'    => [
            'positive' => 0,
            'neutral'  => 0,
            'negative' => 0,
        ],
        'request_type_breakdown' => [
            'general_inquiry'   => 0,
            'technical_support' => 0,
            'partnership'       => 0,
            'complaint'         => 0,
            'job_application'   => 0,
            'other'             => 0,
        ],
        'ai_failures'    => 0,
        'email_failures' => 0,
        'last_request_at' => null,
    ];

    public function __construct()
    {
        $this->filePath = storage_path('app/metrics.json');
        $this->ensureFileExists();
    }

    public function get(): array
    {
        $content = file_get_contents($this->filePath);
        return json_decode($content, true) ?? $this->schema;
    }

    public function increment(array $data): void
    {
        $handle = fopen($this->filePath, 'c+');

        if (!$handle) {
            return;
        }

        flock($handle, LOCK_EX);

        $content = stream_get_contents($handle);
        $metrics = !empty($content) ? (json_decode($content, true) ?? $this->schema) : $this->schema;

        $metrics['total_requests']++;

        if ($data['success'] ?? true) {
            $metrics['successful_requests']++;
        } else {
            $metrics['failed_requests']++;
        }

        $sentiment = $data['sentiment'] ?? 'neutral';
        if (array_key_exists($sentiment, $metrics['sentiment_breakdown'])) {
            $metrics['sentiment_breakdown'][$sentiment]++;
        }

        $requestType = $data['request_type'] ?? 'other';
        if (array_key_exists($requestType, $metrics['request_type_breakdown'])) {
            $metrics['request_type_breakdown'][$requestType]++;
        }

        if ($data['ai_failed'] ?? false) {
            $metrics['ai_failures']++;
        }

        if ($data['email_failed'] ?? false) {
            $metrics['email_failures']++;
        }

        $metrics['last_request_at'] = now()->toIso8601String();

        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($metrics, JSON_PRETTY_PRINT));

        flock($handle, LOCK_UN);
        fclose($handle);
    }

    private function ensureFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            file_put_contents(
                $this->filePath,
                json_encode($this->schema, JSON_PRETTY_PRINT)
            );
        }
    }
}
