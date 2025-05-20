<?php

return [
    'key' => env('MS_USER_SPOOFING_KEY', 'user-proxy'),

    'success_redirect_to' => env(
        'MS_USER_SPOOFING_SUCCESS_REDIRECT_TO',
        config('moonshine.route.prefix', '/')
    ),

    'routes' => [
        'prefix' => env('MS_USER_SPOOFING_ROUTE_PREFIX', config('moonshine.route.prefix').'/spoofing'),

        'middleware' => ['web'],
    ],

    'buttons' => [
        'start' => [
            'icon' => env('MS_USER_SPOOFING_START_BUTTON_ICON', 'eye')
        ],
        'stop' => [
            'icon' => env('MS_USER_SPOOFING_STOP_BUTTON_ICON', 'eye-slash'),
            'class' => env('MS_USER_SPOOFING_STOP_BUTTON_CLASS', 'btn-secondary'),
        ],
    ],

    // query string key name for resource item
    'resource_item_key' => env('MS_USER_SPOOFING_RESOURCE_ITEM_KEY', 'resourceItem'),

    // show 'toast' notifications on different actions
    'show_notification' => env('MS_USER_SPOOFING_SHOW_NOTIFICATION', true),
];
