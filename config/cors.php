<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Next.js frontend URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'X-CSRF-TOKEN', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
