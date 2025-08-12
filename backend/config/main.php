<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
                'felhasznalok' => 'user/index',
                'felhasznalok/letrehozas' => 'user/create',
                'felhasznalok/<id:\d+>' => 'user/view',
                'felhasznalok/<id:\d+>/szerkesztes' => 'user/update',
                'felhasznalok/<id:\d+>/torles' => 'user/delete',
                
                'szerepkorok' => 'role/index',
                'szerepkorok/letrehozas' => 'role/create',
                'szerepkorok/<id:\d+>' => 'role/view',
                'szerepkorok/<id:\d+>/szerkesztes' => 'role/update',
                'szerepkorok/<id:\d+>/torles' => 'role/delete',
                
                'jogosultsagok' => 'permission/index',
                'jogosultsagok/letrehozas' => 'permission/create',
                'jogosultsagok/<id:\d+>' => 'permission/view',
                'jogosultsagok/<id:\d+>/szerkesztes' => 'permission/update',
                'jogosultsagok/<id:\d+>/torles' => 'permission/delete',
                
                'fooldal' => 'site/index',
                'bejelentkezes' => 'site/login',
                'kijelentkezes' => 'site/logout',
                'admin-regisztracio' => 'site/admin-register',
                'elfelejtett-jelszo' => 'site/forgot-password',
            ],
        ],
    ],
    'params' => $params,
];
