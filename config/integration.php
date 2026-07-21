<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External Integrations Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized configuration for all external system integrations (WMS,
    | ISO Library, ERP, etc.) ensuring a unified integration pattern.
    |
    */

    'wms' => [
        'connection' => env('WMS_DB_CONNECTION', 'wms'),
        
        // Base URL for WMS application (used to build direct links to WMS items)
        'base_url' => env('WMS_BASE_URL', 'http://127.0.0.1:8000/items/'),

        // Configurable Live Stock Thresholds
        // 0 => Red (Out of Stock)
        // 1..5 => Yellow (Low Stock)
        // > 5 => Green (Stock Available)
        'stock_thresholds' => [
            'danger_max' => (int) env('WMS_STOCK_DANGER_MAX', 0),
            'warning_max' => (int) env('WMS_STOCK_WARNING_MAX', 5),
        ],
    ],

];
