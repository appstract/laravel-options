<?php

return [
    'cache' => [
        'driver'        => env('CACHE_DRIVER', 'file'),
        'valid_minutes' => env('OPTION_CACHE_VALID_MINUTES', 60),
    ],

    'events' => [
        'created' => env('OPTION_EVENTS_CREATED', false),
        'updated' => env('OPTION_EVENTS_UPDATED', false),
        'deleted' => env('OPTION_EVENTS_DELETED', false),
        'finding' => env('OPTION_EVENTS_FINDING', false),
        'found'   => env('OPTION_EVENTS_FOUND', false),
        'exists'  => env('OPTION_EVENTS_EXISTS', false),
    ],
];
