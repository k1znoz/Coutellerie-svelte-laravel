<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Configuration (Legacy - Filament v3 uses PanelProviders)
    |--------------------------------------------------------------------------
    | 
    | Note: Dans Filament v3, la configuration se fait principalement
    | via les PanelProvider (AdminPanelProvider.php).
    | Ce fichier est maintenu pour compatibilité.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    'assets_path' => null,

    'cache_path' => base_path('bootstrap/cache/filament'),

    'livewire_loading_delay' => 'default',

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [
            \Filament\Pages\Dashboard::class,
        ],
    ],

    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
    ],

    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
    ],

    'livewire' => [
        'namespace' => 'App\\Filament',
        'path' => app_path('Filament'),
    ],

    'dark_mode' => false,

    'database_notifications' => [
        'enabled' => false,
        'polling_interval' => '30s',
    ],

    'broadcasting' => [
        'echo' => [
            'broadcaster' => 'pusher',
            'key' => env('VITE_PUSHER_APP_KEY'),
            'cluster' => env('VITE_PUSHER_APP_CLUSTER'),
            'forceTLS' => true,
        ],
    ],

    'layout' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                ],
            ],
        ],
    ],
];