<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
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
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'home' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'logout' => 'site/logout',
                'pdf' => 'gridview/export/download',
                'immobilien' => 'immobilien/index',
                'preview' => 'immobilien/preview',
                'plz_get' => 'plz/get-city-province',
                'immobilien_pdf' => 'immobilien/pdf',
                'immobilien_view' => 'immobilien/view',
                'immobilien_showlink' => 'immobilien/show',
                'immobilien_termin' => 'immobilien/termin',
                'auswahl' => 'immobilien/auswahl',
                'termin' => 'termin/index',
                'termin_create' => 'termin/create',
                'termin_update' => 'termin/update',
                'termin_auswahl' => 'termin/auswahl',
                'termin_anzeigen' => 'termin/index',
                'termin_link' => 'termin/link',
                'termin_zeigen' => 'termin/view',
                'termin_selektieren' => 'termin/preselect',
                'plz_get' => 'plz/get-city-province',
                'termin_viewen' => 'termin/view',
                'pdf_kunde' => '/kunde/pdf',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/save-as-new',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<action:(contact|captcha)>' => 'site/<action>',
            ],
        ],
        'urlManagerBackend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/yii2_ErkanImmo/backend/web/index.php',
        ],
    ],
    'params' => $params,
];
