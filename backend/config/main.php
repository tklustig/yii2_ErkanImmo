<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
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
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => false,
            'class' => 'yii\web\UrlManager',
            'rules' => [
                'home' => 'site/index',
                'login' => 'site/login',
                'reset' => 'site/request-password-reset',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'delete_user' => 'site/deluser',
                'plz_get' => 'plz/get-city-province',
                'auswahl' => 'immobilien/auswahl',
                'immobilien' => 'immobilien/index',
                'immobilien_view' => 'immobilien/view',
                'immobilien_erzeugen' => 'immobilien/create',
                'immobilien_aktualisieren' => 'immobilien/update',
                'immobilien_löschen' => 'immobilien/deleted',
                'immobilien_duplizieren' => 'immobilien/save-as-new',
                'immobilien_vorauswahl' => 'immobilien/decide',
                'immobilien_showlink' => 'immobilien/show',
                'bankverbindung' => 'bankverbindung/index',
                'bankverbindung_view' => 'bankverbindung/view',
                'bankverbindung_erzeugen' => 'bankverbindung/create',
                'bankverbindung_aktualisieren' => 'bankverbindung/update',
                'bankverbindung_löschen' => 'bankverbindung/delete',
                'bankverbindung_select' => 'bankverbindung/select',
                'bankverbindung_conc' => 'bankverbindung/conclusion',
                'pdf' => 'gridview/export/download',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/save-as-new',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<action:(contact|captcha)>' => 'site/<action>',
            ],
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/yii2_ErkanImmo/frontend/web/index.php',
        ],
    ],
    'params' => $params,
];
