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
            'enableCsrfValidation' => false,
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
            'enableStrictParsing' => true,
            'class' => 'yii\web\UrlManager',
            'rules' => [
                'home' => 'site/index',
                'login' => 'site/login',
                'reset' => 'site/request-password-reset',
                'resetus'=>'site/reset-password',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'delete_user' => 'site/deluser',
                'show_user' => 'site/showuser',
                'load_pictures' => 'site/create',
                'use_pictures' => 'site/show',
                'initialize' => 'site/initialize',
                'deleteAll_pictures' => 'site/deleteall',
                'picture2delete' => 'site/delete',
                'delete_pictures' => 'site/deletion',
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
                'immobilien_deleteuploads' => 'immobilien/deletion',
                'bankverbindung' => 'bankverbindung/index',
                'bankverbindung_view' => 'bankverbindung/view',
                'bankverbindung_erzeugen' => 'bankverbindung/create',
                'bankverbindung_aktualisieren' => 'bankverbindung/update',
                'bankverbindung_loeschen' => 'bankverbindung/delete',
                'bankverbindung_select' => 'bankverbindung/select',
                'bankverbindung_conc' => 'bankverbindung/conclusion',
                'kopf' => 'kopf/index',
                'kopf_view' => 'kopf/view',
                'kopf_erzeugen' => 'kopf/create',
                'kopf_aktualisieren' => 'kopf/update',
                'kopf_loeschen' => 'kopf/delete',
                'kunde' => 'kunde/index',
                'kunde_view' => 'kunde/view',
                'kunde_aktualisieren' => 'kunde/update',
                'kunde_loeschen' => 'kunde/delete',
                'kunde_bildloeschen' => 'kunde/delpic',
                'kunde_mailSenden' => 'kunde/send',
                'kunde_deleteupload' => 'kunde/deletion',
                'auswahlk' => 'kunde/auswahlk',            
                'rechnung' => 'rechnung/index',
                'rechnung_view' => 'rechnung/view',
                'rechnung_erzeugen' => 'rechnung/create',
                'rechnung_aktualisieren' => 'rechnung/update',
                'rechnung_loeschen' => 'rechnung/delete',
                'rechnungsart_aufrufen' => 'rechnungsart/index',
                'rechnungsart_view' => 'rechnungsart/view',
                'rechnungsart_erzeugen' => 'rechnungsart/create',
                'rechnungsart_aktualisieren' => 'rechnungsart/update',
                'rechnungsart_loeschen' => 'rechnungsart/delete',
                'begriffe' => 'begriffe/index',
                'begriffe_view' => 'begriffe/view',
                'begriffe_erzeugen' => 'begriffe/create',
                'begriffe_aktualisieren' => 'begriffe/update',
                'begriffe_loeschen' => 'begriffe/delete',
                'mailserver' => 'mailserver/index',
                'mailserver_view' => 'mailserver/view',
                'mailserver_erzeugen' => 'mailserver/create',
                'mailserver_aktualisieren' => 'mailserver/update',
                'mailserver_loeschen' => 'mailserver/delete',
                'mail' => 'mail/index',
                'baustein' => 'mail/baustein',
                'mail_view' => 'mail/view',
                'mail_erzeugen' => 'mail/create',
                'mail_aktualisieren' => 'mail/update',
                'mail_loeschen' => 'mail/delete',
                'mail_stapelSingle' => 'mail/stapelone',
                'mail_stapelSeveral' => 'mail/stapelseveral',
                'mail_severallAnhaenge' => 'mail/anhaenge',
                'mail_deleteupload' => 'mail/deletion',
                'deleteAll_pictures' => '/mail/deleteall',
                'mail_document' => 'mail/document',
                'firma' => 'firma/index',
                'firma_view' => 'firma/view',
                'firma_erzeugen' => 'firma/create',
                'firma_aktualisieren' => 'firma/update',
                'firma_loeschen' => 'firma/delete',
                'firma_auswahl' => 'firma/auswahl',
                'textbaustein' => 'textbaustein/index',
                'pdf' => 'gridview/export/download',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/save-as-new',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<action:(contact|captcha)>' => 'site/<action>',
            ],
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/Yii2_ErkanImmo/frontend/web/index.php',
        ],
    ],
    'params' => $params,
];
