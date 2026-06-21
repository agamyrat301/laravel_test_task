<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AiService
{
    private Client $client;
    private bool $enabled;
    private string $apiKey;
    private string $model;
    private int $timeout;

    private array $allowedSentiments   = ['positive', 'neutral', 'negative'];
    private array $allowedRequestTypes = [
        'general_inquiry', 'technical_support', 'partnership',
        'complaint', 'job_application', 'other',
    ];

    public function __construct()
    {
        $this->enabled = (bool) config('ai.enabled', true);
        $this->apiKey  = (string) config('ai.groq.key', '');
        $this->model   = (string) config('ai.groq.model', 'llama-3.3-70b-versatile');
        $this->timeout = (int) config('ai.timeout', 30);

        $this->client = new Client([
            'base_uri' => 'https://api.groq.com',
            'timeout'  => $this->timeout,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function analyzeContact(array $contactData): array
    {
        if (!$this->enabled || empty($this->apiKey)) {
            Log::channel('contact_requests')->warning('AI analysis skipped: AI is disabled or GROQ_API_KEY is not set.');
            return $this->fallback(false);
        }

        try {
            $result = $this->callApi($this->buildPrompt($contactData));
            return array_merge($result, ['ai_enabled' => true]);
        } catch (\Exception $e) {
            Log::channel('contact_requests')->error('AI analysis failed', [
                'error'   => $e->getMessage(),
                'contact' => $contactData['email'] ?? 'unknown',
            ]);
            return $this->fallback(true);
        }
    }

    private function buildPrompt(array $data): string
    {
        return <<<PROMPT
You are an assistant analyzing contact form submissions for a developer's portfolio website.

Analyze the submission below and return ONLY a valid JSON object with these exact keys:
- "sentiment": one of "positive", "neutral", "negative"
- "sentiment_score": float 0.0 (most negative) to 1.0 (most positive)
- "request_type": one of "general_inquiry", "technical_support", "partnership", "complaint", "job_application", "other"
- "auto_response": a professional, friendly 2-3 sentence reply to send to the user (write it in the same language as the comment)

Contact form submission:
Name: {$data['name']}
Email: {$data['email']}
Phone: {$data['phone']}
Comment: {$data['comment']}

Respond with ONLY the JSON object. No markdown fences, no explanations.
PROMPT;
    }

    private function callApi(string $prompt): array
    {
        $response = $this->client->post('/openai/v1/chat/completions', [
            'json' => [
                'model'       => $this->model,
                'messages'    => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.3,
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $text = trim($body['choices'][0]['message']['content'] ?? '');

        $text = preg_replace('/^```(?:json)?\s*|\s*```$/s', '', $text);

        $parsed = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('AI returned unparseable JSON: ' . $text);
        }

        return $this->sanitize($parsed);
    }

    private function sanitize(array $raw): array
    {
        return [
            'sentiment'       => in_array($raw['sentiment'] ?? '', $this->allowedSentiments)
                ? $raw['sentiment'] : 'neutral',
            'sentiment_score' => is_numeric($raw['sentiment_score'] ?? null)
                ? max(0.0, min(1.0, (float) $raw['sentiment_score'])) : 0.5,
            'request_type'    => in_array($raw['request_type'] ?? '', $this->allowedRequestTypes)
                ? $raw['request_type'] : 'other',
            'auto_response'   => strip_tags((string) ($raw['auto_response'] ?? '')),
        ];
    }

    private function fallback(bool $aiAttempted): array
    {
        return [
            'sentiment'       => 'neutral',
            'sentiment_score' => 0.5,
            'request_type'    => 'general_inquiry',
            'auto_response'   => 'Thank you for reaching out! We have received your message and will get back to you as soon as possible.',
            'ai_enabled'      => false,
            'ai_attempted'    => $aiAttempted,
        ];
    }
}
