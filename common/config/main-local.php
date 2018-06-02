<?php

return [
    'aliases' =>
    [
        '@uploading' => '@app/uploadedfiles',
    ],
    'modules' => [
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
        ],
        'mailbox' => [
            'view' => '@app/views/mailbox',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        // see settings on http://demos.krajee.com/grid#module
        ],
        'bootstrap' => [
            'i18n',
        ]
    ],
    'components' => [
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'yyyy-m-dd HH:ii',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'EUR',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'login' => 'site/login',
                'reset' => 'site/request-password-reset',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
                'pdf' => 'gridview/export/download',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/save-as-new',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<action:(contact|captcha)>' => 'site/<action>',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_widget',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
?>
