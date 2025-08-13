<?php
return [
    'adminEmail' => env('ADMIN_EMAIL', 'admin@example.com'),
    'supportEmail' => env('SUPPORT_EMAIL', 'support@example.com'),
    'senderEmail' => env('SENDER_EMAIL', 'noreply@example.com'),
    'senderName' => env('SENDER_NAME', 'Example.com mailer'),
    'user.passwordResetTokenExpire' => env('USER_PASSWORD_RESET_TOKEN_EXPIRE', 3600),
    'user.passwordMinLength' => env('USER_PASSWORD_MIN_LENGTH', 8),
    'projectName' => env('PROJECT_NAME', 'Közter'), // Projekt név .env fájlból
];
