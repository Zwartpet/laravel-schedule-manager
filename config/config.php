<?php

return [
    'driver' => env('SCHEDULE_MANAGER_DRIVER', 'cache'),

    'drivers' => [
        'cache' => [
            'prefix' => env('SCHEDULE_MANAGER_CACHE_PREFIX', 'schedule_manager_'),
            'store' => env('SCHEDULE_MANAGER_CACHE_STORE', env('CACHE_STORE', 'file')),
        ],
    ],
];
