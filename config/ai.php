<?php

return [

    'enabled'  => env('AI_ENABLED', true),
    'timeout'  => (int) env('AI_TIMEOUT', 30),
    'provider' => env('AI_PROVIDER', 'anthropic'),

    'anthropic' => [
        'key'   => env('ANTHROPIC_API_KEY', ''),
        'model' => env('ANTHROPIC_MODEL', 'claude-haiku-4-5-20251001'),
    ],

];
