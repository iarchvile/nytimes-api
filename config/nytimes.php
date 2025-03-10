<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NYTimes API Configuration
    |--------------------------------------------------------------------------
    */

    'base_url' => env('NYT_API_BASE_URL', 'https://api.nytimes.com/svc'),
    'api' => [
        'books' => [
            'endpoint' => env('NYT_API_BOOKS_ENDPOINT', 'books/v3'),
            'key' => env('NYT_API_BOOKS_KEY'),
        ]
    ],

    /*
    | This section defines the configuration settings for the API rate limiting.
    | The New York Times API enforces two rate limits:
    | - 5 requests per minute (with a recommended 12-second delay between calls).
    | - 500 requests per day.
    |
    | You can adjust these values in your ".env" file using the following
    | environment variables:
    | - RATE_LIMIT_MAX_ATTEMPTS_PER_DAY: Maximum number of requests per day (default: 500).
    | - RATE_LIMIT_MAX_ATTEMPTS_PER_MINUTE: Maximum number of requests per minute (default: 5).
    | - RATE_LIMIT_DECAY_MINUTES: Time frame (in minutes) for resetting the per-minute rate limit (default: 1).
    |
    | For more details, refer to the NYT API FAQ:
    | https://developer.nytimes.com/faq
    */

    'rate_limit' => [
        'max_attempts_per_day' => env('RATE_LIMIT_MAX_ATTEMPTS_PER_DAY', 500),
        'max_attempts_per_minute' => env('RATE_LIMIT_MAX_ATTEMPTS_PER_MINUTE', 5),
        'decay_minutes' => env('RATE_LIMIT_DECAY_MINUTES', 1),
    ]
];
