<?php

return [
    // Default to the working domain you tested
    'base_url' => env('SHARP_API_BASE', 'https://api.sharpscan.app'),
    // Bearer token
    'key'      => env('SHARP_API_KEY', ''),
    // endpoints (adjust if your vendor uses different paths)
    'endpoints' => [
        'parse' => '/v1/parse',
        'match' => '/v1/match',
    ],
    // Match threshold for "qualified"
    'qualified_score' => (int) env('SHARP_MATCH_THRESHOLD', 80),
];
