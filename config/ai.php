<?php

return [

    'enabled'  => env('AI_ENABLED', true),
    'timeout'  => (int) env('AI_TIMEOUT', 30),
    'provider' => env('AI_PROVIDER', 'gemini'),

    'gemini' => [
        'key'   => env('GEMINI_API_KEY', ''),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
    ],

];
