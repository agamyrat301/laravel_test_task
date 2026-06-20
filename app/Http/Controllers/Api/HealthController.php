<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function index(): JsonResponse
    {
        $checks = [
            'cache'   => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'mail'    => $this->checkMailConfig(),
            'ai'      => $this->checkAiConfig(),
        ];

        $allOk = collect($checks)->every(fn ($c) => $c['ok'] === true);
        $status = $allOk ? 'healthy' : 'degraded';

        return response()->json([
            'success' => true,
            'status'  => $status,
            'version' => config('app.version', '1.0.0'),
            'checks'  => $checks,
        ], $allOk ? 200 : 207);
    }

    private function checkCache(): array
    {
        try {
            Cache::put('_health_probe', true, 5);
            $ok = Cache::get('_health_probe') === true;
            Cache::forget('_health_probe');
            return ['ok' => $ok, 'driver' => config('cache.default')];
        } catch (\Exception $e) {
            return ['ok' => false, 'driver' => config('cache.default'), 'error' => $e->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        $path = storage_path('logs');
        return [
            'ok'       => is_dir($path) && is_writable($path),
            'writable' => is_writable($path),
        ];
    }

    private function checkMailConfig(): array
    {
        $configured = !empty(config('mail.mailers.' . config('mail.default') . '.host'))
            || config('mail.default') === 'log';

        return [
            'ok'     => $configured,
            'mailer' => config('mail.default'),
        ];
    }

    private function checkAiConfig(): array
    {
        $enabled = (bool) config('ai.enabled');
        $hasKey  = !empty(config('ai.anthropic.key'));

        return [
            'ok'      => !$enabled || $hasKey,
            'enabled' => $enabled,
            'key_set' => $hasKey,
            'model'   => config('ai.anthropic.model'),
        ];
    }
}
