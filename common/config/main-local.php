
<?php
if (PHP_OS == "WINNT") {
    $dsn = 'mysql:host=localhost;dbname=mysql';
    $username = 'root';
    $password = '';
    DatenbankErzeugen($dsn, $username, $password);
    //dieser else-Zweig kann dekommentiert werden, sofern auch für LINUX eine Datenbank angelegt werden soll. Dazu werden allerdings die Parameter benötigt
} else {
    $dsn = 'mysql:host=localhost;dbname=mysql';
    $username = "phpmyadmin"; //für LINUX muss hier der Benutzer...
    $password = "1918rott"; //und hier das Passwort angegegeben werden
    DatenbankErzeugen($dsn, $username, $password);
}
return [
    'aliases' =>
    [
        '@uploading' => '@app/uploadedfiles',
        '@pictures' => '@frontend/web/img',
        '@picturesBackend' => '@backend/web/img',
        '@documentsMail' => '@app/mailAnhaenge',
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
            'useFileTransport' => false, //set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
            /*   'host' => 'smtp.strato.de',
              'username' => 't.kipp@eisvogel-online-software.de',
              'password' => 'Hannover96',
              'port' => '587',
              'encryption' => 'tls', */
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

function DatenbankErzeugen($dsn, $username, $password) {
    try {
        $conn = new \Codeception\Lib\Driver\MySql($dsn, $username, $password);
        $sql = "CREATE DATABASE IF NOT EXISTS yii2_kanatimmo CHARACTER SET=utf8 COLLATE=utf8_german2_ci;";
        $parameters = array(':created' => date('Y-m-d H:i:s'));
        $conn->executeQuery($sql, $parameters);
        return;
    } catch (\Exception $error) { //fange den schweren MySQL-Fehler ab
        echo "<center><font size='5'>Connection failed:Change parameters in /common/config/main-local.php</font></center><br>Mindestens einer der folgenden Parameter verhindert ein Verbindungsaufbau zur Datenbank:<br>User:$username<br>";
        if (empty($password))
            echo"Passwort: Kein Passwort vorhanden";
        else
            echo"Passwort:$password";
        echo"<br><br>Further informations see above<br><br> $error";
        die();
    }
}
?>
