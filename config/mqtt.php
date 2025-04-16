<?php

declare(strict_types=1);

use PhpMqtt\Client\MqttClient;

return [
    'default_connection' => 'bel_sekolah',

    'connections' => [
        'bel_sekolah' => [
            // Basic connection settings
            'host' => env('MQTT_HOST', 'localhost'),
            'port' => (int) env('MQTT_PORT', 1883),
            'protocol' => MqttClient::MQTT_3_1_1,
            
            // Authentication
            'username' => env('MQTT_USERNAME', null),
            'password' => env('MQTT_PASSWORD', null),
            
            // Client identification
            'client_id' => env('MQTT_CLIENT_ID', 'laravel_bel_'.gethostname().'_'.uniqid()),
            'use_clean_session' => false,
            
            // Logging
            'enable_logging' => env('MQTT_LOGGING', true),
            'log_channel' => env('MQTT_LOG_CHANNEL', 'stack'),

            // Connection settings
            'connection_settings' => [
                // Last Will and Testament
                'last_will' => [
                    'topic' => 'bel/sekolah/status/backend',
                    'message' => json_encode(['status' => 'offline', 'timestamp' => now()->toDateTimeString()]),
                    'quality_of_service' => 1,
                    'retain' => true,
                ],

                // Timeout settings
                'connect_timeout' => 10,
                'socket_timeout' => 5,
                'resend_timeout' => 10,
                'keep_alive_interval' => 30,

                // Reconnection settings
                'auto_reconnect' => true,
                'max_reconnect_attempts' => 5,
                'reconnect_delay' => 2,
            ],
        ],
    ],

    // Topic configuration
    'topics' => [
        'base' => 'bel/sekolah',
        'commands' => [
            'ring' => 'bel/sekolah/command/ring',
            'sync' => 'bel/sekolah/command/sync',
            'status_request' => 'bel/sekolah/command/status',
        ],
        'responses' => [
            'status' => 'bel/sekolah/response/status',
            'acknowledge' => 'bel/sekolah/response/ack',
        ],
        'system' => [
            'status' => 'bel/sekolah/status/device',
            'logs' => 'bel/sekolah/logs',
        ],
    ],
];