<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_NAME', 'yii2advanced'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
        ],

        'phpmailer' => [
            'host' => env('SMTP_HOST', 'localhost'),
            'port' => env('SMTP_PORT', 587),
            'username' => env('SMTP_USERNAME', ''),
            'password' => env('SMTP_PASSWORD', ''),
            'encryption' => env('SMTP_ENCRYPTION', 'tls'),
            'fromEmail' => env('SMTP_FROM_EMAIL', 'noreply@example.com'),
            'fromName' => env('SMTP_FROM_NAME', 'Application'),
            'charset' => env('SMTP_CHARSET', 'UTF-8'),
            'debug' => env('YII_DEBUG', false),
        ],
    ],
];
