<?php

return [

    'enabled'  => env('AI_ENABLED', true),
    'timeout'  => (int) env('AI_TIMEOUT', 30),
    'provider' => env('AI_PROVIDER', 'groq'),

    'groq' => [
        'key'   => env('GROQ_API_KEY', ''),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    ],

];
