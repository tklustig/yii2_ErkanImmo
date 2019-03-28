<?php

return [
    'aliases' =>
    [
        '@uploading' => '@app/uploadedfiles',
        '@pictures' => '@frontend/web/img',
        '@picturesBackend'=>'@backend/web/img/'
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
            'useFileTransport' => true, //set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.strato.de',
                'username' => 't.kipp@eisvogel-online-software.de',
                'password' => 'Hannover96',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_kanatimmo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
?>
