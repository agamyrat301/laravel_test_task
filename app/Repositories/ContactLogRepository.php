<?php

namespace App\Repositories;

class ContactLogRepository
{
    private string $logPath;

    public function __construct()
    {
        $this->logPath = storage_path('logs/contact_requests.log');
    }

    public function log(array $data): void
    {
        $entry = array_merge(['timestamp' => now()->toIso8601String()], $data);

        file_put_contents(
            $this->logPath,
            json_encode($entry) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}
