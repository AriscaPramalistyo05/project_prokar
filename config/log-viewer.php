<?php

use Opcodes\LogViewer\Http\Middleware\AuthorizeLogViewer;
use Opcodes\LogViewer\Http\Middleware\EnsureFrontendRequestsAreStateful;

return [

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Status
    |--------------------------------------------------------------------------
    */
    'enabled' => env('LOG_VIEWER_ENABLED', true),

    'api_only' => env('LOG_VIEWER_API_ONLY', false),

    'require_auth_in_production' => true,

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Route & Assets
    |--------------------------------------------------------------------------
    */
    'route_domain' => null,

    'route_path' => 'admin/logs',

    'assets_path' => 'vendor/log-viewer',

    /*
    |--------------------------------------------------------------------------
    | Back to System Link
    |--------------------------------------------------------------------------
    */
    'back_to_system_url' => '/admin/dashboard',

    'back_to_system_label' => 'Kembali ke Admin Dashboard',

    /*
    |--------------------------------------------------------------------------
    | Timezone & Datetime Format
    |--------------------------------------------------------------------------
    */
    'timezone' => 'Asia/Jakarta',

    'datetime_format' => 'Y-m-d H:i:s',

    /*
    |--------------------------------------------------------------------------
    | Route Middleware (Strict Security: Super Admin Only)
    |--------------------------------------------------------------------------
    */
    'middleware' => [
        'web',
        'auth',
        'role:super_admin',
        AuthorizeLogViewer::class,
    ],

    'api_middleware' => [
        EnsureFrontendRequestsAreStateful::class,
        'auth',
        'role:super_admin',
        AuthorizeLogViewer::class,
    ],

    'api_stateful_domains' => env('LOG_VIEWER_API_STATEFUL_DOMAINS') ? explode(',', env('LOG_VIEWER_API_STATEFUL_DOMAINS')) : null,

    /*
    |--------------------------------------------------------------------------
    | Hosts & Included Log Files
    |--------------------------------------------------------------------------
    */
    'hosts' => [
        'local' => [
            'name' => 'Prokar Production / Local',
        ],
    ],

    'include_files' => [
        '*.log',
        '**/*.log',
    ],

    'exclude_files' => [],

    'hide_unknown_files' => true,

    'shorter_stack_trace_excludes' => [
        '/vendor/symfony/',
        '/vendor/laravel/framework/',
        '/vendor/barryvdh/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache & Performance
    |--------------------------------------------------------------------------
    */
    'cache_driver' => env('LOG_VIEWER_CACHE_DRIVER', null),

    'cache_key_prefix' => 'lv',

    'lazy_scan_chunk_size_in_mb' => 50,

    'strip_extracted_context' => true,

    'per_page_options' => [10, 25, 50, 100, 250, 500],

    'defaults' => [
        'use_local_storage' => true,
        'folder_sorting_method' => 'ModifiedTime',
        'folder_sorting_order' => 'Descending',
        'file_sorting_method' => 'ModifiedTime',
        'log_sorting_order' => 'Descending',
        'per_page' => 25,
        'theme' => 'System',
        'shorter_stack_traces' => false,
    ],

    'exclude_ip_from_identifiers' => false,

    'patterns' => [
        //
    ],
];
