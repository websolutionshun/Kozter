<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => $params['projectName'] ?? 'Közter', // Alkalmazás név .env fájlból
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Magyar nyelvű útvonalak
                'fooldal' => 'site/index',
                'bejelentkezes' => 'site/login',
                'kijelentkezes' => 'site/logout',
                'regisztracio' => 'site/signup',
                'kapcsolat' => 'site/contact',
                'rolunk' => 'site/about',
                'jelszo-visszaallitas' => 'site/request-password-reset',
                'jelszo-uj/<token>' => 'site/reset-password',
                'email-ujrakuldese' => 'site/resend-verification-email',
                'email-megerositese/<token>' => 'site/verify-email',
                
                // Bejegyzések
                'bejegyzesek' => 'post/index',
                'bejegyzes/<slug>' => 'post/view',
                'kategoria/<slug>' => 'post/category',
                
                // AJAX endpoints
                'ajax/load-more-posts' => 'ajax/load-more-posts',
                'ajax/refresh-category' => 'ajax/refresh-category',
            ],
        ],
    ],
    'params' => $params,
];
