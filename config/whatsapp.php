<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp integration using Venom Bot
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'http://localhost:3000'),
    'api_key' => env('WHATSAPP_API_KEY', ''),
    
    // Enable/disable WhatsApp notifications
    'enabled' => env('WHATSAPP_ENABLED', true),
    
    // Default settings
    'default_country_code' => '62', // Indonesia
    'timeout' => 30, // seconds
    
    // Message templates
    'templates' => [
        'status_update' => '[{app_name} – Sidoarjo] Status laporan Anda telah diperbarui.',
        'verification' => '[{app_name} – Sidoarjo] Laporan Anda telah diverifikasi.',
        'in_progress' => '[{app_name} – Sidoarjo] Laporan Anda sedang dalam proses perbaikan.',
        'completed' => '[{app_name} – Sidoarjo] Laporan Anda telah selesai ditangani.'
    ]
];